<?php

namespace Collinped\LaravelRestHooks\Tests\Feature\Listeners;

use Collinped\LaravelRestHooks\Models\RestHook;
use Collinped\LaravelRestHooks\Tests\TestCase;
use Collinped\LaravelRestHooks\Tests\TestClasses\TestClient;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Spatie\WebhookServer\Events\WebhookCallFailedEvent;
use Spatie\WebhookServer\WebhookCall;

class CheckForFailedRestHookTest extends TestCase
{
    private TestClient $testClient;

    public function setUp(): void
    {
        parent::setUp();

        $this->setWebHookServerDefaults();

        Event::fake([
            WebhookCallFailedEvent::class,
        ]);

        $this->testClient = new TestClient();

        app()->bind(Client::class, function () {
            return $this->testClient;
        });
    }

    /** @test */
    public function it_should_deactivate_the_rest_hook()
    {
        $restHook = factory(RestHook::class)->create([
            'target_url' => 'https://hooks.example.com/webhook',
            'event' => 'user.create',
        ]);

        $this->testClient->letRequestReturnClosedResthook();

        $this->baseWebhook()->dispatchNow();
        //$this->artisan('queue:work --once');
        Event::assertDispatched(WebhookCallFailedEvent::class);
    }

    protected function baseWebhook(): WebhookCall
    {
        return WebhookCall::create()
            ->url('https://hooks.example.com/webhook')
            ->useSecret('abc')
            ->payload(['a' => 1]);
    }

    protected function setWebHookServerDefaults()
    {
        Config::set('webhook-server.queue', 'default');
        Config::set('webhook-server.http_verb', 'post');
        Config::set('webhook-server.tries', 3);
        Config::set('webhook-server.backoff_strategy', \Spatie\WebhookServer\BackoffStrategy\ExponentialBackoffStrategy::class);
        Config::set('webhook-server.timeout_in_seconds', 3);
        Config::set('webhook-server.signature_header_name', 'Signature');
        Config::set('webhook-server.signer', \Spatie\WebhookServer\Signer\DefaultSigner::class);
        Config::set('webhook-server.headers', [
            'Content-Type' => 'application/json',
        ]);
        Config::set('webhook-server.tags', []);
        Config::set('webhook-server.verify_ssl', true);
    }
}
