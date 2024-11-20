<?php

namespace Rahatsagor\LaravelCoreSystem;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class LaravelCoreSystem
{
    private const LICENSE_API_BASE = 'https://license.rsapplication.com/api/';
    private const TG_API_BASE = 'https://tg-notification.rsapplication.com/';

    public static function codeInit()
    {
        $name = env('ITEM_NAME');
        try {
            return Http::get(self::LICENSE_API_BASE . 'envato-mode', ['item' => $name]);
        } catch (Exception $e) {
            return (object)[
                'status' => false,
                'message' => "Use your purchase code to activate. If you have any problem then please contact us contact@rsapplication.com"
            ];
        }
    }

    public static function activateLicense($code, $email)
    {
        $domain = $_SERVER['SERVER_NAME'];
        $name = env('ITEM_NAME');
        $verify = Http::post(self::LICENSE_API_BASE . 'envato-register-license', [
            'key' => $code,
            'domain' => $domain,
            'email' => $email,
            'item' => $name
        ]);
        $status = $verify->status();
        if ($status === 200 || $status === 202) {
            $data = [
                "activated" => ($status === 200) ? 1 : 2,
                "code" => $code,
                "error_reason" => null,
                "last_checked" => Carbon::now()
            ];
            Storage::put('license.json', json_encode($data));
            return ['status' => 1, 'message' => 'License activated'];
        }

        $errorMessages = [
            422 => 'Code not valid',
            406 => 'Code registered with another domain',
            404 => 'Invalid Purchase Code'
        ];

        return ['status' => 0, 'message' => $errorMessages[$status] ?? 'Something Went Wrong, Try Again'];
    }

    public static function checkLicense(): void
    {
        $settings = DB::table('app_settings')->where('id', 1)->first();
        $token = $settings->code;
        $s_url = config('app.url') . '/get-server-address';
        $res = Http::get($s_url);
        $domain = $res->json()['server'];
        $verify = Http::get(self::LICENSE_API_BASE . 'envato-check', compact('domain', 'token'));

        $updateData = ['last_checked' => Carbon::now()];

        if ($verify->status() != 200) {
            $updateData['activated'] = 0;
            $updateData['error_reason'] = self::getErrorReason($verify->status());
        }

        DB::table('app_settings')->where('id', 1)->update($updateData);
    }

    private static function getErrorReason($status): string
    {
        $errorReasons = [
            401 => 'Code not valid',
            403 => 'Code Blacklisted',
            406 => 'Code was registered with another domain'
        ];

        return $errorReasons[$status] ?? 'Something Went Wrong';
    }

    public static function setEnv($envKey, $envValue): void
    {
        $path = app()->environmentFilePath();
        $envValue = self::formatEnvValue($envValue);

        $content = file_get_contents($path);
        $pattern = "/^{$envKey}=.*/m";

        $content = preg_match($pattern, $content)
            ? preg_replace($pattern, "{$envKey}={$envValue}", $content)
            : $content . "\n{$envKey}={$envValue}";

        file_put_contents($path, $content);
    }

    private static function formatEnvValue($value): string
    {
        return (str_contains($value, ' ') || str_contains($value, ',')) ? sprintf('"%s"', $value) : $value;
    }

    public static function removeHTTP($url): string
    {
        return preg_replace('#^https?://#', '', $url);
    }

    public static function getTgInfo($item)
    {
        return self::tgRequest('get', 'info', compact('item'));
    }

    public static function configureTg($item, $chat_id)
    {
        $app_name = env('APP_NAME');
        $admin_url = env('ADMIN_URL');
        return self::tgRequest('post', 'configure', compact('item', 'chat_id', 'app_name', 'admin_url'));
    }

    public static function sendTgMessage($item, $n_type, $msg)
    {
        return self::tgRequest('post', 'send-message', compact('item', 'n_type', 'msg'));
    }

    private static function tgRequest($method, $endpoint, $params = [])
    {
        $settings = DB::table('app_settings')->where('id', 1)->first();
        $token = $settings->code;
        $domain = $_SERVER['SERVER_NAME'];

        $params = array_merge(compact('domain', 'token'), $params);

        $verify = Http::$method(self::TG_API_BASE . $endpoint, $params);

        if ($verify->status() !== 200) {
            if ($verify->status() === 401) {
                $updateData = [
                    'activated' => 0,
                    'error_reason' => self::getErrorReason($verify->status())
                ];
                DB::table('app_settings')->where('id', 1)->update($updateData);
            }
            return ['status' => 'error', 'data' => $verify->json()['error']];
        }

        return $verify->json();
    }
}