<?php

namespace App\Http\Controllers;

use App\Models\CharacterSet;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function index(Request $request): View
    {
        return view('quiz.index', [
            'groups' => CharacterSet::ordered()->get()->groupBy('group'),
            'user' => $request->user(),
        ]);
    }
}
