<?php

class Actor
{
    private $id;
    private $nombre;
    private $apellidos;
    private $fechaNacimiento;

    public function __construct($id = null, $nombre = null, $apellidos = null, $fechaNacimiento = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->fechaNacimiento = $fechaNacimiento;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getApellidos() { return $this->apellidos; }
    public function getFechaNacimiento() { return $this->fechaNacimiento; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellidos($apellidos) { $this->apellidos = $apellidos; }
    public function setFechaNacimiento($fecha) { $this->fechaNacimiento = $fecha; }

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
        $stmt = $pdo->prepare("SELECT id, nombre, apellidos, fecha_nacimiento FROM actores ORDER BY id");
        $stmt->execute();

        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = new Actor(
                $row["id"],
                $row["nombre"],
                $row["apellidos"],
                $row["fecha_nacimiento"]
            );
        }
        return $items;
    }

    public function getById($id)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("SELECT id, nombre, apellidos, fecha_nacimiento FROM actores WHERE id = ?");
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return new Actor($row["id"], $row["nombre"], $row["apellidos"], $row["fecha_nacimiento"]);
    }

    public function create($nombre, $apellidos, $fechaNacimiento)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("INSERT INTO actores (nombre, apellidos, fecha_nacimiento) VALUES (?, ?, ?)");
        return $stmt->execute([$nombre, $apellidos, $fechaNacimiento ?: null]);
    }

    public function update($id, $nombre, $apellidos, $fechaNacimiento)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("UPDATE actores SET nombre = ?, apellidos = ?, fecha_nacimiento = ? WHERE id = ?");
        return $stmt->execute([$nombre, $apellidos, $fechaNacimiento ?: null, $id]);
    }

    public function delete($id)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("DELETE FROM actores WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
