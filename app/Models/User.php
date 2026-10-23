<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * A student. Accounts are created on the first Twitch login, there is no password.
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'twitch_id',
        'name',
        'avatar',
        'email',
        'global_xp',
        'streak_days',
        'high_score',
        'last_score',
        'last_played_date',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'email',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'global_xp' => 'integer',
            'streak_days' => 'integer',
            'high_score' => 'integer',
            'last_score' => 'integer',
            'last_played_date' => 'date',
        ];
    }

    /**
     * @return HasMany<CharacterStat, $this>
     */
    public function characterStats(): HasMany
    {
        return $this->hasMany(CharacterStat::class);
    }

    /**
     * @return BelongsToMany<Achievement, $this>
     */
    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class)->withPivot('unlocked_at');
    }

    /**
     * Characters the user missed at least once, as { set slug: { character: wrong_count } }.
     * Used client-side to draw those characters more often.
     *
     * @return array<string, array<string, int>>
     */
    public function weakCharacterWeights(): array
    {
        $weights = [];

        $this->characterStats()
            ->with('characterSet:id,slug')
            ->where('wrong_count', '>', 0)
            ->get()
            ->each(function (CharacterStat $stat) use (&$weights) {
                $weights[$stat->characterSet->slug][$stat->character] = $stat->wrong_count;
            });

        return $weights;
    }
}
