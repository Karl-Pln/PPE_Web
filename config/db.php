<?php
class DBConnexion {
    private static $instance = null;

    private function __construct() {}

    public static function getConnexion(): PDO {
        if (self::$instance === null) {
            $host   = 'localhost';
            $dbname = 'stadiumcompany';
            $user   = 'root';
            $password   = 'password';
            $dsn    = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
            self::$instance = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }
        return self::$instance;
    }
}