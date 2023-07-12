<?php

use Kanatraining\Env;
use Kanatraining\Achievement;
use Kanatraining\Student;
use Kanatraining\studentAchievement;
use Vtiful\Kernel\Format;

class StudentAchievementDAO extends Env
{
    //DON'T TOUCH IT, LITTLE PRICK
    private $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    private $username;
    private $password;
    private $host;
    private $dbname;
    private $table;
    private $connection;

    public function __construct()
    {
        // Change the values according to your hosting.
        $this->username = parent::env('DB_USERNAME', 'root');   // The login to connect to the DB
        $this->password = parent::env('DB_PASSWORD', '');       // The password to connect to the DB
        $this->host     = parent::env('DB_HOST', 'localhost');  // The name of the server where my DB is located
        $this->dbname   = parent::env('DB_NAME');               // The name of the DB you want to attack.
        $this->table    = "student_achievements";               // The table to attack

        $this->connection = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8", $this->username, $this->password, $this->options);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function fetchAll()
    {
        try {
            $statement = $this->connection->prepare("SELECT * FROM {$this->table}");
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            $Users   = array();

            foreach ($results as $result) {
                Array_push($Users, $this->create($result));
            }

            if (!empty($Users)) {
                return $Users;
            }
        } catch (PDOException $e) {
            var_dump($e);
            return false;
        }
    }

    public function fetch(int $id)
    {
        try {
            $statement = $this->connection->prepare("SELECT * FROM {$this->table} where sa_ID = ?");
            $statement->execute([$id]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $result = $this->create($result);

            if (!empty($result)) {
                return $result;
            }
            return false;
        } catch (PDOException $e) {
            var_dump($e);
        }
    }

    public function create(array $data)
    {
        if (!$data) {
            return false;
        }

        if (is_array($data)) {
            return new studentAchievement(
                $data['sa_ID'],
                $data['student_ID'],
                $data['achievements_ID']
            );
        }
        return false;
    }

    public function store(object $data)
    {
        if (empty($data)) {
            return false;
        }

        var_dump($data);

        if ($data) {
            try {
                $statement = $this->connection->prepare("INSERT INTO {$this->table} (achievements_ID, student_ID) VALUES (?, ?)");
                $statement->execute([
                    $data->_AchievementID,
                    $data->_StudentID
                ]);

                return true;
            } catch (PDOException $e) {
                echo $e;
                return false;
            }
        }
    }

    public function update(object $data)
    {
        if (empty($data)) {
            return false;
        }

        if ($data) {
            try {
                $statement = $this->connection->prepare("UPDATE {$this->table} SET 
                    achievements_ID = ?, student_ID = ? WHERE sa_ID = ?");

                $statement->execute([
                    $data->_AchievementID,
                    $data->_StudentID,
                    $data->_ID
                ]);

                return true;
            } catch (PDOException $e) {
                echo $e;
                return false;
            }
        }
    }
}
