<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;

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
    return view('auth/login');
});

Route::middleware(['auth','verified'])->group(function () {
    // dashboard
    Route::get('/dashboard', [DashboardController::class,'dashboard'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // reports
    Route::middleware(['role:admin|manager'])->group(function () {
        Route::get('/reports-dashboard', [\App\Http\Controllers\ReportsController::class,'index'])->name('reports');

        // User Management
        Route::get('/user-management-dashboard',[\App\Http\Controllers\UserController::class,'index'])->name('users-management');
    });

    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/user-management', [UserController::class, 'index'])->name('user-management.index');
        Route::post('/user-management', [UserController::class, 'store'])->name('user-management.store');
        Route::get('/user-management/{id}/edit', [UserController::class, 'edit'])->name('user-management.edit');
        Route::put('/user-management/{id}', [UserController::class, 'update'])->name('user-management.update');
        Route::delete('/user-management/{id}', [UserController::class, 'destroy'])->name('user-management.destroy');
    });

    });

    
    Route::middleware(['role:admin'])->group(function () {
        // settings
        Route::get('/settings-dashboard', [\App\Http\Controllers\SettingsController::class,'getSetting'])->name('settings');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [ReportsController::class, 'dashboard'])->name('dashboard');
        Route::post('/reports/store', [ReportsController::class, 'store'])->name('reports.store');

        // Halaman laporan untuk Admin & Manager
    Route::middleware(['role:admin|manager'])->group(function () {
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.reports-dashboard');
    });
    
    });




require __DIR__ . '/auth.php';
