CREATE TABLE
    student (
        student_ID INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
        student_TwitchID VARCHAR(255) NOT NULL UNIQUE,
        student_Name VARCHAR(25) NOT NULL,
        student_Avatar VARCHAR(255) NOT NULL,
        student_Email VARCHAR(255) NULL,
        student_RegisterDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        student_GlobalXp INTEGER NOT NULL DEFAULT 0,
        student_StreakDays INTEGER NOT NULL DEFAULT 0,
        student_HighScore INTEGER NOT NULL DEFAULT 0,
        student_LastScore INTEGER NOT NULL DEFAULT 0,
        student_LastPlayedDate DATE NULL
    );

CREATE TABLE
    lessons (
        lessons_ID INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
        lessons_Name VARCHAR(25) NOT NULL,
        lessons_Description TEXT NOT NULL
    );

CREATE TABLE
    achievements (
        achievements_ID INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
        achievements_Name VARCHAR(25) NOT NULL,
        achievements_Icon VARCHAR(255) NOT NULL,
        achievements_Description TEXT NOT NULL,
        achievements_Condition TEXT NOT NULL
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE
    student_achievements (
        sa_ID INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
        student_ID INTEGER NOT NULL,
        achievements_ID INTEGER NOT NULL,
        UNIQUE KEY unique_student_achievement (student_ID, achievements_ID),
        FOREIGN KEY (student_ID) REFERENCES student(student_ID),
        FOREIGN KEY (achievements_ID) REFERENCES achievements(achievements_ID)
    );

CREATE TABLE
    student_lessons (
        sl_ID INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
        student_ID INTEGER NOT NULL,
        lesson_ID INTEGER NOT NULL,
        FOREIGN KEY (student_ID) REFERENCES student(student_ID),
        FOREIGN KEY (lesson_ID) REFERENCES lessons(lessons_ID)
    );
