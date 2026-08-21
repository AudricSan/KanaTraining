-- Nouveaux succes : paliers XP/streak/score supplementaires + succes de maitrise
-- par categorie (necessite la colonne category geree par AchievementDAO::conditionMet,
-- qui lit la somme des bonnes reponses de student_character_stats par character_type).
-- A executer une fois, apres achievements_seed.sql.

INSERT INTO achievements (achievements_Name, achievements_Icon, achievements_Description, achievements_Condition) VALUES
    ('2500 XP', '🎖️', 'Atteindre 2500 XP au total', 'xp:2500'),
    ('5000 XP', '👑', 'Atteindre 5000 XP au total', 'xp:5000'),
    ('10000 XP', '🚀', 'Atteindre 10000 XP au total', 'xp:10000'),
    ('Deux semaines de suite', '🔥', '14 jours d''affilée avec au moins une bonne réponse', 'streak:14'),
    ('Deux mois de suite', '🔥', '60 jours d''affilée avec au moins une bonne réponse', 'streak:60'),
    ('100 jours de suite', '💯', '100 jours d''affilée avec au moins une bonne réponse', 'streak:100'),
    ('Très bon score', '🎯', '75 bonnes réponses dans une même session', 'highscore:75'),
    ('Score parfait', '🏅', '100 bonnes réponses dans une même session', 'highscore:100'),
    ('Maître Hiragana', 'あ', '50 bonnes réponses en Hiragana', 'category:hiragana:50'),
    ('Maître Katakana', 'ア', '50 bonnes réponses en Katakana', 'category:katakana:50'),
    ('Maître Kanji N5', '漢', '1000 caractères validés en Kanji N5', 'category:kanjiN5:1000'),
    ('Maître Kanji N4', '漢', '1000 caractères validés en Kanji N4', 'category:kanjiN4:1000'),
    ('Maître Kanji N3', '漢', '1000 caractères validés en Kanji N3', 'category:kanjiN3:1000'),
    ('Maître Kanji N2', '漢', '1000 caractères validés en Kanji N2', 'category:kanjiN2:1000'),
    ('Maître Kanji N1', '漢', '1000 caractères validés en Kanji N1', 'category:kanjiN1:1000');
