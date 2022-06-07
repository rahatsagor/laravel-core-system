<?php

namespace Rahatsagor\LaravelCoreSystem;

use Illuminate\Support\Facades\Facade;

class LaravelCoreSystemFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-core-system';
    }
}
