<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SessionController::class, 'loginPage'])->name('login');
Route::post('/login', [SessionController::class, 'login'])->name('login.submit');

Route::get('forgot-password', [ForgetPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('forgot-password', [ForgetPasswordController::class, 'sendResetLink'])->name('password.email');

Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('update-password', [ResetPasswordController::class, 'resetPassword'])->name('password.change');


Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [SessionController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, '__invoke'])->name('dashboard');
});
