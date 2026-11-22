<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CharacterSet;
use App\Models\Kana;
use App\Models\Kanji;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CharacterController extends Controller
{
    /**
     * Every set with its characters, in the shape the quiz script expects:
     * { kana: { hiragana: [[char, romaji, active], ...] }, kanji: { kanjiN5: [{kanji, read, translate}, ...] } }.
     * Cached until the data changes (see version()), and cacheable by the browser.
     */
    public function index(Request $request): JsonResponse
    {
        $version = self::version();

        $payload = Cache::rememberForever("characters.{$version}", function () {
            $sets = CharacterSet::ordered()->with(['kana', 'kanji'])->get();
            $kana = [];
            $kanji = [];

            foreach ($sets as $set) {
                if ($set->isKanji()) {
                    $kanji[$set->slug] = $set->kanji->map(fn (Kanji $k) => [
                        'kanji' => $k->character,
                        'read' => $k->readings,
                        'translate' => $k->meanings,
                    ])->all();
                } else {
                    $kana[$set->slug] = $set->kana->map(fn (Kana $k) => [$k->character, $k->romaji, $k->is_active])->all();
                }
            }

            return ['kana' => $kana, 'kanji' => $kanji];
        });

        return response()->json($payload)
            ->setEtag($version)
            ->setPublic()
            ->setMaxAge(3600);
    }

    /**
     * Changes whenever a set, kana or kanji is added, removed or edited.
     */
    public static function version(): string
    {
        return md5(implode('|', [
            CharacterSet::count(), CharacterSet::max('updated_at'),
            Kana::count(), Kana::max('updated_at'),
            Kanji::count(), Kanji::max('updated_at'),
        ]));
    }
}
