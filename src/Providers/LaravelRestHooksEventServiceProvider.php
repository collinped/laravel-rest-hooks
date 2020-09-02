<?php

namespace Collinped\LaravelRestHooks\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class LaravelRestHooksEventServiceProvider extends EventServiceProvider
{
    protected $listen = [
        'Spatie\WebhookServer\Events\WebhookCallFailedEvent' => [
            'Collinped\LaravelRestHooks\Listeners\CheckForFailedRestHook',
        ],
        'Spatie\WebhookServer\Events\FinalWebhookCallFailedEvent' => [
            'Collinped\LaravelRestHooks\Listeners\DeactivateFinalFailedRestHook',
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
