<?php

namespace Rahatsagor\LaravelCoreSystem\Console;

use Illuminate\Console\Command;
use Rahatsagor\LaravelCoreSystem\LaravelCoreSystem;

class CheckLicense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rs:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Product License';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        LaravelCoreSystem::checkLicense();
        return 1;
    }
}
