<?php

namespace App\Http\Controllers;

use App\Enums\SocialProvider;
use App\Models\SocialAccount;
use App\Models\User;
use App\Services\AchievementService;
use Database\Seeders\AchievementSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function show(Request $request, AchievementService $achievements): View
    {
        /** @var User $user */
        $user = $request->user();

        $weak = $user->characterStats()
            ->with('characterSet:id,name')
            ->where('wrong_count', '>', 0)
            ->orderByDesc('wrong_count')
            ->limit(5)
            ->get()
            ->map(fn ($stat) => [
                'character' => $stat->character,
                'label' => $stat->characterSet->name,
                'count' => $stat->wrong_count,
            ]);

        return view('student.show', [
            'student' => $user,
            'achievements' => $achievements->allWithStatus($user)->map(fn ($a) => [
                'icon' => $a->icon,
                'name' => $a->name,
                'description' => $a->description,
                'unlocked' => $a->unlocked,
            ]),
            'weak' => $weak,
            ...$this->accounts($user->socialAccounts()->get()),
        ]);
    }

    /**
     * Linked providers first, then the configured ones the student can still link.
     *
     * @param  Collection<int, SocialAccount>  $accounts
     * @return array{providers: list<array{provider: SocialProvider, account: ?SocialAccount}>, canUnlink: bool}
     */
    private function accounts(Collection $accounts): array
    {
        $byProvider = $accounts->keyBy(fn (SocialAccount $a) => $a->provider->value);

        $providers = collect(SocialProvider::cases())
            ->filter(fn (SocialProvider $p) => $byProvider->has($p->value) || $p->isConfigured())
            ->map(fn (SocialProvider $p) => ['provider' => $p, 'account' => $byProvider->get($p->value)])
            ->sortBy(fn (array $item) => $item['account'] ? 0 : 1)
            ->values()
            ->all();

        return ['providers' => $providers, 'canUnlink' => $accounts->count() > 1];
    }

    /**
     * Same template as show() with fake data, no login or database needed:
     * lets you iterate on the profile layout locally. Only routed outside production.
     */
    public function preview(): View
    {
        $student = new User([
            'name' => 'Audric_San',
            'avatar' => 'data:image/svg+xml;utf8,'.rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="96" height="96"><rect width="96" height="96" fill="#9146ff"/><text x="48" y="62" font-size="42" text-anchor="middle" fill="#fff" font-family="sans-serif">A</text></svg>'),
            'global_xp' => 12480,
            'streak_days' => 27,
            'high_score' => 98,
            'last_score' => 84,
        ]);

        $unlocked = ['Premier pas', 'Sur la lancée', 'Une semaine de suite', '100 XP', '500 XP', '1000 XP', 'Bon score', 'Maître Hiragana'];

        return view('student.show', [
            'student' => $student,
            'achievements' => collect(AchievementSeeder::ACHIEVEMENTS)->map(fn ($a) => [
                'icon' => $a[1],
                'name' => $a[0],
                'description' => $a[2],
                'unlocked' => in_array($a[0], $unlocked, true),
            ]),
            'weak' => collect([
                ['character' => 'づ', 'label' => 'Hiragana Dakuon', 'count' => 12],
                ['character' => 'ヴ', 'label' => 'Katakana Dakuon', 'count' => 9],
                ['character' => '猫', 'label' => 'Kanji JLPT N4', 'count' => 7],
                ['character' => 'ぴ', 'label' => 'Hiragana Combo', 'count' => 5],
                ['character' => 'を', 'label' => 'Hiragana', 'count' => 4],
            ]),
            ...$this->accounts(collect([new SocialAccount(['provider' => SocialProvider::Twitch, 'nickname' => 'audric_san'])])),
        ]);
    }
}
