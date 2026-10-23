<?php

namespace App\Services;

use App\Enums\SocialProvider;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as ProviderUser;
use RuntimeException;

class SocialLoginService
{
    /**
     * Finds the student behind this identity, creating them on their first login.
     *
     * Accounts are never merged on email: a provider may hand back an address it
     * has not verified. Students link their other providers from their profile.
     */
    public function resolve(SocialProvider $provider, ProviderUser $identity): User
    {
        return DB::transaction(function () use ($provider, $identity) {
            $account = $this->find($provider, $identity);

            if ($account) {
                $this->refresh($account, $identity);

                return $account->user;
            }

            $user = User::create([
                'name' => $this->displayName($identity),
                'avatar' => $identity->getAvatar() ?? '',
                'email' => $identity->getEmail(),
            ]);
            $this->attach($user, $provider, $identity);

            return $user;
        });
    }

    /**
     * Adds a provider to a student who is already logged in.
     *
     * @throws RuntimeException when this identity already belongs to another student
     */
    public function link(User $user, SocialProvider $provider, ProviderUser $identity): void
    {
        $account = $this->find($provider, $identity);

        if ($account && $account->user_id !== $user->id) {
            throw new RuntimeException("Ce compte {$provider->label()} est déjà lié à un autre profil.");
        }

        if ($account) {
            $this->refresh($account, $identity);

            return;
        }

        // One identity per provider and per student: linking again replaces the previous one
        $user->socialAccounts()->where('provider', $provider)->delete();
        $this->attach($user, $provider, $identity);
    }

    private function find(SocialProvider $provider, ProviderUser $identity): ?SocialAccount
    {
        return SocialAccount::with('user')
            ->where('provider', $provider)
            ->where('provider_id', (string) $identity->getId())
            ->first();
    }

    private function attach(User $user, SocialProvider $provider, ProviderUser $identity): SocialAccount
    {
        return $user->socialAccounts()->create([
            'provider' => $provider,
            'provider_id' => (string) $identity->getId(),
            'nickname' => $identity->getNickname() ?: $identity->getName(),
            'avatar' => $identity->getAvatar(),
        ]);
    }

    /**
     * Keeps the account up to date, and the student's name / avatar too when
     * this is the provider they signed up with, so they don't flip between providers.
     */
    private function refresh(SocialAccount $account, ProviderUser $identity): void
    {
        $account->update([
            'nickname' => $identity->getNickname() ?: $identity->getName(),
            'avatar' => $identity->getAvatar(),
        ]);

        $user = $account->user;
        if ($user->socialAccounts()->oldest('id')->value('id') === $account->id) {
            $user->update([
                'name' => $this->displayName($identity),
                'avatar' => $identity->getAvatar() ?? '',
                'email' => $identity->getEmail() ?? $user->email,
            ]);
        }
    }

    private function displayName(ProviderUser $identity): string
    {
        $name = $identity->getName() ?: $identity->getNickname() ?: 'Élève';

        return Str::limit($name, 50, '');
    }
}
