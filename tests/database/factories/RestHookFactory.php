<?php

use \Faker\Generator;
use Collinped\LaravelRestHooks\Models\RestHook;

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
