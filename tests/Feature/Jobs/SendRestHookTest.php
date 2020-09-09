<?php

namespace Collinped\LaravelRestHooks\Tests\Feature\Jobs;

use Collinped\LaravelRestHooks\Jobs\SendRestHook;
use Collinped\LaravelRestHooks\Models\RestHook;
use Collinped\LaravelRestHooks\Tests\TestCase;
use Illuminate\Support\Facades\Bus;

class SendRestHookTest extends TestCase
{
    /** @test */
    public function it_creates_a_job()
    {
        Bus::fake();

        $restHook = factory(RestHook::class)->create();

        $data = [
            'target_url' => 'https://hooks.example.com/hook',
            'event' => 'user.create',
        ];

        SendRestHook::dispatch($restHook, $data);

        // $this->artisan('queue:work --once');
        Bus::assertDispatched(SendRestHook::class, function ($job) use ($restHook) {
            return $job->restHook->id === $restHook->id;
        });
    }
}
