<?php

require_once('../model/class/User.php');
require_once('../model/dao/UserDAO.php');

echo "
 </ul>
 </div>
  <div class='center'>
";

$_SESSION['user'] = 133565026;
$userDAO = new UserDAO;
$userInfo = $userDAO->fetch($_SESSION['user']);

?>

<div class='outside'>
  <div class='color'>
  </div>

  <div class='imgback'>
    <img src=' <?= $userInfo->_avatar ?>'>
  </div>

  <div class='info'>

    <div>
    <h2> User Name </h2>
    <p> <?= $userInfo->_name ?>  </p>
    </div>
    
    <div>
    <h2> High Score </h2>
    <p> <?= $userInfo->_best ?> </p>
    </div>

    <div>
      <h2> Last Score </h2>
      <p id='score'></p>
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
</script>