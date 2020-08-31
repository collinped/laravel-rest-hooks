<?php

namespace Collinped\LaravelRestHooks;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Collinped\LaravelRestHooks\LaravelRestHooks
 */
class LaravelRestHooksFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-rest-hooks';
    }
}
