<?php

namespace Rahatsagor\LaravelCoreSystem;

use App\Models\AppSetting;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class LaravelCoreSystem
{

    public static function codeInit()
    {
        $name = env('ITEM_NAME');
        try {
            return Http::get('https://license.rsapplication.com/api/envato-mode', ['item' => $name]);
        } catch (Exception) {
            $r = new \stdClass();
            $r->status = false;
            $r->message = "Use your purchase code to activate. If you have any problem then please contact us contact@rsapplication.com";
            return $r;
        }
    }

    public static function activateLicense($code)
    {
        $key = $code;
        $domain = $_SERVER['SERVER_NAME'];
        $name = env('ITEM_NAME');
        $verify = Http::post('https://license.rsapplication.com/api/envato-register-license', ['domain' => $domain, 'key' => $key, 'item' => $name]);

        if ($verify->status() === 200) {
            $time = Carbon::now();
            $data = [
                "activated" => 1,
                "code" => $code,
                "error_reason" => null,
                "last_checked" => $time
            ];
            Storage::put('license.json', json_encode($data));

            return ['status' => 1, 'message' => 'License activated'];
        } elseif ($verify->status() === 202) {

            $time = Carbon::now();
            $data = [
                "activated" => 2,
                "code" => $code,
                "error_reason" => null,
                "last_checked" => $time
            ];
            Storage::put('license.json', json_encode($data));

            return ['status' => 1, 'message' => 'License activated'];
        } elseif ($verify->status() == 422) {
            return ['status' => 0, 'message' => 'Code not valid'];
        } elseif ($verify->status() == 406) {
            return ['status' => 0, 'message' => 'Code registered with another domain'];
        } elseif ($verify->status() == 404) {
            return ['status' => 0, 'message' => 'Invalid Purchase Code'];
        } else {
            return ['status' => 0, 'message' => 'Something Went Wrong, Try Again'];
        }
    }

    public static function checkLicense(): void
    {
        $settings = AppSetting::find(1);
        $token = $settings->code;
        $domain = $_SERVER['SERVER_NAME'];
        $verify = Http::get('https://license.rsapplication.com/api/envato-check',['domain' => $domain, 'token' =>  $token]);
        if ($verify->status() != 200) {
            if ($verify->status() == 401) {
                $settings->activated = 0;
                $settings->error_reason = 'Code not valid';
            } elseif ($verify->status() == 403) {
                $settings->activated = 0;
                $settings->error_reason = 'Code Blacklisted';
            } elseif ($verify->status() == 406) {
                $settings->activated = 0;
                $settings->error_reason = 'Code was registered with another domain';
            } else {
                $settings->error_reason = 'Something Went Wrong';
            }
        }
        $settings->last_checked = Carbon::now();
        $settings->update();

    }

    public static function setEnv($envKey, $envValue): void
    {
        $path = app()->environmentFilePath();
        if ($envValue == trim($envValue)){
            if (str_contains($envValue, ' ') || str_contains($envValue, ','))
            $envValue = sprintf('"%s"', $envValue);
        }
        $escaped = preg_quote('=' . env($envKey), '/');
        //update value of existing key
        file_put_contents($path, preg_replace(
            "/^{$envKey}{$escaped}/m",
            "{$envKey}={$envValue}",
            file_get_contents($path)
        ));
        //if key not exist append key=value to end of file
        $fp = fopen($path, "r");
        $content = fread($fp, filesize($path));
        fclose($fp);
        if (!strpos($content, $envKey . '=' . $envValue) && !strpos($content, $envKey . '=' . '\"' . $envValue . '\"')) {
            file_put_contents($path, $content . "\n" . $envKey . '=' . $envValue);
        }
    }

    public static function removeHTTP($url)
    {
        $disallowed = array('http://', 'https://');
        foreach ($disallowed as $d) {
            if (str_starts_with($url, $d)) {
                $url = str_replace($d, '', $url);
            }
        }
        return $url;
    }

}
