<?php

namespace Collinped\LaravelRestHooks\Listeners;

use Collinped\LaravelRestHooks\Models\RestHook;
use Spatie\WebhookServer\Events\FinalWebhookCallFailedEvent;

class DeactivateFinalFailedRestHook
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FinalWebhookCallFailedEvent  $event
     * @return void
     */
    public function handle(FinalWebhookCallFailedEvent $event)
    {
        RestHook::where('target_url', $event->webhookUrl)->update(['active' => false]);
    }
}
