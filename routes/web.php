<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
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

Route::get('artisan', function () {
    Artisan::call('clear-compiled');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
});


Auth::routes();
Route::view('/', 'auth.login');

Route::get('test-claves', [TestController::class, 'actualizarClaves'])->name('test-claves');

Route::middleware(['auth'])->group(function () {
    Route::get('cerrar-sesion', [LoginController::class, 'logout'])->name('cerrar-sesion');
    Route::get('inicio', [HomeController::class, 'index'])->name('inicio');
});