<?php

namespace Collinped\LaravelRestHooks\Models;

use Collinped\LaravelRestHooks\RestHookable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Config;

class RestHook extends Model
{
    protected $table = 'rest_hooks';

    protected $fillable = ['user_id', 'event', 'target_url', 'trigger', 'active'];

    public function scopeForUser(Builder $query, $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Relation to user who made the change.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Config::get('rest_hooks.user_model'));
    }

    /**
     * Polymorphic relation to model.
     *
     * @return MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope to find changes of a given type.
     *
     * @param Builder $query
     * @param string $type
     *
     * @return Builder
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to find changes for a given model.
     *
     * @param Builder $query
     * @param RestHookable|Model $model
     *
     * @return Builder
     */
    public function scopeForModel(Builder $query, RestHookable $model): Builder
    {
        return $query->where('model_type', get_class($model));
    }
}
