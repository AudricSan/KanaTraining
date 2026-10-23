<?php

namespace App\Models;

use App\Enums\CharacterKind;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CharacterSet extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'group',
        'kind',
        'xp_value',
        'position',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kind' => CharacterKind::class,
            'xp_value' => 'integer',
            'position' => 'integer',
        ];
    }

    /**
     * @return HasMany<Kana, $this>
     */
    public function kana(): HasMany
    {
        return $this->hasMany(Kana::class)->orderBy('position');
    }

    /**
     * @return HasMany<Kanji, $this>
     */
    public function kanji(): HasMany
    {
        return $this->hasMany(Kanji::class)->orderBy('position');
    }

    /**
     * @param  Builder<CharacterSet>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('position');
    }

    public function isKanji(): bool
    {
        return $this->kind === CharacterKind::Kanji;
    }
}
