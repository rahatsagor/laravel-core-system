<?php

namespace Rahatsagor\LaravelCoreSystem\Http\Controllers;

use App\Models\AppSetting;
use Carbon\Carbon;
use DB;
use Exception;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use PDO;
use Rahatsagor\LaravelCoreSystem\LaravelCoreSystem;
use URL;

class InstallController extends Controller
{

    public function start()
    {
        $url = URL::to('/');
        LaravelCoreSystem::setEnv('APP_URL', $url);
        return view('laravel-core-system::start');
    }

    public function step1()
    {

        $permission['php'] = version_compare(PHP_VERSION, "8.0.2", ">");
        $permission['curl'] = extension_loaded('curl');
        $permission['mysqli'] = extension_loaded('mysqli');
        $permission['ctype'] = extension_loaded('ctype');
        $permission['fileinfo'] = extension_loaded('fileinfo');
        $permission['json'] = extension_loaded('json');
        $permission['mbstring'] = extension_loaded('mbstring');
        $permission['openssl'] = extension_loaded('openssl');
        $permission['pdo'] = extension_loaded('pdo');
        $permission['tokenizer'] = extension_loaded('tokenizer');
        $permission['xml'] = extension_loaded('xml');
        $permission['env_write'] = is_writable(base_path('.env'));
        $permission['file_write'] = File::chmod(base_path('./storage/app/public')) >= 755;

        return view('laravel-core-system::step1', compact('permission'));

    }

    public function step2()
    {
        $r = LaravelCoreSystem::codeInit();
        return view('laravel-core-system::step2', compact('r'));
    }

    public function step3()
    {
        if (file_exists(storage_path('/app/license.json'))){
            return view('laravel-core-system::step3');
        } else {
            return redirect('/install/step2');
        }
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

        $res = LaravelCoreSystem::activateLicense($request->get('code'));
        if ($res['status'] === 1) {
            return redirect('/install/step3');
        } else {
            $error = $res['message'];
            return redirect('/install/step2/' . $error);
        }
    }


    public function database_installation(Request $request)
    {

        try {
            $connection = new PDO(
                sprintf("mysql:host=%s:%s;dbname=%s", $request->get('DB_HOST'), $request->get('DB_PORT'), $request->get('DB_DATABASE')),
                $request->get('DB_USERNAME'),
                $request->get('DB_PASSWORD', '')
            );
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception) {
            return redirect('install/step3/database_error');
        }

        $path = base_path('.env');
        if (file_exists($path)) {
            foreach ($request->types as $type) {
                LaravelCoreSystem::setEnv($type, $request[$type]);
            }
            try {
                Artisan::call('config:clear');
            } catch (Exception) {
            }
            return redirect('install/step4');
        } else {
            return redirect('install/step3');
        }

    }

    public function import_sql()
    {
        try {
            Artisan::call('db:wipe');
        } catch (Exception) {
        }

        try {
            $sql_path = base_path('database.sql');
            DB::unprepared(file_get_contents($sql_path));
        } catch (Exception) {
            return redirect('install/step4/importing_error');
        }

        try {
            $data = json_decode(file_get_contents(storage_path() . '/app/license.json'));
            $time = Carbon::parse($data->last_checked);
            $settings = AppSetting::find(1);
            $settings->activated = $data->activated;
            $settings->code = $data->code;
            $settings->error_reason = $data->error_reason;
            $settings->last_checked = $time;
            $settings->update();
        } catch (Exception) {
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
            LaravelCoreSystem::setEnv('APP_NAME', $request->get('app_name'));
            LaravelCoreSystem::setEnv('SITE_URL', $request->get('frontend_url'));
            LaravelCoreSystem::setEnv('ADMIN_URL', $request->get('admin_url'));
        } catch (Exception) {
            return redirect('/install/step5/error');
        }
        try {
            Artisan::call('key:generate', ['--force' => true]);
            Artisan::call('storage:link', ['--force' => true]);
            LaravelCoreSystem::setEnv('APP_ENV', 'production');
        } catch (Exception $e) {
            return redirect('/install/step5/error');
        }
        try {
            Artisan::call('optimize:clear');
        } catch (Exception) {
        }

        file_put_contents(storage_path('install'), 'completed', FILE_APPEND | LOCK_EX);

        return view('laravel-core-system::finish');

    }

}
