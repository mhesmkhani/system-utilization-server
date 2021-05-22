<?php

use App\Events\SchedulerEvent;
use App\Http\Controllers\SystemInfoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/diskinfo', [SystemInfoController::class, 'DiskInfo']);
Route::get('/cpuinfo', [SystemInfoController::class, 'CpuInfo']);
Route::get('/memoryinfo', [SystemInfoController::class, 'MemoryInfo']);
Route::get('/account', [SystemInfoController::class, 'GetAccount']);

