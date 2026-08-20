<?php

namespace Kanatraining;

class Database
{
    private static ?\PDO $instance = null;

    public static function get(env $env): \PDO
    {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . $env->env('DB_HOST')
                . ';port=' . $env->env('DB_PORT')
                . ';dbname=' . $env->env('DB_NAME')
                . ';charset=utf8mb4';

            self::$instance = new \PDO($dsn, $env->env('DB_USERNAME'), $env->env('DB_PASSWORD'), [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]);
        }

        return self::$instance;
    }
}
