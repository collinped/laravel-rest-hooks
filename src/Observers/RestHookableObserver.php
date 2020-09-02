<?php


namespace Collinped\LaravelRestHooks\Observers;

use Collinped\LaravelRestHooks\RestHookable;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RestHookableObserver
{
    /**
     * @var mixed
     */
    private $userId;

    /**
     * @var Dispatcher
     */
    private $events;

    /**
     * LoggableObserver constructor.
     *
     * @param Authenticatable $user
     * @param Dispatcher $events
     */
    public function __construct(Authenticatable $user, Dispatcher $events)
    {
        $this->userId = $user->getKey();
        $this->events = $events;
    }

    /**
     * Log a created model.
     *
     * @param RestHookable|Model $model
     *
     * @return void
     */
    public function created(RestHookable $model): void
    {
        $data = $this->buildDataToLog('create', $model);

        // Change::create($data);
    }

    /**
     * Log an updated model.
     *
     * @param RestHookable|Model $model
     *
     * @return void
     */
    public function updated(RestHookable $model): void
    {
        $data = $this->buildDataToLog('update', $model);

        foreach ($this->getHookableAttributes($model) as $change) {
            $data['attribute'] = $change['attribute'];
            $data['old_value'] = $change['old_value'];
            $data['new_value'] = $change['new_value'];

            // Change::create($data);
        }
    }

    /**
     * Log a deleted model.
     *
     * @param RestHookable|Model $model
     *
     * @return void
     */
    public function deleted(RestHookable $model): void
    {
        $data = $this->buildDataToLog('delete', $model);

        // Change::create($data);
    }
}
