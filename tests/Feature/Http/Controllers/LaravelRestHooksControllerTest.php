<?php

namespace Collinped\LaravelRestHooks\Tests\Feature\Http\Controllers;

use Collinped\LaravelRestHooks\Models\RestHook;
use Collinped\LaravelRestHooks\Tests\TestCase;
use Illuminate\Foundation\Auth\User;

class LaravelRestHooksControllerTest extends TestCase
{
    /** @test */
    public function it_can_visit_the_hooks_index()
    {
        $user = factory(User::class)->create(['id' => 1]);

        $this->actingAs($user)
            ->get('/hooks')
            ->assertOk();
    }

    /** @test */
    public function it_can_store_a_hook()
    {
        $user = factory(User::class)->create(['id' => 1]);

        $response = $this->actingAs($user)
                        ->postJson('/hooks', [
                            'event' => 'user.create',
                            'target_url' => 'urlHere',
                        ])
                        ->assertStatus(201)
                        ->assertJson([
                            'event' => 'user.create',
                            'target_url' => 'urlHere',
                        ]);
    }

    /** @test */
    public function it_can_update_a_hook()
    {
        $this->actingAs(factory(User::class)->create(['id' => 1]));

        $restHook = factory(RestHook::class)->create(['user_id' => auth()->user()->id, 'target_url' => 'testUrl']);
        $restHook->event = 'user.update';

        $this->put('/hooks/' . $restHook->id, $restHook->toArray())->assertOk();
        $this->assertDatabaseHas('rest_hooks', ['id' => $restHook->id , 'event' => 'user.update']);
    }

    /** @test */
    public function it_can_delete_a_hook()
    {
        $this->actingAs(factory(User::class)->create(['id' => 1]));

        $restHook = factory(RestHook::class)->create(['user_id' => auth()->user()->id, 'target_url' => 'testUrl']);

        $this->delete('/hooks/' . $restHook->id, $restHook->toArray())->assertOk();

        $this->assertDeleted('rest_hooks', ['id' => $restHook->id]);
    }
}
