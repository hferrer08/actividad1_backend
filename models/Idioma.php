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

    public function exists($id): bool
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("SELECT 1 FROM idiomas WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return (bool)$stmt->fetchColumn();
    }

    public function getByCodigo($codigo)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("SELECT id, nombre, codigo FROM idiomas WHERE codigo = ? LIMIT 1");
        $stmt->execute([$codigo]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        return new Idioma($row["id"], $row["nombre"], $row["codigo"]);
    }

    public function getByNombre($nombre)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("SELECT id, nombre, codigo FROM idiomas WHERE nombre = ? LIMIT 1");
        $stmt->execute([$nombre]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;
        return new Idioma($row["id"], $row["nombre"], $row["codigo"]);
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
        // Validación BBDD: duplicados 
        if ($this->getByCodigo($codigo)) {
            throw new Exception("❌ Ya existe un idioma con el código '{$codigo}'.");
        }
        if ($this->getByNombre($nombre)) {
            throw new Exception("❌ Ya existe un idioma con el nombre '{$nombre}'.");
        }

        try {
            $pdo = $this->connect();
            $stmt = $pdo->prepare("INSERT INTO idiomas (nombre, codigo) VALUES (?, ?)");
            $stmt->execute([$nombre, $codigo]);
            return true;
        } catch (PDOException $e) {
            // 1062 = Duplicate entry (UNIQUE)
            if ((int)($e->errorInfo[1] ?? 0) === 1062) {
                throw new Exception("❌ Ya existe un idioma con ese nombre o código.");
            }
            throw $e;
        }
    }

    public function update($id, $nombre, $codigo)
    {
        // Validación BBDD: existencia
        if (!$this->exists($id)) {
            throw new Exception("❌ No existe el idioma con id {$id}.");
        }

        // Validación BBDD: duplicados (excluyendo el mismo id)
        $byCodigo = $this->getByCodigo($codigo);
        if ($byCodigo && (int)$byCodigo->getId() !== (int)$id) {
            throw new Exception("❌ Ya existe otro idioma con el código '{$codigo}'.");
        }

        $byNombre = $this->getByNombre($nombre);
        if ($byNombre && (int)$byNombre->getId() !== (int)$id) {
            throw new Exception("❌ Ya existe otro idioma con el nombre '{$nombre}'.");
        }

        try {
            $pdo = $this->connect();
            $stmt = $pdo->prepare("UPDATE idiomas SET nombre = ?, codigo = ? WHERE id = ?");
            $stmt->execute([$nombre, $codigo, $id]);
            return true;
        } catch (PDOException $e) {
            if ((int)($e->errorInfo[1] ?? 0) === 1062) {
                throw new Exception("❌ Ya existe un idioma con ese nombre o código.");
            }
            throw $e;
        }
    }

    public function delete($id)
{
    if (!$this->exists($id)) {
        throw new Exception("❌ No existe el idioma con id {$id}.");
    }

    try {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("DELETE FROM idiomas WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        // 1451 = FK restrict
        if ((int)($e->errorInfo[1] ?? 0) === 1451) {
            throw new Exception("❌ No se puede borrar este idioma porque está asociado a una o más series.");
        }
        throw $e;
    }
}
}
