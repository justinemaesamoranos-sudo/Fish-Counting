<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LiveCameraController;
use App\Http\Controllers\FishCountController; 

// Default to login
Route::get('/', function () {
    return view('Authentication.login');
});

// Register routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Login routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Protected Dashboard Routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Fish Counts & Reports
    Route::get('/fish-counts', [DashboardController::class, 'fishCounts'])->name('fish-counts');
    Route::post('/fish-counts/delete-all', [DashboardController::class, 'deleteAllData'])->name('fish-counts.delete-all');
    Route::delete('/fish-counts/{id}', [DashboardController::class, 'deleteImage'])->name('fish-counts.delete');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    Route::get('/chart-data', [DashboardController::class, 'chartData'])->name('chart-data');
    Route::get('/comparative-analysis', [DashboardController::class, 'comparativeAnalysis'])->name('comparative-analysis');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Live Camera
    Route::get('/live-camera', [LiveCameraController::class, 'index'])->name('live-camera');

    // Capture button calls controller method
    Route::post('/live-camera/capture', [LiveCameraController::class, 'capture'])->name('live-camera.capture');

});
