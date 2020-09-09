<?php

namespace Collinped\LaravelRestHooks;

use Collinped\LaravelRestHooks\Models\RestHook;
use Collinped\LaravelRestHooks\Observers\RestHookableObserver;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use ReflectionClass;

trait RestHookable
{

    /**
     * Register events that intercept when model is being saved.
     */
    public static function bootRestHookable()
    {
        static::observe(app(RestHookableObserver::class));
    }

    /**
     * Get a list of attributes that are loggable.
     *
     * @return array
     */
    public function getRestHookableAttributes(): array
    {
        return [];
    }

    /**
     * Get a list of attributes that are unloggable.
     *
     * @return array
     */
    public function getRestUnHookableAttributes(): array
    {
        return [];
    }

    public function getRestHookEventName(): string
    {
        // (new \ReflectionClass(get_called_class()))->getShortName()
        return strtolower((new ReflectionClass($this))->getShortName());
    }

    /**
     * Relation to changes.
     *
     * @return MorphMany
     */

    // This could be a HasMany based on getRestHookEventName()?
    public function restHooks(): MorphMany
    {
        return $this->morphMany(RestHook::class, 'loggable', 'model_type', 'model_id')
            ->orderBy('created_at', 'desc');
    }
}
