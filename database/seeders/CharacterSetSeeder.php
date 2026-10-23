<?php

namespace Database\Seeders;

use App\Enums\CharacterKind;
use App\Models\CharacterSet;
use Illuminate\Database\Seeder;

class CharacterSetSeeder extends Seeder
{
    /**
     * Training sets, in menu order. xp_value is the XP won on a correct answer
     * and lost on a wrong one: harder content is worth more.
     */
    public const SETS = [
        ['slug' => 'hiragana', 'name' => 'Hiragana', 'group' => 'Hiragana', 'kind' => CharacterKind::Kana, 'xp_value' => 1],
        ['slug' => 'hiraganaCombo', 'name' => 'Hiragana Combo', 'group' => 'Hiragana', 'kind' => CharacterKind::Kana, 'xp_value' => 2],
        ['slug' => 'hiraganaDakuon', 'name' => 'Hiragana Dakuon', 'group' => 'Hiragana', 'kind' => CharacterKind::Kana, 'xp_value' => 2],
        ['slug' => 'hiraganaDakuonCombo', 'name' => 'Hiragana Dakuon Combo', 'group' => 'Hiragana', 'kind' => CharacterKind::Kana, 'xp_value' => 3],
        ['slug' => 'katakana', 'name' => 'Katakana', 'group' => 'Katakana', 'kind' => CharacterKind::Kana, 'xp_value' => 1],
        ['slug' => 'katakanaCombo', 'name' => 'Katakana Combo', 'group' => 'Katakana', 'kind' => CharacterKind::Kana, 'xp_value' => 2],
        ['slug' => 'katakanaDakuon', 'name' => 'Katakana Dakuon', 'group' => 'Katakana', 'kind' => CharacterKind::Kana, 'xp_value' => 2],
        ['slug' => 'katakanaDakuonCombo', 'name' => 'Katakana Dakuon Combo', 'group' => 'Katakana', 'kind' => CharacterKind::Kana, 'xp_value' => 3],
        ['slug' => 'kanjiN5', 'name' => 'Kanji JLPT N5', 'group' => 'Kanji JLPT', 'kind' => CharacterKind::Kanji, 'xp_value' => 3],
        ['slug' => 'kanjiN4', 'name' => 'Kanji JLPT N4', 'group' => 'Kanji JLPT', 'kind' => CharacterKind::Kanji, 'xp_value' => 4],
        ['slug' => 'kanjiN3', 'name' => 'Kanji JLPT N3', 'group' => 'Kanji JLPT', 'kind' => CharacterKind::Kanji, 'xp_value' => 5],
        ['slug' => 'kanjiN2', 'name' => 'Kanji JLPT N2', 'group' => 'Kanji JLPT', 'kind' => CharacterKind::Kanji, 'xp_value' => 6],
        ['slug' => 'kanjiN1', 'name' => 'Kanji JLPT N1', 'group' => 'Kanji JLPT', 'kind' => CharacterKind::Kanji, 'xp_value' => 8],
    ];

    public function run(): void
    {
        foreach (self::SETS as $position => $set) {
            CharacterSet::updateOrCreate(
                ['slug' => $set['slug']],
                [...$set, 'position' => $position],
            );
        }
    }
}
