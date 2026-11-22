<?php

namespace Database\Seeders;

use App\Models\CharacterSet;
use App\Models\Kana;
use Illuminate\Database\Seeder;

class KanaSeeder extends Seeder
{
    /**
     * Imports database/data/kana.json (extracted from the former public/js/kana.js).
     */
    public function run(): void
    {
        $data = json_decode(file_get_contents(database_path('data/kana.json')), true, flags: JSON_THROW_ON_ERROR);
        $now = now();

        foreach ($data as $slug => $entries) {
            $set = CharacterSet::where('slug', $slug)->firstOrFail();

            $rows = [];
            foreach ($entries as $position => $entry) {
                $rows[] = [
                    'character_set_id' => $set->id,
                    'character' => $entry['character'],
                    'romaji' => $entry['romaji'],
                    'is_active' => $entry['active'],
                    'position' => $position,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            Kana::upsert($rows, ['character_set_id', 'character'], ['romaji', 'is_active', 'position', 'updated_at']);
        }
    }
}
