<?php
require_once __DIR__ . '/../../config/database.php';

class Plataforma
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = (new Database())->connect();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM plataformas ORDER BY id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}