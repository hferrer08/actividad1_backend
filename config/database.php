<?php

class Database
{
    private static $instance = null;
    private $conn;

    private $host = "localhost";
    private $db   = "actividad1_backend";
    private $user = "root";
    private $pass = "";
    private $charset = "utf8mb4";

    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $this->conn = new PDO($dsn, $this->user, $this->pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    public static function connect()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}