CREATE TABLE
    student (
        student_ID INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
        student_Name VARCHAR(25) NOT NULL,
        student_Avatar VARCHAR(255) NOT NULL,
        student_RegisterDate TIMESTAMP NOT NULL,
        student_GlobalXp INTEGER NOT NULL,
        student_StreakDays INTEGER NOT NULL,
        student_HighScore INTEGER NOT NULL,
        student_LastScore INTEGER NOT NULL
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
    );

CREATE TABLE
    student_achievements (
        sa_ID INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
        student_ID INTEGER NOT NULL,
        achievements_ID INTEGER NOT NULL,
        FOREIGN KEY (student_ID) REFERENCES student(student_ID),
        FOREIGN KEY (achievements_ID) REFERENCES Achievements(achievements_ID)
    );

CREATE TABLE
    student_lessons (
        sl_ID INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
        student_ID INTEGER NOT NULL,
        lesson_ID INTEGER NOT NULL,
        FOREIGN KEY (student_ID) REFERENCES student(student_ID),
        FOREIGN KEY (lesson_ID) REFERENCES lesson(lesson_ID)
    );