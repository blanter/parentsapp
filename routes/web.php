<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\ParentScoreController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GardeningController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\ChildrenTrackerController;
use App\Http\Controllers\LearningTrackerController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/download', function () {
    return view('page.download');
})->name('app.download');

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
        $appVersion = \App\Models\WebSetting::where('key', 'app_version')->first()->value ?? '1.0.0';
        return view('dashboard', compact('appVersion'));
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

    // Lifebook Journey
    Route::get('/lifebook-journey', [\App\Http\Controllers\LifebookJourneyController::class, 'index'])->name('lifebook-journey.index');
    Route::post('/lifebook-journey/update', [\App\Http\Controllers\LifebookJourneyController::class, 'update'])->name('lifebook-journey.update');

    // Habit Tracker
    Route::get('/habit-tracker/data', [\App\Http\Controllers\HabitTrackerController::class, 'index'])->name('habit-tracker.data');
    Route::post('/habit-tracker/habit', [\App\Http\Controllers\HabitTrackerController::class, 'storeHabit'])->name('habit-tracker.store');
    Route::post('/habit-tracker/habit/toggle', [\App\Http\Controllers\HabitTrackerController::class, 'toggleHabit'])->name('habit-tracker.toggle');
    Route::delete('/habit-tracker/habit/{id}', [\App\Http\Controllers\HabitTrackerController::class, 'deleteHabit'])->name('habit-tracker.delete');

    Route::post('/habit-tracker/weekly-task', [\App\Http\Controllers\HabitTrackerController::class, 'storeWeeklyTask'])->name('habit-tracker.weekly.store');
    Route::post('/habit-tracker/weekly-task/{id}/toggle', [\App\Http\Controllers\HabitTrackerController::class, 'toggleWeeklyTask'])->name('habit-tracker.weekly.toggle');
    Route::delete('/habit-tracker/weekly-task/{id}', [\App\Http\Controllers\HabitTrackerController::class, 'deleteWeeklyTask'])->name('habit-tracker.weekly.delete');
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

    // Gardening Admin
    Route::get('/admin/gardening', [\App\Http\Controllers\AdminGardeningController::class, 'index'])->name('admin.gardening.index');
    Route::post('/admin/gardening/update-score', [\App\Http\Controllers\AdminGardeningController::class, 'updateScore'])->name('admin.gardening.update-score');

    // Volunteer Mission Admin
    Route::get('/admin/volunteer-data', [\App\Http\Controllers\AdminVolunteerController::class, 'index'])->name('admin.volunteer.index');

    // Children Tracker Admin
    Route::get('/admin/children-tracker', [\App\Http\Controllers\AdminChildrenTrackerController::class, 'index'])->name('admin.children-tracker.index');
    Route::get('/admin/children-tracker/{id}', [\App\Http\Controllers\AdminChildrenTrackerController::class, 'show'])->name('admin.children-tracker.show');
    Route::get('/admin/learning-tracker', [\App\Http\Controllers\AdminLearningTrackerController::class, 'index'])->name('admin.learning-tracker.index');
    Route::get('/admin/learning-tracker/{id}', [\App\Http\Controllers\AdminLearningTrackerController::class, 'show'])->name('admin.learning-tracker.show');

    // Lifebook Journey Admin
    Route::get('/admin/lifebook-journey', [\App\Http\Controllers\AdminLifebookJourneyController::class, 'index'])->name('admin.lifebook-journey.index');
    Route::get('/admin/lifebook-journey/{userId}', [\App\Http\Controllers\AdminLifebookJourneyController::class, 'show'])->name('admin.lifebook-journey.show');

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

    // Learning Tracker
    Route::get('/learning-tracker', [LearningTrackerController::class, 'index'])->name('learning-tracker.index');
    Route::post('/learning-tracker', [LearningTrackerController::class, 'store'])->name('learning-tracker.store');
    Route::put('/learning-tracker/{id}', [LearningTrackerController::class, 'update'])->name('learning-tracker.update');
    Route::delete('/learning-tracker/{id}', [LearningTrackerController::class, 'destroy'])->name('learning-tracker.destroy');
    Route::post('/learning-tracker/{id}/reply', [LearningTrackerController::class, 'reply'])->name('learning-tracker.reply');
    Route::put('/learning-tracker/log/{id}', [LearningTrackerController::class, 'updateLog'])->name('learning-tracker.log.update');
    Route::delete('/learning-tracker/log/{id}', [LearningTrackerController::class, 'destroyLog'])->name('learning-tracker.log.destroy');
});
