<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CharacterStat extends Model
{
    protected $fillable = [
        'user_id',
        'character_set_id',
        'character',
        'wrong_count',
        'correct_count',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'wrong_count' => 'integer',
            'correct_count' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<CharacterSet, $this>
     */
    public function characterSet(): BelongsTo
    {
        return $this->belongsTo(CharacterSet::class);
    }
}
