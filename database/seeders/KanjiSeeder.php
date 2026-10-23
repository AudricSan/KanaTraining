<?php

namespace Database\Seeders;

use App\Models\CharacterSet;
use App\Models\Kanji;
use Illuminate\Database\Seeder;

class KanjiSeeder extends Seeder
{
    /**
     * Imports database/data/kanji.json (extracted from the former public/js/kanji.js).
     */
    public function run(): void
    {
        $data = json_decode(file_get_contents(database_path('data/kanji.json')), true, flags: JSON_THROW_ON_ERROR);
        $now = now();

        foreach ($data as $slug => $entries) {
            $set = CharacterSet::where('slug', $slug)->firstOrFail();

            $rows = [];
            foreach ($entries as $position => $entry) {
                $rows[] = [
                    'character_set_id' => $set->id,
                    'character' => $entry['character'],
                    'readings' => $entry['readings'],
                    'meanings' => $entry['meanings'],
                    'position' => $position,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            foreach (array_chunk($rows, 500) as $chunk) {
                Kanji::upsert($chunk, ['character_set_id', 'character'], ['readings', 'meanings', 'position', 'updated_at']);
            }
        }
    }
}
