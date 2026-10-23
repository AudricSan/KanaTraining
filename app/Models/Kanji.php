<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kanji extends Model
{
    protected $table = 'kanji';

    protected $fillable = [
        'character_set_id',
        'character',
        'readings',
        'meanings',
        'position',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
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
