<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    public function index(Request $request): View
    {
        return view('leaderboard.index', [
            'students' => User::orderByDesc('global_xp')
                ->orderByDesc('streak_days')
                ->limit(20)
                ->get(['id', 'name', 'avatar', 'global_xp', 'streak_days']),
            'currentUserId' => $request->user()?->id,
        ]);
    }
}
