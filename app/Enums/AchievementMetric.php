<?php

namespace App\Enums;

enum AchievementMetric: string
{
    case Xp = 'xp';
    case Streak = 'streak';
    case HighScore = 'highscore';
    case Category = 'category';
}
