<?php

namespace App\Models;

use App\Enums\AchievementMetric;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Achievement extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'description',
        'metric',
        'threshold',
        'character_set_id',
        'position',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'metric' => AchievementMetric::class,
            'threshold' => 'integer',
            'position' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<CharacterSet, $this>
     */
    public function characterSet(): BelongsTo
    {
        return $this->belongsTo(CharacterSet::class);
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('unlocked_at');
    }
}
