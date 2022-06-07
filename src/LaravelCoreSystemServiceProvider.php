<?php

namespace Rahatsagor\LaravelCoreSystem;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Rahatsagor\LaravelCoreSystem\Console\CheckLicense;

class LaravelCoreSystemServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-core-system');
        $this->loadRoutesFrom(__DIR__ . '/../routes/install.php');
        if ($this->app->runningInConsole()) {
            $this->commands([
                CheckLicense::class,
            ]);
            $this->app->booted(function () {
                $schedule = $this->app->make(Schedule::class);
                $schedule->command('rs:check')->weekly();
            });
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton('laravel-core-system', function () {
            return new LaravelCoreSystem;
        });
    }
}
