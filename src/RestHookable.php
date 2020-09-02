<?php

namespace Collinped\LaravelRestHooks;

use Collinped\LaravelRestHooks\Models\RestHook;
use Collinped\LaravelRestHooks\Observers\RestHookableObserver;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
    public function getUnRestHookableAttributes(): array
    {
        return [];
    }

    /**
     * Relation to changes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function restHooks(): MorphMany
    {
        return $this->morphMany(RestHook::class, 'loggable', 'model_type', 'model_id')
            ->orderBy('created_at', 'desc');
    }
}
