<?php

namespace Collinped\LaravelRestHooks\Observers;

use Collinped\LaravelRestHooks\Jobs\SendRestHook;
use Collinped\LaravelRestHooks\RestHookable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RestHookableObserver
{
    /**
     * @var mixed
     */
    private $userId;

    /**
     * LoggableObserver constructor.
     *
     * @param  Authenticatable  $user
     */
    public function __construct(Authenticatable $user)
    {
        $this->userId = $user->getKey();
    }

    /**
     * Log a created model.
     *
     * @param  RestHookable|Model  $model
     * @return void
     */
    public function created(RestHookable $model): void
    {
        if ($restHooks = $this->getRestHooks($model)) {
            $data = $this->buildDataToSend('create', $model);
            foreach ($restHooks as $restHook) {
                SendRestHook::dispatch($restHook, $data);
            }
        }
    }

    /**
     * Log an updated model.
     *
     * @param  RestHookable|Model  $model
     * @return void
     */
    public function updated(RestHookable $model): void
    {
        $data = $this->buildDataToSend('update', $model);

//        foreach ($this->getHookableAttributes($model) as $change) {
//            $data['attribute'] = $change['attribute'];
//            $data['old_value'] = $change['old_value'];
//            $data['new_value'] = $change['new_value'];
//
//            // Change::create($data);
//        }
    }

    /**
     * Log a deleted model.
     *
     * @param  RestHookable|Model  $model
     * @return void
     */
    public function deleted(RestHookable $model): void
    {
        $data = $this->buildDataToSend('delete', $model);

        // Change::create($data);
    }

    protected function getRestHooks(RestHookable $model)
    {
        return $model->restHooks()
                        ->where('active', true)
                        ->where('event', 'create')
                        ->where('user_id', $this->userId);
    }

    /**
     * Build up the array of data to send.
     *
     * @param  string  $eventAction
     * @param  RestHookable|Model  $model
     * @return array
     */
    protected function buildDataToSend(string $eventAction, RestHookable $model): array
    {
        return [
            'event' => $model->getRestHookEventName().'.'.$eventAction,
            'user_id' => $this->userId,
        ];
    }

    /**
     * Return an array of old and new values that should be logged.
     *
     * @param  RestHookable|Model  $model
     * @return array
     */
    private function getLoggableAttributes(RestHookable $model): array
    {
        // Get the list of all the changed attributes
        $attributes = $model->getDirty();

        // The list of timestamp attributes to filter out
        $timestamps = $this->getTimestampAttributes($model);

        // If the model has a defined list of attributes to log,
        // use that and filter the timestamp list accordingly
        if ($only = $model->getLoggableAttributes()) {
            $attributes = array_only($attributes, $only);
            $timestamps = array_diff($timestamps, $only);
        }

        // Finally, filter out any unloggable attributes,
        // and any remaining unloggable timestamp attributes
        if ($except = $model->getUnloggableAttributes()) {
            $attributes = array_except($attributes, array_merge($except, $timestamps));
        }

        // Get a list of attributes to be obfuscated
        $hidden = $model->getHidden();

        return array_map(
            function ($attribute, $value) use ($model, $hidden) {
                if (in_array($attribute, $hidden, null)) {
                    return [
                        'attribute' => $attribute,
                        'old_value' => '** HIDDEN **',
                        'new_value' => '** HIDDEN **',
                    ];
                }

                return [
                    'attribute' => $attribute,
                    'old_value' => $model->getOriginal($attribute),
                    'new_value' => $value,
                ];
            },
            array_keys($attributes),
            $attributes
        );
    }

    /**
     * Get the timestamp attributes on the model that are normally not sent to the rest hook.
     *
     * @param  RestHookable|Model  $model
     * @return array
     */
    private function getTimestampAttributes(RestHookable $model): array
    {
        if (! $model->usesTimestamps()) {
            return [];
        }

        $attributes = [
            $model->getCreatedAtColumn(),
            $model->getUpdatedAtColumn(),
        ];
        if (method_exists($model, 'getDeletedAtColumn')) {
            $attributes[] = $model->getDeletedAtColumn();
        }

        return $attributes;
    }
}
