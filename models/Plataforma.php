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

 
    // Validación BBDD: existencia

    public function exists($id): bool
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("SELECT 1 FROM plataformas WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return (bool)$stmt->fetchColumn();
    }

    // Validación BBDD: duplicados por nombre
    public function getByNombre($nombre)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("SELECT id, nombre FROM plataformas WHERE nombre = ? LIMIT 1");
        $stmt->execute([$nombre]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return new Plataforma($row["id"], $row["nombre"]);
    }

    // CRUD

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

    public function getById($id)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("SELECT id, nombre FROM plataformas WHERE id = ?");
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        return new Plataforma($row["id"], $row["nombre"]);
    }

    public function create($nombre)
    {
       
        if ($this->getByNombre($nombre)) {
            throw new Exception("❌ Ya existe una plataforma con el nombre '{$nombre}'.");
        }

        try {
            $pdo = $this->connect();
            $stmt = $pdo->prepare("INSERT INTO plataformas (nombre) VALUES (?)");
            $stmt->execute([$nombre]);
            return true;
        } catch (PDOException $e) {
            // 1062 = Duplicate entry (UNIQUE)
            if ((int)($e->errorInfo[1] ?? 0) === 1062) {
                throw new Exception("❌ Ya existe una plataforma con ese nombre.");
            }
            throw $e;
        }
    }

    public function update($id, $nombre)
    {
        if (!$this->exists($id)) {
            throw new Exception("❌ No existe la plataforma con id {$id}.");
        }

        // Duplicado (excluyendo el mismo id)
        $byNombre = $this->getByNombre($nombre);
        if ($byNombre && (int)$byNombre->getId() !== (int)$id) {
            throw new Exception("❌ Ya existe otra plataforma con el nombre '{$nombre}'.");
        }

        try {
            $pdo = $this->connect();
            $stmt = $pdo->prepare("UPDATE plataformas SET nombre = ? WHERE id = ?");
            $stmt->execute([$nombre, $id]);
            return true;
        } catch (PDOException $e) {
            if ((int)($e->errorInfo[1] ?? 0) === 1062) {
                throw new Exception("❌ Ya existe una plataforma con ese nombre.");
            }
            throw $e;
        }
    }

    public function delete($id)
    {
        if (!$this->exists($id)) {
            throw new Exception("❌ No existe la plataforma con id {$id}.");
        }

        try {
            $pdo = $this->connect();
            $stmt = $pdo->prepare("DELETE FROM plataformas WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            // 1451 = FK restrict (plataforma asociada en serie_plataforma)
            if ((int)($e->errorInfo[1] ?? 0) === 1451) {
                throw new Exception("❌ No se puede borrar esta plataforma porque está asociada a una o más series.");
            }
            throw $e;
        }
    }
}
