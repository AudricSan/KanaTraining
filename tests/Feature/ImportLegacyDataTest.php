<?php

namespace Tests\Feature;

use App\Models\CharacterStat;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ImportLegacyDataTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected $seeder = DatabaseSeeder::class;

    protected function setUp(): void
    {
        parent::setUp();

        config(['database.connections.legacy' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '']]);
        $schema = Schema::connection('legacy');

        $schema->create('student', function (Blueprint $t) {
            $t->integer('student_ID')->primary();
            $t->string('student_TwitchID');
            $t->string('student_Name');
            $t->string('student_Avatar');
            $t->string('student_Email')->nullable();
            $t->timestamp('student_RegisterDate');
            $t->integer('student_GlobalXp');
            $t->integer('student_StreakDays');
            $t->integer('student_HighScore');
            $t->integer('student_LastScore');
            $t->date('student_LastPlayedDate')->nullable();
        });
        $schema->create('achievements', function (Blueprint $t) {
            $t->integer('achievements_ID')->primary();
            $t->string('achievements_Name');
        });
        $schema->create('student_achievements', function (Blueprint $t) {
            $t->integer('student_ID');
            $t->integer('achievements_ID');
        });
        $schema->create('student_character_stats', function (Blueprint $t) {
            $t->integer('student_ID');
            $t->string('character_type');
            $t->string('character_key');
            $t->integer('wrong_count');
            $t->integer('correct_count');
        });

        $db = DB::connection('legacy');
        $db->table('student')->insert([
            'student_ID' => 7, 'student_TwitchID' => '999', 'student_Name' => 'Audric_San', 'student_Avatar' => 'a.png',
            'student_Email' => null, 'student_RegisterDate' => '2024-01-02 03:04:05', 'student_GlobalXp' => 1500,
            'student_StreakDays' => 12, 'student_HighScore' => 60, 'student_LastScore' => 8, 'student_LastPlayedDate' => '2026-10-20',
        ]);
        $db->table('achievements')->insert([['achievements_ID' => 1, 'achievements_Name' => 'Premier pas'], ['achievements_ID' => 2, 'achievements_Name' => '1000 XP']]);
        $db->table('student_achievements')->insert([['student_ID' => 7, 'achievements_ID' => 1], ['student_ID' => 7, 'achievements_ID' => 2]]);
        $db->table('student_character_stats')->insert(['student_ID' => 7, 'character_type' => 'kanjiN5', 'character_key' => '一', 'wrong_count' => 3, 'correct_count' => 9]);
    }

    public function test_imports_students_stats_and_achievements_and_can_be_rerun(): void
    {
        $this->artisan('kana:import-legacy')->assertSuccessful();
        $this->artisan('kana:import-legacy')->assertSuccessful();

        $user = User::sole();
        $this->assertSame(['999', 'Audric_San', 1500, 12, 60, 8], [
            $user->socialAccounts->sole()->provider_id, $user->name, $user->global_xp, $user->streak_days, $user->high_score, $user->last_score,
        ]);
        $this->assertSame('2026-10-20', $user->last_played_date->toDateString());
        $this->assertSame('2024-01-02', $user->created_at->toDateString());
        $this->assertEqualsCanonicalizing(['Premier pas', '1000 XP'], $user->achievements->pluck('name')->all());

        $stat = CharacterStat::sole();
        $this->assertSame(['kanjiN5', '一', 3, 9], [$stat->characterSet->slug, $stat->character, $stat->wrong_count, $stat->correct_count]);
    }
}
