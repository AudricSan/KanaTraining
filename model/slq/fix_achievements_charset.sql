-- Fixes achievement icons showing as "?" instead of emoji: the achievements table
-- wasn't in utf8mb4 (needed for 4-byte emoji), so they got mangled on insert.
-- Run this once on an install affected by the issue.

ALTER TABLE achievements
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

UPDATE achievements SET achievements_Icon = '🥉' WHERE achievements_Name = 'Premier pas';
UPDATE achievements SET achievements_Icon = '🔥' WHERE achievements_Name = 'Sur la lancée';
UPDATE achievements SET achievements_Icon = '🔥' WHERE achievements_Name = 'Une semaine de suite';
UPDATE achievements SET achievements_Icon = '🔥' WHERE achievements_Name = 'Un mois de suite';
UPDATE achievements SET achievements_Icon = '⭐' WHERE achievements_Name = '100 XP';
UPDATE achievements SET achievements_Icon = '🌟' WHERE achievements_Name = '500 XP';
UPDATE achievements SET achievements_Icon = '💫' WHERE achievements_Name = '1000 XP';
UPDATE achievements SET achievements_Icon = '🎯' WHERE achievements_Name = 'Bon score';
UPDATE achievements SET achievements_Icon = '🏆' WHERE achievements_Name = 'Excellent score';
