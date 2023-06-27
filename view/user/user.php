<?php

$_SESSION['user'] = 133565026;

require_once('../model/class/User.php');
require_once('../model/class/UserScore.php');
require_once('../model/class/UserAchievement.php');
require_once('../model/class/Achievement.php');

require_once('../model/dao/UserDAO.php');
require_once('../model/dao/UserAchievementDAO.php');
require_once('../model/dao/AchievementDAO.php');
require_once('../model/dao/UserScoreDAO.php');

echo "
 </ul>
 </div>
  <div class='center'>
";

$userDAO            = new UserDAO;
$UserAchievementDAO = new UserAchievementDAO;
$AchievementDAO     = new AchievementDAO;
$UserScoreDAO       = new UserScoreDAO;

$userInfo        = $userDAO->fetch($_SESSION['user']);
$achievement     = $AchievementDAO->fetchAll();
$userAchievement = $UserAchievementDAO->fetch($_SESSION['user']);
$userScore       = $UserScoreDAO->fetch($_SESSION['user']);

// var_dump($userInfo);
// var_dump($userAchievement);
// var_dump($userScore);
// var_dump($achievement);

?>
<div class='outside'>
  <div class='color'>
    <div id='achievement'>
      <?php
      $class = NULL;
      foreach ($achievement as $avalue) {
        foreach ($userAchievement as $uavalue) {
          if ($uavalue->_Achievement === $avalue->_id) {
            $class = "apply";
          }
        }
        echo "<img class='$class' src='$avalue->_Icon'>";
      }
      ?>
    </div>
  </div>

  <div class='imgback'>
    <img src=' <?= $userInfo->_avatar ?>'>
  </div>

  <div class='info'>
    <div>
      <h2> User Name </h2>
      <p>
        <?= $userInfo->_displayName ?>
      </p>
    </div>

    <div>
      <h2> High Score </h2>
      <div class='score'>
        <?php
        foreach ($userScore as $value) {
          echo "<p>" . $value->_Dificulty . " : " . $value->_ScoreHighest . '</p>';
        }
        ?>
      </div>
    </div>

    <div>
      <h2> Last Score </h2>
      <p>
        <span id='dificulty'></span> : <span id='score'></span>
      </p>
    </div>

    <div class='btn'>
      <a href='/update'><span class='fa-solid fa-cloud-arrow-up'></span> Update On server </a>
    </div>

    <div class='btn'>
      <a href='leaderboard'> <span class='fa-solid fa-graduation-cap'></span> Leaderboard </a>
    </div>
  </div>
</div>

<script>
  document.getElementById('score').innerHTML = localStorage.getItem('score');
  document.getElementById('dificulty').innerHTML = localStorage.getItem('dificulty');
  const element = document.getElementById('achievement');
  element.remove();
</script>