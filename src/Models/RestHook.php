<?php

namespace Collinped\LaravelRestHooks\Models;

use Illuminate\Database\Eloquent\Model;

class RestHook extends Model
{
    protected $table = 'rest_hooks';

    protected $fillable = ['user_id', 'event', 'target_url', 'trigger'];
}
