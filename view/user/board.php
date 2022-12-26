<?php

require_once('../model/class/User.php');
require_once('../model/dao/userDAO.php');

echo "
 </ul>
 </div>
  <div class='center'>
";

$userDAO = new UserDAO;
$users = $userDAO->fetchAll();

foreach ($users as $key => $value) {
    echo "
    <div class='board'>
        <div class='outside'>
            <div class='color'>
            </div>
            
            <div class='imgback'>
                <img src='$value->_avatar'>
            </div>

            <div class='info'>
                <div>
                <h2>User Name </h2>
                <p>$value->_name </p>
                </div>
                
                <div>
                <h2>High Score </h2>
                <p> $value->_best </p>
                </div>
            </div>
        </div>
    </div>";
};
