-- Run this once on an install that already has the base schema (db.sql) applied.
-- Adds the table used to track which characters each student struggles with.

CREATE TABLE
    student_character_stats (
        scs_ID INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
        student_ID INTEGER NOT NULL,
        character_type VARCHAR(30) NOT NULL,
        character_key VARCHAR(10) NOT NULL,
        wrong_count INTEGER NOT NULL DEFAULT 0,
        correct_count INTEGER NOT NULL DEFAULT 0,
        UNIQUE KEY unique_student_character (student_ID, character_type, character_key),
        FOREIGN KEY (student_ID) REFERENCES student(student_ID)
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
