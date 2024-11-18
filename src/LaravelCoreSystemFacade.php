<?php

namespace Rahatsagor\LaravelCoreSystem;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool checkLicense()
 * @method static object codeInit()
 * @method static array getLicenseInfo()
 * @method static array activateLicense(string $code, string $email)
 * @method static void setEnv(string $key, string $value)
 * @method static string removeHTTP(string $url)
 * @method static array getTgInfo(string $item)
 * @method static array configureTg(string $item, string $chat_id)
 * @method static array sendTgMessage(string $item, string $type, string $message)
 * @see \Rahatsagor\LaravelCoreSystem\LaravelCoreSystem
 */
class LaravelCoreSystemFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-core-system';
    }
}