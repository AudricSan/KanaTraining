<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class TwitchController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function redirect(): RedirectResponse
    {
        return Socialite::driver('twitch')->redirect();
    }

    /**
     * Twitch sends the user back here: create the account on first login,
     * refresh name / avatar / email on the next ones.
     */
    public function callback(Request $request): RedirectResponse
    {
        if ($request->has('error')) {
            return redirect()->route('login');
        }

        try {
            $twitchUser = Socialite::driver('twitch')->user();
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('login');
        }

        $user = User::updateOrCreate(
            ['twitch_id' => (string) $twitchUser->getId()],
            [
                'name' => $twitchUser->getName() ?: $twitchUser->getNickname(),
                'avatar' => $twitchUser->getAvatar() ?? '',
                'email' => $twitchUser->getEmail(),
            ],
        );

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->route('student.show');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('quiz');
    }
}
