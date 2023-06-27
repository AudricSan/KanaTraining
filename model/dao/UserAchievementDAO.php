<?php

use Kanatraining\env;
use Kanatraining\UserAchievement;

class UserAchievementDAO extends Env {
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
        $this->table    = "KanaUserAchievement"; // The table to attack

        $this->connection = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8", $this->username, $this->password, $this->options);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }

    public function fetchAll() {
        try {
            $statement = $this->connection->prepare("SELECT * FROM {$this->table}");
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            $res     = array();

            foreach ($results as $result) {
                array_push($res, $this->create($result));
            }

            return $res;
        } catch (PDOException $e) {
            var_dump($e);
        }
    }

    public function fetch($id) {
        try {
            $statement = $this->connection->prepare("SELECT * FROM {$this->table} WHERE KanaUserAchievement_User = ?");
            $statement->execute([$id]);
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            $res     = array();

            foreach ($results as $result) {
                array_push($res, $this->create($result));
            }

            return $res;
        } catch (PDOException $e) {
            var_dump($e);
        }
    }

    public function create($data) {
        if (!$data) {
            return false;
        }

        if (is_array($data)) {
            return new UserAchievement(
                $data['KanaUserAchievement_ID'],
                $data['KanaUserAchievement_User'],
                $data['KanaUserAchievement_Achievement']
            );

        } else {
            return new UserAchievement(
                $data->_id,
                $data->_User,
                $data->_Achievement
            );
        }
    }

    // public function store($data) {

    //     if (empty($data)) {
    //         return false;
    //     }

    //     $user = $data;

    //     if ($user) {
    //         try {
    //             $statement = $this->connection->prepare("INSERT INTO {$this->table} 
    //             (user_id, user_name) 
    //             VALUES (?, ?)");
    //             $statement->execute([
    //                 $user->_id,
    //                 $user->_displayName
    //             ]);

    //             $user->id = $this->connection->lastInsertId();
    //         } catch (PDOException $e) {
    //             echo $e;
    //         }
    //     }

    //     header('location: /user');
    // }

    // public function update($id, $data) {
    //     if (empty($data) || empty($id)) {
    //         return false;
    //     }

    //     if ($data['id'] === false) {
    //         echo 'false';
    //         header('location: /user');
    //         return false;
    //     }

    //     $UserAchievement = $this->create([
    //         'UserAchievement_id'          => $data['id'],
    //         'UserAchievement_User'        => $data['UserAchievement_User'],
    //         'UserAchievement_Achievement' => $data['UserAchievement_Achievement']
    //     ]);

    //     if ($UserAchievement) {
    //         try {
    //             $statement = $this->connection->prepare("UPDATE {$this->table} SET
    //             UserAchievement_User = ?, UserAchievement_Achievement = ? WHERE UserAchievement_id = ?");

    //             $statement->execute([
    //                 $UserAchievement->UserAchievement_User,
    //                 $UserAchievement->UserAchievement_Achievement,
    //                 $UserAchievement->_id
    //             ]);

    //         } catch (PDOException $e) {
    //             var_dump($e->getMessage());
    //             return false;
    //         }
    //     }

    //     header('location: /user ');
    // }
}