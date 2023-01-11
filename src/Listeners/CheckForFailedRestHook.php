<?php

namespace Collinped\LaravelRestHooks\Listeners;

use Collinped\LaravelRestHooks\Models\RestHook;
use Spatie\WebhookServer\Events\WebhookCallFailedEvent;

class CheckForFailedRestHook
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
     * @param  WebhookCallFailedEvent  $event
     * @return void
     */
    public function handle(WebhookCallFailedEvent $event)
    {
        if ($event->response->getStatusCode() === 410) {
            $restHook = RestHook::where('target_url', $event->webhookUrl)->first();
            if ($restHook) {
                $restHook->update(['active' => false]);
            }
        }
    }
}
