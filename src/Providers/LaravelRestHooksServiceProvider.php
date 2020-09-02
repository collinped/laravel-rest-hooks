<?php

namespace Collinped\LaravelRestHooks\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LaravelRestHooksServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/rest-hooks.php' => config_path('rest-hooks.php'),
            ], 'config');

            $migrationFileName = 'create_laravel_rest_hooks_table.php';
            if (! $this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    __DIR__ . "/../../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
                ], 'migrations');
            }

            Route::macro('rest_hooks', function (string $prefix) {
                Route::apiResource($prefix, 'Collinped\LaravelRestHooks\Http\Controllers\LaravelRestHooksController');
                Route::prefix($prefix)->group(function () {
                    Route::post('/{id}/unsubscribe', 'Collinped\LaravelRestHooks\Http\Controllers\LaravelRestHooksController@unsubscribe');
                });
            });
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/rest-hooks.php', 'rest-hooks');

        $this->app->register(LaravelRestHooksEventServiceProvider::class);
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName);
        foreach (glob(database_path("migrations/*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }
}
