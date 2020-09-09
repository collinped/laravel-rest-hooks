<?php

namespace Collinped\LaravelRestHooks\Tests\Feature\Models;

use Collinped\LaravelRestHooks\Tests\TestCase;
use Collinped\LaravelRestHooks\Models\RestHook;

class RestHookTest extends TestCase
{
    /** @test */
    public function it_can_create_a_rest_hook()
    {
        factory(RestHook::class)->create();

        $this->assertDatabaseCount('rest_hooks', 1);
    }


}
