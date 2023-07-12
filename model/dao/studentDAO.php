<?php

use Kanatraining\Env;
use Kanatraining\Student;
use Vtiful\Kernel\Format;

class StudentDAO extends Env
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
        $this->table    = "student";                           // The table to attack

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
            return false;
        } catch (PDOException $e) {
            var_dump($e);
        }
    }

    public function fetch(int $id)
    {
        try {
            $statement = $this->connection->prepare("SELECT * FROM {$this->table} where {$this->table}_ID = ?");
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

            return new Student(
                $data['student_ID'],
                $data['student_Name'],
                $data['student_Avatar'],
                $data['student_RegisterDate'],
                $data['student_GlobalXp'],
                $data['student_StreakDays'],
                $data['student_HighScore'],
                $data['student_LastScore']
            );
        }
        return false;
    }

    public function store(object $data)
    {
        if (empty($data)) {
            return false;
        }

        if ($data) {
            $date = new DateTime();
            $date = $date->Format("Y-m-d H:i:s");

            try {
                $statement = $this->connection->prepare("INSERT INTO {$this->table} ({$this->table}_Name, {$this->table}_Avatar, {$this->table}_RegisterDate) VALUES (?, ?, ?)");
                $statement->execute([
                    $data->_Name,
                    $data->_Avatar,
                    $date
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
                    {$this->table}_Name = ?, {$this->table}_Avatar = ?, {$this->table}_GlobalXp = ?, {$this->table}_StreakDays = ?, {$this->table}_HighScore = ?, {$this->table}_LastScore = ? WHERE {$this->table}_ID = ?");

                $statement->execute([
                    $data->_Name,
                    $data->_Avatar,
                    $data->_GlobalXp,
                    $data->_StreakDays,
                    $data->_HighScore,
                    $data->_LastScore,
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
