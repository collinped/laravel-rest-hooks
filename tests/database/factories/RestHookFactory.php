<?php

use Collinped\LaravelRestHooks\Models\RestHook;
use Faker\Generator;

/* @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(RestHook::class, function (Generator $faker) {
    return [
        'user_id' => 1,
        'event' => 'user.create',
        'target_url' => '',
        'trigger' => '',
        'active' => true,
    ];
});
