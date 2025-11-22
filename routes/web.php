<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TimesheetController;
use App\Http\Controllers\UserController;
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

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::patch('/{user}', [UserController::class, 'update'])->name('users.update');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::prefix('projects')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/', [ProjectController::class, 'store'])->name('projects.store');
        Route::get('/{project}', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::patch('/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    });

    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
        Route::get('/{task}', [TaskController::class, 'show'])->name('tasks.show');
        Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
        Route::patch('/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
        // Route::post('/{task}/toggle-status', [TaskController::class, 'toggleStatus'])->name('tasks.toggleStatus');
    });
    Route::get('/projects/{project}/users', [ProjectController::class, 'projectUsers'])
    ->name('projects.users');


    Route::prefix('timesheets')->group(function () {
        Route::post('/start-timer', [TimesheetController::class, 'startTimer'])->name('timesheets.startTimer');
        Route::post('/stop-timer', [TimesheetController::class, 'stopTimer'])->name('timesheets.stopTimer');
        Route::delete('/{timesheet}', [TimesheetController::class, 'destroy'])->name('timesheets.destroy');
        Route::get('/{task}/task-timesheets', [TimesheetController::class, 'showTimesheets'])->name('timesheets.showTimesheets');
    });
});
