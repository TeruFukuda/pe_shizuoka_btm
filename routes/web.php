<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| ここでWebアプリケーションのルートを登録します。
| これらのルートはRouteServiceProviderによってロードされ、
| "web"ミドルウェアグループに割り当てられます。
|
*/

// ホームページ
Route::get('/', function () {
    return view('welcome');
});

// 認証ルート
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/password-reset', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password-reset', [PasswordResetController::class, 'resetPassword']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // ダッシュボード
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// API ルート（認証状態確認用）
Route::prefix('api')->group(function () {
    Route::get('/user', [LoginController::class, 'getUser']);
    Route::get('/auth/check', [LoginController::class, 'checkAuth']);
});
