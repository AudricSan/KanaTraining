<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Reference data only (sets, kana, kanji, achievements). Every seeder is
     * idempotent, so `php artisan db:seed` can be re-run after editing the data.
     */
    public function run(): void
    {
        $this->call([
            CharacterSetSeeder::class,
            KanaSeeder::class,
            KanjiSeeder::class,
            AchievementSeeder::class,
        ]);
    }
}
