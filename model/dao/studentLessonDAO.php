<?php

use Kanatraining\Env;
use Kanatraining\Achievement;
use Kanatraining\Student;
use Kanatraining\studentLesson;
use Vtiful\Kernel\Format;

class StudentLessonDAO extends Env
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
        $this->dbname   = parent::env('DB_NAME', 'kanatraining'); // The name of the DB you want to attack.

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
            $statement = $this->connection->prepare("SELECT * FROM {$this->table} where sl_ID = ?");
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
            return new studentLesson(
                $data['sl_ID'],
                $data['student_ID'],
                $data['lesson_ID']
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
                $statement = $this->connection->prepare("INSERT INTO {$this->table} (student_ID, lesson_ID) VALUES (?, ?)");
                $statement->execute([
                    $data->_StudentID,
                    $data->_LessonID
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
                    lesson_ID = ?, student_ID = ? WHERE sl_ID = ?");

                $statement->execute([
                    $data->_LessonID,
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
