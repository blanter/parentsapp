<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\ParentScoreController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GardeningController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\ChildrenTrackerController;

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
        if (Auth::user()->role === 'admin') {
            return redirect()->intended('/admin/dashboard');
        }
        return view('dashboard');
    })->name('dashboard');

    Route::get('/coming-soon', function () {
        return view('coming-soon');
    })->name('coming-soon');

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



    // Volunteer Mission
    Route::get('/volunteer-mission', [\App\Http\Controllers\VolunteerMissionController::class, 'index'])->name('volunteer.index');
    Route::post('/volunteer-mission/toggle', [\App\Http\Controllers\VolunteerMissionController::class, 'toggle'])->name('volunteer.toggle');
});

// Admin Only Routes
Route::middleware(['auth', 'approved', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])->name('admin.dashboard');

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
    Route::patch('/manage-users/{id}/toggle', [AuthController::class, 'toggleUserStatus'])->name('admin.users.toggle');
    Route::put('/manage-users/{id}', [AuthController::class, 'updateUser'])->name('admin.users.update');

    // System Settings
    Route::get('/admin/settings', [AdminSettingController::class, 'index'])->name('admin.settings');
    Route::post('/admin/settings', [AdminSettingController::class, 'update'])->name('admin.settings.update');
});

// Public No PIN / Guest
Route::get('/leaderboard-parents', [ParentScoreController::class, 'leaderboard'])->name('parents.leaderboard');
// Teacher Routes
Route::prefix('guru')->group(function () {
    Route::get('/login', [App\Http\Controllers\TeacherAuthController::class, 'showLoginForm'])->name('teacher.login');
    Route::post('/login', [App\Http\Controllers\TeacherAuthController::class, 'login'])->name('teacher.login.post');
    Route::post('/logout', [App\Http\Controllers\TeacherAuthController::class, 'logout'])->name('teacher.logout');

    Route::middleware(['auth:teacher'])->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\TeacherAuthController::class, 'dashboard'])->name('teacher.dashboard');
        Route::get('/profile', [App\Http\Controllers\TeacherProfileController::class, 'index'])->name('teacher.profile');
        Route::post('/profile/students', [App\Http\Controllers\TeacherProfileController::class, 'updateStudents'])->name('teacher.profile.students');
    });
});

// Shared Routes (Accessible by Parents 'web' and Teachers 'teacher')
Route::middleware(['auth:web,teacher', 'approved'])->group(function () {
    // Children Tracker
    Route::get('/children-tracker', [ChildrenTrackerController::class, 'index'])->name('children-tracker.index');
    Route::get('/children-tracker/parent-aspect', [ChildrenTrackerController::class, 'parentAspect'])->name('children-tracker.parent-aspect');
    Route::get('/children-tracker/child-aspect', [ChildrenTrackerController::class, 'childAspect'])->name('children-tracker.child-aspect');
    Route::get('/children-tracker/internal-external-aspect', [ChildrenTrackerController::class, 'internalExternalAspect'])->name('children-tracker.internal-external-aspect');
    Route::post('/children-tracker/save-journal', [ChildrenTrackerController::class, 'saveJournal'])->name('children-tracker.save-journal');
    Route::post('/children-tracker/save-reflection', [ChildrenTrackerController::class, 'saveReflection'])->name('children-tracker.save-reflection');
});
