CREATE TABLE `kanaachievement` (
  `Achievement_ID` int(11) NOT NULL,
  `Achievement_Name` varchar(20) DEFAULT NULL,
  `Achievement_Description` mediumtext,
  `Achievement_Condition` mediumtext
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `kanauser` (
  `User_ID` int(11) NOT NULL,
  `User_Name` varchar(20) DEFAULT NULL,
  `user_mail` varchar(255) DEFAULT NULL,
  `user_viewer` int(11) DEFAULT NULL,
  `user_type` varchar(50) DEFAULT NULL,
  `User_Avatar` varchar(255) DEFAULT NULL,
  `User_ScoreHighest` int(11) DEFAULT NULL,
  `User_ScoreLower` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `kanauser` (`User_ID`, `User_Name`, `user_mail`, `user_viewer`, `user_type`, `User_Avatar`, `User_ScoreHighest`, `User_ScoreLower`) VALUES
(133565026, 'audric_san', 'audricrosier@gmail.com', 4070, 'affiliate', 'https://static-cdn.jtvnw.net/jtv_user_pictures/544c69c0-332a-42d7-b05c-4e2b23490cbe-profile_image-300x300.png', NULL, NULL);

CREATE TABLE `kanauserachievement` (
  `UserAchievement_ID` int(11) NOT NULL,
  `UserAchievement_UserUserID` int(11) NOT NULL,
  `UserAchievement_AchievementAchievementID` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `kanaachievement`
  ADD PRIMARY KEY (`Achievement_ID`);


ALTER TABLE `kanauser`
  ADD PRIMARY KEY (`User_ID`);


ALTER TABLE `kanauserachievement`
  ADD PRIMARY KEY (`UserAchievement_ID`),
  ADD KEY `UserAchievement_UserUserID` (`UserAchievement_UserUserID`),
  ADD KEY `UserAchievement_AchievementAchievementID` (`UserAchievement_AchievementAchievementID`);


ALTER TABLE `kanaachievement`
  MODIFY `Achievement_ID` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `kanauser`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133565027;


ALTER TABLE `kanauserachievement`
  MODIFY `UserAchievement_ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
