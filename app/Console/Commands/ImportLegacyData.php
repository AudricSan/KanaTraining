<?php

namespace App\Console\Commands;

use App\Models\Achievement;
use App\Models\CharacterSet;
use App\Models\CharacterStat;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Copies students, their per-character stats and unlocked achievements from the
 * former PDO app database (tables student, student_character_stats,
 * student_achievements). Safe to re-run: rows are matched on Twitch id,
 * character and achievement name, and counters are overwritten, not added.
 */
class ImportLegacyData extends Command
{
    protected $signature = 'kana:import-legacy {--connection=legacy : Database connection of the former app}';

    protected $description = 'Import students, character stats and achievements from the former KanaTraining database';

    public function handle(): int
    {
        if (CharacterSet::count() === 0 || Achievement::count() === 0) {
            $this->error('Run `php artisan db:seed` first: sets and achievements are needed to map the old data.');

            return self::FAILURE;
        }

        $legacy = DB::connection($this->option('connection'));
        $setIds = CharacterSet::pluck('id', 'slug');
        $achievementIds = Achievement::pluck('id', 'name');

        // former student_ID => new users.id
        $userIds = [];

        foreach ($legacy->table('student')->orderBy('student_ID')->cursor() as $student) {
            $user = User::updateOrCreate(['twitch_id' => $student->student_TwitchID], [
                'name' => $student->student_Name,
                'avatar' => $student->student_Avatar,
                'email' => $student->student_Email,
                'global_xp' => $student->student_GlobalXp,
                'streak_days' => $student->student_StreakDays,
                'high_score' => $student->student_HighScore,
                'last_score' => $student->student_LastScore,
                'last_played_date' => $student->student_LastPlayedDate,
            ]);

            if ($user->wasRecentlyCreated) {
                $user->created_at = $student->student_RegisterDate;
                $user->save();
            }

            $userIds[$student->student_ID] = $user->id;
        }

        $stats = 0;
        foreach ($legacy->table('student_character_stats')->cursor() as $row) {
            if (! isset($userIds[$row->student_ID], $setIds[$row->character_type])) {
                continue;
            }

            CharacterStat::updateOrCreate([
                'user_id' => $userIds[$row->student_ID],
                'character_set_id' => $setIds[$row->character_type],
                'character' => $row->character_key,
            ], [
                'wrong_count' => $row->wrong_count,
                'correct_count' => $row->correct_count,
            ]);
            $stats++;
        }

        $unlocks = 0;
        $rows = $legacy->table('student_achievements as sa')
            ->join('achievements as a', 'a.achievements_ID', '=', 'sa.achievements_ID')
            ->select('sa.student_ID', 'a.achievements_Name')
            ->cursor();

        foreach ($rows as $row) {
            if (! isset($userIds[$row->student_ID], $achievementIds[$row->achievements_Name])) {
                continue;
            }

            User::find($userIds[$row->student_ID])
                ->achievements()
                ->syncWithoutDetaching([$achievementIds[$row->achievements_Name]]);
            $unlocks++;
        }

        $this->info(sprintf('Imported %d students, %d character stats, %d unlocked achievements.', count($userIds), $stats, $unlocks));

        return self::SUCCESS;
    }
}
