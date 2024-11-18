<?php

use Illuminate\Support\Facades\Route;
use Rahatsagor\LaravelCoreSystem\Http\Controllers\InstallController;

Route::group([
    'prefix' => 'install',
    'middleware' => 'installer'
], static function () {
    Route::get('/', [InstallController::class,'start'])->name('install.start');
    Route::get('/step1', [InstallController::class,'step1'])->name('install.step1');
    Route::get('/step2/{error?}', [InstallController::class,'step2'])->name('install.step2');
    Route::get('/step3/{error?}', [InstallController::class,'step3'])->name('install.step3');
    Route::get('/step4/{error?}', [InstallController::class,'step4'])->name('install.step4');
    Route::get('/step5/{error?}', [InstallController::class,'step5'])->name('install.step5');

    Route::post('/verify_code', [InstallController::class,'verify_code'])->name('install.verify_code');
    Route::post('/database_installation', [InstallController::class,'database_installation'])->name('install.db');
    Route::get('/import_sql', [InstallController::class,'import_sql'])->name('install.import_sql');
    Route::post('/completed', [InstallController::class,'completeSetup'])->name('install.complete');
});

Route::get('/go-to-frontend',function (){return redirect(config('app.site_url'));})->name('install.frontend');
Route::get('/go-to-admin',function (){return redirect(config('app.admin_url'));})->name('install.admin');

Route::get('/get-server-address', function (){
    return response()->json([
        'server' => $_SERVER['SERVER_NAME']
    ]);
});


