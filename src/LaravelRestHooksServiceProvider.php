<?php

namespace Collinped\LaravelRestHooks;

use Collinped\LaravelRestHooks\Commands\LaravelRestHooksCommand;
use Illuminate\Support\ServiceProvider;

class LaravelRestHooksServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-rest-hooks.php' => config_path('laravel-rest-hooks.php'),
            ], 'config');

            $migrationFileName = 'create_laravel_rest_hooks_table.php';
            if (! $this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
                ], 'migrations');
            }

            $this->commands([
                LaravelRestHooksCommand::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-rest-hooks');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-rest-hooks.php', 'laravel-rest-hooks');
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
