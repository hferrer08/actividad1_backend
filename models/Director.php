<?php

class Director
{
    private $id;
    private $nombre;
    private $apellidos;
    private $fechaNacimiento; 
    private $nacionalidad; 

    public function __construct($id = null, $nombre = null, $apellidos = null, $fechaNacimiento = null, $nacionalidad = null)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->fechaNacimiento = $fechaNacimiento;
        $this->nacionalidad = $nacionalidad;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getApellidos() { return $this->apellidos; }
    public function getFechaNacimiento() { return $this->fechaNacimiento; }
    public function getNacionalidad() { return $this->nacionalidad; }


    // Setters
    public function setId($id) { $this->id = $id; }
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setApellidos($apellidos) { $this->apellidos = $apellidos; }
    public function setFechaNacimiento($fecha) { $this->fechaNacimiento = $fecha; }
    public function setNacionalidad($nacionalidad) { $this->nacionalidad = $$nacionalidad; }


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
        $stmt = $pdo->prepare("SELECT id, nombre, apellidos, fecha_nacimiento, nacionalidad FROM directores ORDER BY id");
        $stmt->execute();

        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = new Director(
                $row["id"],
                $row["nombre"],
                $row["apellidos"],
                $row["fecha_nacimiento"],
                $row["nacionalidad"],
            );
        }
        return $items;
    }

    public function getById($id)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("SELECT id, nombre, apellidos, fecha_nacimiento, nacionalidad FROM directores WHERE id = ?");
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return new Director($row["id"], $row["nombre"], $row["apellidos"], $row["fecha_nacimiento"], $row["nacionalidad"]);
    }

    public function create($nombre, $apellidos, $fechaNacimiento, $nacionalidad)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("INSERT INTO directores (nombre, apellidos, fecha_nacimiento, nacionalidad) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$nombre, $apellidos, $fechaNacimiento ?: null, $nacionalidad ?: null]);
    }

    public function update($id, $nombre, $apellidos, $fechaNacimiento, $nacionalidad)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("UPDATE directores SET nombre = ?, apellidos = ?, fecha_nacimiento = ?, nacionalidad = ? WHERE id = ?");
        return $stmt->execute([$nombre, $apellidos, $fechaNacimiento ?: null, $nacionalidad ?: null, $id]);
    }

    public function delete($id)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("DELETE FROM directores WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
