<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kana extends Model
{
    protected $table = 'kana';

    protected $fillable = [
        'character_set_id',
        'character',
        'romaji',
        'is_active',
        'position',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
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
}
