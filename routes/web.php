<?php

use App\Http\Controllers\Api\CharacterController;
use App\Http\Controllers\Api\ScoreController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [QuizController::class, 'index'])->name('quiz');
Route::get('/classement', [LeaderboardController::class, 'index'])->name('leaderboard');

Route::get('/login', [SocialAuthController::class, 'login'])->middleware('guest')->name('login');

// Also reached while logged in, to link another provider from the profile
Route::get('/login/{provider}', [SocialAuthController::class, 'redirect'])->name('login.provider');
Route::get('/login/{provider}/callback', [SocialAuthController::class, 'callback'])->name('login.callback');
// Keeps the redirect URI already registered on the Twitch application
Route::get('/callback', [SocialAuthController::class, 'callback'])->defaults('provider', 'twitch')->name('login.twitch.callback');

Route::middleware('auth')->group(function () {
    Route::get('/student', [StudentController::class, 'show'])->name('student.show');
    Route::delete('/login/{provider}', [SocialAuthController::class, 'unlink'])->name('login.unlink');
    Route::post('/logout', [SocialAuthController::class, 'logout'])->name('logout');
});

if (! app()->isProduction()) {
    Route::get('/student/preview', [StudentController::class, 'preview'])->name('student.preview');
}

// JSON endpoints used by the quiz script; session-authenticated, so they live in the web group
Route::get('/api/characters', [CharacterController::class, 'index'])->name('api.characters');
Route::post('/api/score', [ScoreController::class, 'store'])->middleware('auth')->name('api.score');
