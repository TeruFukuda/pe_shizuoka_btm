<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GenreController;
use Illuminate\Support\Facades\Route;

// ホームページ
Route::get('/', fn() => redirect()->route('login'));

// 認証ルート
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/password-reset', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password-reset', [PasswordResetController::class, 'resetPassword']);
});

// ログイン必須ルート
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // ダッシュボード
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 問題管理（QuestionsControllerに集約されている前提）
    Route::prefix('questions')->name('questions.')->group(function () {
        Route::get('/', [QuestionsController::class, 'index'])->name('index');
        Route::get('/create', [QuestionsController::class, 'create'])->name('create'); // ここもControllerへ！
        Route::get('/select', [QuestionsController::class, 'select'])->name('select');
        Route::post('/', [QuestionsController::class, 'store'])->name('store'); // 新規保存用
        Route::put('/{question}', [QuestionsController::class, 'update'])->name('update'); // 編集保存用
        Route::get('/{question}/edit', [QuestionsController::class, 'edit'])->name('edit'); // ここもControllerへ！
    });

    // ジャンル管理
    Route::resource('genres', GenreController::class)->except(['show', 'destroy']);
});

// API
Route::prefix('api')->group(function () {
    Route::get('/user', [LoginController::class, 'getUser']);
    Route::get('/auth/check', [LoginController::class, 'checkAuth']);
});