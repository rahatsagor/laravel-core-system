<?php

namespace Rahatsagor\LaravelCoreSystem\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Rahatsagor\LaravelCoreSystem\LaravelCoreSystem;

class InstallController extends Controller
{
    public function start()
    {
        LaravelCoreSystem::setEnv('APP_URL', URL::to('/'));
        return view('laravel-core-system::start');
    }

    public function step1()
    {
        $permission = $this->checkPermissions();
        return view('laravel-core-system::step1', compact('permission'));
    }

    public function step2()
    {
        $r = LaravelCoreSystem::codeInit();
        return view('laravel-core-system::step2', compact('r'));
    }

    public function step3()
    {
        return Storage::exists('license.json')
            ? view('laravel-core-system::step3')
            : redirect('/install/step2');
    }

    public function step4()
    {
        return view('laravel-core-system::step4');
    }

    public function step5()
    {
        return view('laravel-core-system::step5');
    }

    public function verify_code(Request $request)
    {
        $res = LaravelCoreSystem::activateLicense($request->get('code'), $request->get('email'));
        return $res['status'] === 1
            ? redirect('/install/step3')
            : redirect('/install/step2/' . $res['message']);
    }

    public function database_installation(Request $request)
    {
        if (!$this->check_database_connection($request->DB_HOST, $request->DB_DATABASE, $request->DB_USERNAME, $request->DB_PASSWORD)) {
            return redirect('install/step3/database_error');
        }

        $path = base_path('.env');
        if (!file_exists($path)) {
            return redirect('install/step3');
        }

        foreach ($request->types as $type) {
            LaravelCoreSystem::setEnv($type, $request[$type]);
        }

        $this->callArtisanSafely('config:clear');
        return redirect('install/step4');
    }

    public function import_sql()
    {
        $this->callArtisanSafely('db:wipe');

        try {
            DB::unprepared(file_get_contents(base_path('database.sql')));
            $this->updateAppSettings();
        } catch (Exception $e) {
            return redirect('install/step4/importing_error');
        }

        return redirect('install/step5');
    }

    public function completeSetup(Request $request)
    {
        $request->validate([
            'app_name' => 'required',
            'frontend_url' => 'required',
            'admin_url' => 'required'
        ]);

        try {
            $this->updateEnvironmentVariables($request);
            $this->generateKeyAndLink();
            $this->callArtisanSafely('optimize:clear');

            file_put_contents(storage_path('install'), 'completed', FILE_APPEND | LOCK_EX);

            return view('laravel-core-system::finish');
        } catch (Exception $e) {
            return redirect('/install/step5/error');
        }
    }

    private function checkPermissions()
    {
        return [
            'php' => version_compare(PHP_VERSION, "8.0.2", ">="),
            'curl' => extension_loaded('curl'),
            'mysqli' => extension_loaded('mysqli'),
            'ctype' => extension_loaded('ctype'),
            'fileinfo' => extension_loaded('fileinfo'),
            'json' => extension_loaded('json'),
            'mbstring' => extension_loaded('mbstring'),
            'openssl' => extension_loaded('openssl'),
            'pdo' => extension_loaded('pdo'),
            'tokenizer' => extension_loaded('tokenizer'),
            'xml' => extension_loaded('xml'),
            'env_write' => is_writable(base_path('.env')),
            'file_write' => File::chmod(base_path('./storage/app/public')) >= 755,
        ];
    }

    private function check_database_connection($db_host, $db_name, $db_user, $db_pass)
    {
        return @mysqli_connect($db_host, $db_user, $db_pass, $db_name) !== false;
    }

    private function updateAppSettings()
    {
        $data = json_decode(file_get_contents(storage_path() . '/app/license.json'));
        $time = Carbon::parse($data->last_checked);

        DB::table('app_settings')->where('id', 1)->update([
            'activated' => $data->activated,
            'code' => $data->code,
            'error_reason' => $data->error_reason,
            'last_checked' => $time
        ]);
    }

    private function updateEnvironmentVariables(Request $request)
    {
        LaravelCoreSystem::setEnv('APP_NAME', $request->get('app_name'));
        LaravelCoreSystem::setEnv('SITE_URL', $request->get('frontend_url'));
        LaravelCoreSystem::setEnv('ADMIN_URL', $request->get('admin_url'));
        LaravelCoreSystem::setEnv('APP_ENV', 'production');
    }

    private function generateKeyAndLink()
    {
        $this->callArtisanSafely('key:generate', ['--force' => true]);
        $this->callArtisanSafely('storage:link', ['--force' => true]);
    }

    private function callArtisanSafely($command, $params = [])
    {
        try {
            Artisan::call($command, $params);
        } catch (Exception $e) {
        }
    }
}