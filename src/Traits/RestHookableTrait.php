<?php

namespace Collinped\LaravelRestHooks\Traits;

use Collinped\LaravelRestHooks\Observers\ModelObserver;

trait RestHookableTrait
{
    public static function bootObservantTrait()
    {
        static::observe(new ModelObserver);
    }
}
