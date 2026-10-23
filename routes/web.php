<?php

use App\Http\Controllers\Api\CharacterController;
use App\Http\Controllers\Api\ScoreController;
use App\Http\Controllers\Auth\TwitchController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [QuizController::class, 'index'])->name('quiz');
Route::get('/classement', [LeaderboardController::class, 'index'])->name('leaderboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [TwitchController::class, 'login'])->name('login');
    Route::get('/login/twitch', [TwitchController::class, 'redirect'])->name('login.twitch');
    // Keeps the redirect URI already registered on the Twitch application
    Route::get('/callback', [TwitchController::class, 'callback'])->name('login.twitch.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/student', [StudentController::class, 'show'])->name('student.show');
    Route::get('/logout', [TwitchController::class, 'logout'])->name('logout');
});

if (! app()->isProduction()) {
    Route::get('/student/preview', [StudentController::class, 'preview'])->name('student.preview');
}

// JSON endpoints used by the quiz script; session-authenticated, so they live in the web group
Route::get('/api/characters', [CharacterController::class, 'index'])->name('api.characters');
Route::post('/api/score', [ScoreController::class, 'store'])->middleware('auth')->name('api.score');
