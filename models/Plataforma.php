<?php

class Plataforma
{
    private $id;
    private $nombre;

    public function __construct($id = null, $nombre = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    // Getters/Setters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function setId($id) { $this->id = $id; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    // Conexión
    private function connect()
    {
        $host = "localhost";
        $db   = "actividad1_backend";
        $user = "root";
        $pass = "";
        $charset = "utf8mb4";

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    // getAll = devuelve array de objetos Plataforma (como el ejemplo)
    public function getAll()
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("SELECT id, nombre FROM plataformas ORDER BY id");
        $stmt->execute();

        $plataformas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $plataformas[] = new Plataforma($row["id"], $row["nombre"]);
        }
        return $plataformas;
    }

    // getById = devuelve objeto Plataforma o null (para edit)
    public function getById($id)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("SELECT id, nombre FROM plataformas WHERE id = ?");
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return new Plataforma($row["id"], $row["nombre"]);
    }

    // create = true/false
    public function create($nombre)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("INSERT INTO plataformas (nombre) VALUES (?)");
        return $stmt->execute([$nombre]);
    }

    // update = true/false
    public function update($id, $nombre)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("UPDATE plataformas SET nombre = ? WHERE id = ?");
        return $stmt->execute([$nombre, $id]);
    }

    // delete = true/false
    public function delete($id)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("DELETE FROM plataformas WHERE id = ?");
        return $stmt->execute([$id]);
    }
}