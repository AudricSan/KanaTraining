<?php

namespace App\Http\Controllers\Auth;

use App\Enums\SocialProvider;
use App\Http\Controllers\Controller;
use App\Services\SocialLoginService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use RuntimeException;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirect;
use Throwable;

class SocialAuthController extends Controller
{
    public function login(): View
    {
        return view('auth.login', ['providers' => SocialProvider::configured()]);
    }

    public function redirect(SocialProvider $provider): SymfonyRedirect
    {
        abort_unless($provider->isConfigured(), 404);

        return Socialite::driver($provider->value)->redirect();
    }

    /**
     * The provider sends the student back here, either to log in or, when
     * already logged in, to link this provider to their profile.
     */
    public function callback(Request $request, SocialProvider $provider, SocialLoginService $logins): RedirectResponse
    {
        abort_unless($provider->isConfigured(), 404);

        $linking = Auth::check();
        $back = fn (string $message) => redirect()->route($linking ? 'student.show' : 'login')->with('error', $message);

        if ($request->has('error')) {
            return $back("Connexion à {$provider->label()} annulée.");
        }

        try {
            $identity = Socialite::driver($provider->value)->user();
        } catch (InvalidStateException) {
            return $back('La demande de connexion a expiré, réessaie.');
        } catch (Throwable $e) {
            report($e);

            return $back("{$provider->label()} n'a pas répondu, réessaie dans un instant.");
        }

        if ($linking) {
            try {
                $logins->link($request->user(), $provider, $identity);
            } catch (RuntimeException $e) {
                return $back($e->getMessage());
            }

            return redirect()->route('student.show')->with('status', "Compte {$provider->label()} lié.");
        }

        Auth::login($logins->resolve($provider, $identity), remember: true);
        $request->session()->regenerate();

        return redirect()->intended(route('student.show'));
    }

    public function unlink(Request $request, SocialProvider $provider): RedirectResponse
    {
        $user = $request->user();

        if ($user->socialAccounts()->count() <= 1) {
            return back()->with('error', 'Garde au moins un moyen de connexion.');
        }

        $user->socialAccounts()->where('provider', $provider)->delete();

        return back()->with('status', "Compte {$provider->label()} retiré.");
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('quiz');
    }
}
