<?php

class Database
{
    private $host = "localhost";
    private $db   = "actividad1_backend";
    private $user = "root";
    private $pass = "";
    private $charset = "utf8mb4";

    public function connect()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
            $pdo = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            return $pdo;

        } catch (PDOException $e) {
            die("Error conexión BD: " . $e->getMessage());
        }
    }
}