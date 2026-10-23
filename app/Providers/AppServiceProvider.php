<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Twitch\TwitchExtendSocialite;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(SocialiteWasCalled::class, [TwitchExtendSocialite::class, 'handle']);

        // Characters the student keeps missing, read by the quiz script to draw them more often
        View::composer('quiz.index', function ($view) {
            $user = auth()->user();
            $view->with('weakCharacters', $user?->weakCharacterWeights() ?? []);
        });
    }
}
