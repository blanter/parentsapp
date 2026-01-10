<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\ParentScoreController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GardeningController;
use App\Http\Controllers\AdminSettingController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Approved Users Dashboard
Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Gardening
    Route::get('/gardening', [GardeningController::class, 'index'])->name('gardening.index');
    Route::get('/gardening/{id}', [GardeningController::class, 'show'])->name('gardening.show');
    Route::post('/gardening', [GardeningController::class, 'store'])->name('gardening.store');
    Route::delete('/gardening/{id}', [GardeningController::class, 'destroy'])->name('gardening.destroy');

    // Gardening Progress
    Route::post('/gardening/{id}/progress', [GardeningController::class, 'storeProgress'])->name('gardening.progress.store');
    Route::delete('/gardening/progress/{id}', [GardeningController::class, 'destroyProgress'])->name('gardening.progress.destroy');
});

// Admin Only Routes
Route::middleware(['auth', 'approved', 'admin'])->group(function () {
    // Login dengan PIN (if still needed, but now protected by admin)
    Route::get('/access', [AccessController::class, 'index'])->name('access.index');
    Route::post('/access', [AccessController::class, 'store'])->name('access.store');

    Route::get('/parents-score', [ParentScoreController::class, 'index'])->name('parents.index');
    Route::post('/parents-score', [ParentScoreController::class, 'store'])->name('parents.store');
    Route::delete('/parents-score/{id}', [ParentScoreController::class, 'destroy'])->name('scores.destroy');

    // Edit Score
    Route::get('/edit-score/{id}', [ParentScoreController::class, 'editscore'])->name('score.edit');
    Route::put('/edit-score/{id}', [ParentScoreController::class, 'updatescore'])->name('score.update');
    // User Management
    Route::get('/manage-users', [AuthController::class, 'listUsers'])->name('admin.users');
    Route::patch('/manage-users/{id}/approve', [AuthController::class, 'approveUser'])->name('admin.users.approve');

    // System Settings
    Route::get('/admin/settings', [AdminSettingController::class, 'index'])->name('admin.settings');
    Route::post('/admin/settings', [AdminSettingController::class, 'update'])->name('admin.settings.update');
});

// Public No PIN / Guest
Route::get('/leaderboard-parents', [ParentScoreController::class, 'leaderboard'])->name('parents.leaderboard');