<?php

use Kanatraining\User;
use Kanatraining\Env;

class UserDAO extends Env {
    //DON'T TOUCH IT, LITTLE PRICK
    private $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    private $username;
    private $password;
    private $host;
    private $dbname;
    private $table;
    private $connection;

    public function __construct() {
        // Change the values according to your hosting.
        $this->username = parent::env('DB_USERNAME', 'root'); // The login to connect to the DB
        $this->password = parent::env('DB_PASSWORD', ''); // The password to connect to the DB
        $this->host     = parent::env('DB_HOST', 'localhost'); // The name of the server where my DB is located
        $this->dbname   = parent::env('DB_NAME'); // The name of the DB you want to attack.
        $this->table    = "kanauser"; // The table to attack

        $this->connection = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8", $this->username, $this->password, $this->options);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }

    public function fetchAll() {
        try {
            $statement = $this->connection->prepare("SELECT * FROM {$this->table}");
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            $Users   = array();

            foreach ($results as $result) {
                array_push($Users, $this->create($result));
            }

            return $Users;
        } catch (PDOException $e) {
            var_dump($e);
        }
    }

    public function fetch($id) {
        try {
            $statement = $this->connection->prepare("SELECT * FROM {$this->table} WHERE User_ID = ?");
            $statement->execute([$id]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            // echo 'result Fetch';
            // var_dump($result);

            return $this->create($result);
        } catch (PDOException $e) {
            var_dump($e);
        }
    }

    public function create($data) {
        if (!$data) {
            return false;
        }

        if (is_array($data)) {
            return new User(
                $data['User_ID'],
                $data['User_Name'],
                $data['user_viewer'],
                $data['user_type'],
                $data['user_mail'],
                $data['User_Avatar'],
                $data['User_ScoreHighest']
            );

        } else {
            return new User(
                $data->_id,
                $data->_name,
                $data->_viewer,
                $data->_type,
                $data->_email,
                $data->_avatar,
                $data->_best
            );
        }

    }

    public function store($data) {

        if (empty($data)) {
            return false;
        }

        $user = $data;

        if ($user) {
            try {
                $statement = $this->connection->prepare("INSERT INTO {$this->table} 
                (user_id, user_name, user_viewer, user_type, user_mail, user_avatar, User_ScoreHighest, User_ScoreLower) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $statement->execute([
                    $user->_id,
                    $user->_name,
                    $user->_viewer,
                    $user->_type,
                    $user->_email,
                    $user->_avatar,
                    $user->_best,
                    $user->_last,
                ]);

                $user->id = $this->connection->lastInsertId();
            } catch (PDOException $e) {
                echo $e;
            }
        }

        header('location: /user');
    }

    public function update($id, $data) {
        if (empty($data) || empty($id)) {
            return false;
        }

        if ($data['id'] === false) {
            echo 'false';
            header('location: /user');
            return false;
        }

        $user = $this->create([
            "user_id"           => $id,
            'user_login'        => $data['login'],
            'user_displayName'  => $data['displayName'],
            'user_email'        => $data['email'],
            'user_viewerCount'  => $data['viewerCount'],
            'user_profileImage' => $data['profileImage'],
            'User_ScoreHighest' => $data['best'],
            'User_ScoreLower'   => $data['last'],
        ]);

        if ($user) {
            try {
                $statement = $this->connection->prepare("UPDATE {$this->table} SET
                user_login          = ?,
                user_displayName    = ?,
                user_email          = ?,
                user_viewerCount    = ?,
                user_profileImage   = ?,
                User_ScoreHighest   = ?,
                User_ScoreLower     = ?, WHERE
                admin_id            = ?");

                $statement->execute([
                    $user->_login,
                    $user->_displayName,
                    $user->_email,
                    $user->_viewCount,
                    $user->_profileImage,
                    $user->_best,
                    $user->_last,
                    $user->_id
                ]);

            } catch (PDOException $e) {
                var_dump($e->getMessage());
                return false;
            }
        }

        header('location: /user ');
    }

    public function scoreUpdate($id) {
        if (empty($id)) {
            return false;
        }
        $olduser = $this->fetch($id);

        $lastScore = explode(' ', $_COOKIE['score'], 2);
        $lastScore = $lastScore[0];
        $lsScore   = explode('/', $lastScore);

        $Lscore    = $lsScore[0];
        $LscoreMax = $lsScore[1];

        $Lscore    = intval($Lscore);
        $LscoreMax = intval($LscoreMax);

        $dbScore   = explode('/', $olduser->_best);
        $dScore    = $dbScore[0];
        $dScoreMax = $dbScore[1];

        $dbScore    = intval($dScore);
        $dbScoreMax = intval($dScoreMax);

        if ($dbScoreMax === 0) {
            $dbScoreMax = 1;
        }

        if ($LscoreMax === 0) {
            $LscoreMax = 1;
        }

        $NewBest = ($Lscore / $LscoreMax) * 100;
        $OldBest = ($dbScore / $dbScoreMax) * 100;

        if ($LscoreMax < $dbScoreMax) {
            $best = $olduser->_best;
        } else {
            if ($NewBest > $OldBest) {
                $best = $lastScore;
            }else{
                $best = $olduser->_best;
            }
        }

        $user = $this->create([
            'User_ID'           => $id,
            'User_Name'         => $olduser->_name,
            'user_viewer'       => $olduser->_viewerCount,
            'user_type'         => $olduser->_type,
            'user_mail'         => $olduser->_email,
            'User_Avatar'       => $olduser->_avatar,
            'User_ScoreHighest' => $best
        ]);

        if ($user) {
            try {
                $statement = $this->connection->prepare("UPDATE {$this->table} SET User_ScoreHighest = ?, User_ScoreLower = ? WHERE User_id = ?");
                $statement->execute([
                    $user->_best,
                    $user->_last,
                    $user->_id
                ]);

            } catch (PDOException $e) {
                var_dump($e->getMessage());
                die();
            }
        }

        header('location: /user ');
    }
}