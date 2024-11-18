<?php

namespace Rahatsagor\LaravelCoreSystem;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Rahatsagor\LaravelCoreSystem\Console\CheckLicense;

class LaravelCoreSystemServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void 
    {
        $this->registerSingleton();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerViews()
             ->registerRoutes();

        $this->registerConsoleServices();
    }

    /**
     * Register the singleton binding.
     */
    private function registerSingleton(): void
    {
        $this->app->singleton('laravel-core-system', fn() => new LaravelCoreSystem);
    }

    /**
     * Register view resources.
     */
    private function registerViews(): self
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-core-system');
        return $this;
    }

    /**
     * Register routes.
     */
    private function registerRoutes(): self
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/install.php');
        return $this;
    }

    /**
     * Register console-specific services.
     */
    private function registerConsoleServices(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([CheckLicense::class]);
        $this->scheduleLicenseCheck();
    }

    /**
     * Schedule the license check.
     */
    private function scheduleLicenseCheck(): void
    {
        $this->app->booted(function () {
            try {
                $schedule = $this->app->make(Schedule::class);
                $schedule->command('rs:check')
                        ->daily()
                        ->withoutOverlapping()
                        ->onFailure(function () {
                            logger()->error('License check failed');
                        })
                        ->runInBackground();
            } catch (\Exception $e) {
                logger()->error('Failed to schedule license check: ' . $e->getMessage());
            }
        });
    }
}