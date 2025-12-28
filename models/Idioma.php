<?php

class Idioma
{
    private $id;
    private $nombre;
    private $codigo;

    public function __construct($id = null, $nombre = null, $codigo = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->codigo = $codigo;
    }

    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getCodigo() { return $this->codigo; }

    public function setId($id) { $this->id = $id; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setCodigo($codigo) { $this->codigo = $codigo; }

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

    public function getAll()
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("SELECT id, nombre, codigo FROM idiomas ORDER BY id");
        $stmt->execute();

        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = new Idioma($row["id"], $row["nombre"], $row["codigo"]);
        }
        return $items;
    }

    public function getById($id)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("SELECT id, nombre, codigo FROM idiomas WHERE id = ?");
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return new Idioma($row["id"], $row["nombre"], $row["codigo"]);
    }

    public function create($nombre, $codigo)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("INSERT INTO idiomas (nombre, codigo) VALUES (?, ?)");
        return $stmt->execute([$nombre, $codigo]);
    }

    public function update($id, $nombre, $codigo)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("UPDATE idiomas SET nombre = ?, codigo = ? WHERE id = ?");
        return $stmt->execute([$nombre, $codigo, $id]);
    }

    public function delete($id)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("DELETE FROM idiomas WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
