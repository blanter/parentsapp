<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\ParentScoreController;

Route::get('/', function () {
    return redirect()->route('access.index');
});

// Login dengan PIN
Route::get('/access', [AccessController::class, 'index'])->name('access.index');
Route::post('/access', [AccessController::class, 'store'])->name('access.store');

// Halaman utama
Route::middleware('check.pin')->group(function () {
    Route::get('/parents-score', [ParentScoreController::class, 'index'])->name('parents.index');
    Route::post('/parents-score', [ParentScoreController::class, 'store'])->name('parents.store');
    Route::delete('/parents-score/{id}', [ParentScoreController::class, 'destroy'])->name('scores.destroy');
    
    // Edit Score
    Route::get('/edit-score/{id}', [ParentScoreController::class, 'editscore'])->name('score.edit');
    Route::put('/edit-score/{id}', [ParentScoreController::class, 'updatescore'])->name('score.update');
    
});

// Public No PIN
Route::get('/leaderboard-parents', [ParentScoreController::class, 'leaderboard'])->name('parents.leaderboard');