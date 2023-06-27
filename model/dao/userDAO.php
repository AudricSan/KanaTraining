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
            $statement = $this->connection->prepare("SELECT * FROM {$this->table} WHERE KanaUser_ID = ?");
            $statement->execute([$id]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);

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
                $data['KanaUser_ID'],
                $data['KanaUser_Name'],
                $data['KanaUser_Avatar']
            );

        } else {
            return new User(
                $data->_id,
                $data->_displayName,
                $data->_avatar
            );
        }
    }

    public function store($data) {

        if (empty($data)) {
            return false;
        }

        if ($data) {
            try {
                $statement = $this->connection->prepare("INSERT INTO {$this->table} 
                (KanaUser_ID, KanaUser_Name, KanaUser_Avatar) 
                VALUES (?, ?, ?)");
                $statement->execute([
                    $data->_id,
                    $data->_displayName,
                    $data->_avatar
                ]);
            } catch (PDOException $e) {
                echo $e;
            }
        }

        header('location: /user');
    }

// public function update($id, $data) {}
}