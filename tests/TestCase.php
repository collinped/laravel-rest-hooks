<?php

namespace Collinped\LaravelRestHooks\Tests;

use Collinped\LaravelRestHooks\Providers\LaravelRestHooksServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
        $this->setUpGuard();

        $this->withFactories(__DIR__.'/database/factories');

        Route::rest_hooks('hooks');
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelRestHooksServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        include_once __DIR__.'/../database/migrations/create_laravel_rest_hooks_table.php.stub';
        (new \CreateLaravelRestHooksTable())->up();
    }

    protected function setUpDatabase()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('remember_token');
            $table->timestamps();
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });
    }

    protected function setUpGuard()
    {
        config([
            'auth.guards.alternate' => [
                'driver' => 'session',
                'provider' => 'users',
            ],
        ]);
    }
}
