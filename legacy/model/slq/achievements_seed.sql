-- Run this once on an install that already has the base schema (db.sql) applied.
-- Adds the missing unique constraint on student_achievements and seeds the achievement definitions.

ALTER TABLE student_achievements
    ADD UNIQUE KEY unique_student_achievement (student_ID, achievements_ID);

INSERT INTO achievements (achievements_Name, achievements_Icon, achievements_Description, achievements_Condition) VALUES
    ('Premier pas', '🥉', 'Répondre correctement pour la première fois', 'xp:1'),
    ('Sur la lancée', '🔥', '3 jours d''affilée avec au moins une bonne réponse', 'streak:3'),
    ('Une semaine de suite', '🔥', '7 jours d''affilée avec au moins une bonne réponse', 'streak:7'),
    ('Un mois de suite', '🔥', '30 jours d''affilée avec au moins une bonne réponse', 'streak:30'),
    ('100 XP', '⭐', 'Atteindre 100 XP au total', 'xp:100'),
    ('500 XP', '🌟', 'Atteindre 500 XP au total', 'xp:500'),
    ('1000 XP', '💫', 'Atteindre 1000 XP au total', 'xp:1000'),
    ('Bon score', '🎯', '20 bonnes réponses dans une même session', 'highscore:20'),
    ('Excellent score', '🏆', '50 bonnes réponses dans une même session', 'highscore:50');
