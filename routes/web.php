<?php

use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
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

Route::middleware('web')->group(function () {
    Route::domain(env('APP_DOMAIN'))->group(function () {
        // default route
        Route::get('/', function () {
            return view('welcome');
        });
        // User Auth Route
        Auth::routes();
        // user redirect after authentication
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    });
    Route::domain('admin.' . env('APP_DOMAIN'))->middleware('guest:admin')->group(function () {
        // default route
        Route::name('admin.')->group(function () {
            Route::get("/", function () {
                return redirect(route('admin.login'));
            });
            //Auth::routes();
            Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
            Route::post('/login', [AdminLoginController::class, 'login'])->name('post.login');
        });
    });
    Route::domain('admin.' . env('APP_DOMAIN'))->middleware('auth:admin')->group(function () {
        // default route
        Route::name('admin.')->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
            Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

        });
    });


});
