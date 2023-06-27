-- Active: 1676541047683@@127.0.0.1@3306@kanatraining2.0

-- ---

-- Table "KanaUser"

-- ---

DROP TABLE IF EXISTS `KanaUser`;

CREATE TABLE
    `KanaUser` (
        `KanaUser_ID` INTEGER NOT NULL AUTO_INCREMENT,
        `KanaUser_Name` VARCHAR(255) NULL DEFAULT NULL,
        `KanaUser_Avatar` VARCHAR(255) NULL DEFAULT NULL,
        PRIMARY KEY (`KanaUser_ID`)
    );

-- ---

-- Table "KanaUserScore"

-- ---

DROP TABLE IF EXISTS `KanaUserScore`;

CREATE TABLE
    `KanaUserScore` (
        `KanaUserScore_ID` INTEGER NOT NULL AUTO_INCREMENT,
        `KanaUserScore_User` INTEGER NOT NULL,
        `KanaUserScore_Dificulty` VARCHAR(255) NULL DEFAULT NULL,
        `KanaUserScore_ScoreHighest` VARCHAR(255) NULL DEFAULT NULL,
        PRIMARY KEY (`KanaUserScore_ID`)
    );

-- ---

-- Table "KanaUserAchievement"

-- ---

DROP TABLE IF EXISTS `KanaUserAchievement`;

CREATE TABLE
    `KanaUserAchievement` (
        `KanaUserAchievement_ID` INTEGER NOT NULL,
        `KanaUserAchievement_User` INTEGER NOT NULL,
        `KanaUserAchievement_Achievement` INTEGER NOT NULL,
        PRIMARY KEY (`KanaUserAchievement_ID`)
    );

-- ---

-- Table "KanaAchievement"

-- ---

DROP TABLE IF EXISTS `KanaAchievement`;

CREATE TABLE
    `KanaAchievement` (
        `KanaAchievement_ID` INTEGER NOT NULL AUTO_INCREMENT,
        `KanaAchievement_Name` VARCHAR(255) NULL DEFAULT NULL,
        `KanaAchievement_Icon` VARCHAR(255) NULL DEFAULT NULL,
        `KanaAchievement_Condition` MEDIUMTEXT NULL DEFAULT NULL,
        PRIMARY KEY (`KanaAchievement_ID`)
    );

-- ---

-- Foreign Keys

-- ---

ALTER TABLE `KanaUserScore`
ADD
    FOREIGN KEY (KanaUserScore_User) REFERENCES `KanaUser` (`KanaUser_ID`);

ALTER TABLE
    `KanaUserAchievement`
ADD
    FOREIGN KEY (KanaUserAchievement_User) REFERENCES `KanaUser` (`KanaUser_ID`);

ALTER TABLE
    `KanaUserAchievement`
ADD
    FOREIGN KEY (
        KanaUserAchievement_Achievement
    ) REFERENCES `KanaAchievement` (`KanaAchievement_ID`);

-- ---

-- Table Properties

-- ---

-- ALTER TABLE `KanaUser` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `KanaUserScore` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `KanaUserAchievement` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ALTER TABLE `KanaAchievement` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---

-- Test Data

-- ---

-- INSERT INTO `KanaUser` (`KanaUser_ID`,`KanaUser_Name`) VALUES

-- ("","");

-- INSERT INTO `KanaUserScore` (`KanaUserScore_ID`,`KanaUserScore_User`,`KanaUserScore_Dificulty`,`KanaUserScore_ScoreHighest`) VALUES

-- ("","","","");

-- INSERT INTO `KanaUserAchievement` (`KanaUserAchievement_ID`,`KanaUserAchievement_User`,`KanaUserAchievement_Achievement`) VALUES

-- ("","","");

-- INSERT INTO `KanaAchievement` (`KanaAchievement_ID`,`KanaAchievement_Name`,`KanaAchievement_Condition`) VALUES

-- ("","","");

INSERT INTO
    `KanaUser` (
        `KanaUser_ID`,
        `KanaUser_Name`,
        `KanaUser_Avatar`
    )
VALUES (
        133565026,
        "audric_san",
        "https://static-cdn.jtvnw.net/jtv_user_pictures/544c69c0-332a-42d7-b05c-4e2b23490cbe-profile_image-300x300.png"
    );

INSERT INTO
    `KanaAchievement` (
        `KanaAchievement_ID`,
        `KanaAchievement_Name`,
        `KanaAchievement_Condition`,
        `KanaAchievement_Icon`
    )
VALUES (
        1,
        "First Play",
        "play one time",
        "public/image/badge.png"
    ), (
        2,
        "Time of study",
        "get 10/10 on each dificulty",
        "public/image/badge.png"
    ), (
        3,
        "Good study",
        "get 50/50 on each dificulty",
        "public/image/badge.png"
    ), (
        4,
        "Just like a boss",
        "get 100/100 on each dificulty",
        "public/image/badge.png"
    ), (
        5,
        "I am a native speaker",
        "get 1.000/1.000 on each dificulty",
        "public/image/badge.png"
    );