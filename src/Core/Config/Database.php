<?php

namespace Core\Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function connection()
    {
        if (!self::$connection) {
            $host = $_ENV['DB_HOST'];
            $db   = $_ENV['DB_DATABASE'];
            $user = $_ENV['DB_USERNAME'];
            $pass = $_ENV['DB_PASSWORD'];
            $port = $_ENV['DB_PORT'] ?? 3306;

            $dns = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
            try {
                 self::$connection = new PDO($dns, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
