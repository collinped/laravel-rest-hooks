<?php

namespace Collinped\LaravelRestHooks\Jobs;

use Collinped\LaravelRestHooks\Models\RestHook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookServer\WebhookCall;

class SendRestHook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public RestHook $restHook;

    protected array $data;

    /**
     * Create a new job instance.
     *
     * @param  RestHook  $restHook
     * @param $data
     */
    public function __construct(RestHook $restHook, $data)
    {
        $this->restHook = $restHook;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @param  WebhookCall  $webhookCall
     * @return void
     */
    public function handle(WebhookCall $webhookCall)
    {
        $webhookCall->create()
            ->url($this->restHook->target_url)
            ->payload($this->data)
//            ->useSecret('123')
            ->dispatchNow();
    }
}
