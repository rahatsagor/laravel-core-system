<?php

namespace Rahatsagor\LaravelCoreSystem\Console;

use Illuminate\Console\Command;
use Rahatsagor\LaravelCoreSystem\LaravelCoreSystem;

class CheckLicense extends Command
{
    protected $signature = 'rs:check';

    protected $description = 'Check the license status';

    public function handle()
    {
        LaravelCoreSystem::checkLicense();
        $this->info('License check completed.');
    }
}