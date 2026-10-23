<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecordAnswerRequest;
use App\Models\Achievement;
use App\Models\CharacterSet;
use App\Services\ScoreService;
use Illuminate\Http\JsonResponse;

class ScoreController extends Controller
{
    /**
     * Records one answer of the logged-in student and returns the updated stats,
     * plus the achievements this answer unlocked.
     */
    public function store(RecordAnswerRequest $request, ScoreService $scores): JsonResponse
    {
        $set = $request->filled('type')
            ? CharacterSet::where('slug', $request->string('type'))->first()
            : null;

        $stats = $scores->recordAnswer(
            $request->user(),
            $request->boolean('correct'),
            $request->integer('good'),
            $set,
            $request->filled('character') ? (string) $request->string('character') : null,
        );

        $stats['newAchievements'] = $stats['newAchievements']
            ->map(fn (Achievement $a) => ['name' => $a->name, 'icon' => $a->icon])
            ->all();

        return response()->json($stats);
    }
}
