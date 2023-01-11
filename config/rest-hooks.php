<?php

return [

    'table' => env('REST_HOOKS_TABLE', 'rest_hooks'),

    'user_model' => env('REST_HOOKS_USER_MODEL', App\Models\User::class),
];
