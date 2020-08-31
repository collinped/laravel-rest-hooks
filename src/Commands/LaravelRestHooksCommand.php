<?php

namespace Collinped\LaravelRestHooks\Commands;

use Illuminate\Console\Command;

class LaravelRestHooksCommand extends Command
{
    public $signature = 'laravel-rest-hooks';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
