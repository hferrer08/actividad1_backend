<?php

class Serie
{
    private $id;
    private $titulo;
    private $sinopsis;
    private $anio;
    private $temporadas;

    public function __construct($id = null, $titulo = null, $sinopsis = null, $anio = null, $temporadas = null)
    {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->sinopsis = $sinopsis;
        $this->anio = $anio;
        $this->temporadas = $temporadas;
    }

    public function getId() { return $this->id; }
    public function getTitulo() { return $this->titulo; }
    public function getSinopsis() { return $this->sinopsis; }
    public function getAnio() { return $this->anio; }
    public function getTemporadas() { return $this->temporadas; }

    public function setId($id) { $this->id = $id; }
    public function setTitulo($titulo) { $this->titulo = $titulo; }
    public function setSinopsis($sinopsis) { $this->sinopsis = $sinopsis; }
    public function setAnio($anio) { $this->anio = $anio; }
    public function setTemporadas($temporadas) { $this->temporadas = $temporadas; }

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

    // Validaciones BBDD

    public function existsSerie(int $id): bool
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("SELECT 1 FROM series WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return (bool)$stmt->fetchColumn();
    }

    private function existsInTable(PDO $pdo, string $table, int $id): bool
    {
        $stmt = $pdo->prepare("SELECT 1 FROM {$table} WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return (bool)$stmt->fetchColumn();
    }

    private function assertAllExist(PDO $pdo, string $table, array $ids, string $label): void
    {
        foreach ($ids as $id) {
            if (!$this->existsInTable($pdo, $table, (int)$id)) {
                throw new Exception("❌ {$label}: no existe el id {$id}.");
            }
        }
    }

    private function assertDirectorExists(PDO $pdo, ?int $directorId): void
    {
        if ($directorId === null) return;
        if (!$this->existsInTable($pdo, "directores", $directorId)) {
            throw new Exception("❌ Director: no existe el id {$directorId}.");
        }
    }

    // CRUD 

    public function getAll()
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("SELECT id, titulo, sinopsis, anio, temporadas FROM series ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare("SELECT id, titulo, sinopsis, anio, temporadas, director_id FROM series WHERE id = ?");
        $stmt->execute([(int)$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getObjectById($id)
    {
        $row = $this->getById($id);
        if (!$row) return null;

        return new Serie(
            $row["id"],
            $row["titulo"],
            $row["sinopsis"],
            $row["anio"],
            $row["temporadas"]
        );
    }

    public function delete($id)
    {
        $id = (int)$id;

        if (!$this->existsSerie($id)) {
            throw new Exception("❌ No existe la serie con id {$id}.");
        }

        try {
            $pdo = $this->connect();
            $stmt = $pdo->prepare("DELETE FROM series WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            if ((int)($e->errorInfo[1] ?? 0) === 1451) {
                throw new Exception("❌ No se puede borrar esta serie porque está asociada a registros relacionados.");
            }
            throw $e;
        }
    }

    // -------------------------
    // RELACIONES: IDs seleccionados
    // -------------------------
    public function getPlataformaIdsBySerie(int $serieId): array
    {
        return $this->fetchIds("SELECT plataforma_id AS id FROM serie_plataforma WHERE serie_id = ?", $serieId);
    }

    public function getActorIdsBySerie(int $serieId): array
    {
        return $this->fetchIds("SELECT actor_id AS id FROM serie_actor WHERE serie_id = ?", $serieId);
    }

    public function getAudioIdiomaIdsBySerie(int $serieId): array
    {
        return $this->fetchIds("SELECT idioma_id AS id FROM serie_idioma_audio WHERE serie_id = ?", $serieId);
    }

    public function getSubIdiomaIdsBySerie(int $serieId): array
    {
        return $this->fetchIds("SELECT idioma_id AS id FROM serie_idioma_sub WHERE serie_id = ?", $serieId);
    }

    private function fetchIds(string $sql, int $serieId): array
    {
        $pdo = $this->connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([(int)$serieId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($r) => (int)$r["id"], $rows);
    }


    // CREATE/UPDATE completo (SERIE + relaciones intermedias)

    public function createFull(array $data): int
    {
        $titulo = trim($data["titulo"] ?? "");
        if ($titulo === "") {
            throw new Exception("❌ El título es obligatorio.");
        }

        $sinopsis = trim($data["sinopsis"] ?? "");
        $sinopsis = ($sinopsis === "") ? null : $sinopsis;

        $anio = ($data["anio"] ?? "") !== "" ? (int)$data["anio"] : null;
        $temporadas = ($data["temporadas"] ?? "") !== "" ? (int)$data["temporadas"] : null;
        $directorId = ($data["director_id"] ?? "") !== "" ? (int)$data["director_id"] : null;

        $plataformas = $this->sanitizeIdArray($data["plataformas"] ?? []);
        $actores     = $this->sanitizeIdArray($data["actores"] ?? []);
        $audios      = $this->sanitizeIdArray($data["idiomas_audio"] ?? []);
        $subs        = $this->sanitizeIdArray($data["idiomas_sub"] ?? []);

        $pdo = $this->connect();
        $pdo->beginTransaction();

        try {
            // Validaciones BBDD: existencia de FKs (antes de insertar)
            $this->assertDirectorExists($pdo, $directorId);
            $this->assertAllExist($pdo, "plataformas", $plataformas, "Plataforma");
            $this->assertAllExist($pdo, "actores", $actores, "Actor");
            $this->assertAllExist($pdo, "idiomas", $audios, "Idioma audio");
            $this->assertAllExist($pdo, "idiomas", $subs, "Idioma subtítulos");

            // Insert serie
            $stmt = $pdo->prepare("INSERT INTO series (titulo, sinopsis, anio, temporadas, director_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$titulo, $sinopsis, $anio, $temporadas, $directorId]);

            $serieId = (int)$pdo->lastInsertId();

            // Insert relaciones
            $this->insertSeriePlataformas($pdo, $serieId, $plataformas);
            $this->insertSerieActores($pdo, $serieId, $actores);
            $this->insertSerieIdiomasAudio($pdo, $serieId, $audios);
            $this->insertSerieIdiomasSub($pdo, $serieId, $subs);

            $pdo->commit();
            return $serieId;

        } catch (Throwable $e) {
            $pdo->rollBack();

            // Mensaje por FKS
            if ($e instanceof PDOException && (int)($e->errorInfo[1] ?? 0) === 1452) {
                throw new Exception("❌ Hay referencias inválidas (director/plataformas/actores/idiomas).");
            }

            throw $e;
        }
    }

    public function updateFull(int $serieId, array $data): bool
    {
        if (!$this->existsSerie((int)$serieId)) {
            throw new Exception("❌ No existe la serie con id {$serieId}.");
        }

        $titulo = trim($data["titulo"] ?? "");
        if ($titulo === "") {
            throw new Exception("❌ El título es obligatorio.");
        }

        $sinopsis = trim($data["sinopsis"] ?? "");
        $sinopsis = ($sinopsis === "") ? null : $sinopsis;

        $anio = ($data["anio"] ?? "") !== "" ? (int)$data["anio"] : null;
        $temporadas = ($data["temporadas"] ?? "") !== "" ? (int)$data["temporadas"] : null;
        $directorId = ($data["director_id"] ?? "") !== "" ? (int)$data["director_id"] : null;

        $plataformas = $this->sanitizeIdArray($data["plataformas"] ?? []);
        $actores     = $this->sanitizeIdArray($data["actores"] ?? []);
        $audios      = $this->sanitizeIdArray($data["idiomas_audio"] ?? []);
        $subs        = $this->sanitizeIdArray($data["idiomas_sub"] ?? []);

        $pdo = $this->connect();
        $pdo->beginTransaction();

        try {
            // Validaciones BBDD: existencia de FKs
            $this->assertDirectorExists($pdo, $directorId);
            $this->assertAllExist($pdo, "plataformas", $plataformas, "Plataforma");
            $this->assertAllExist($pdo, "actores", $actores, "Actor");
            $this->assertAllExist($pdo, "idiomas", $audios, "Idioma audio");
            $this->assertAllExist($pdo, "idiomas", $subs, "Idioma subtítulos");

            // Update serie
            $stmt = $pdo->prepare("UPDATE series SET titulo = ?, sinopsis = ?, anio = ?, temporadas = ?, director_id = ? WHERE id = ?");
            $ok = $stmt->execute([$titulo, $sinopsis, $anio, $temporadas, $directorId, (int)$serieId]);

            // Limpiar relaciones
            $this->clearBridge($pdo, "DELETE FROM serie_plataforma WHERE serie_id = ?", $serieId);
            $this->clearBridge($pdo, "DELETE FROM serie_actor WHERE serie_id = ?", $serieId);
            $this->clearBridge($pdo, "DELETE FROM serie_idioma_audio WHERE serie_id = ?", $serieId);
            $this->clearBridge($pdo, "DELETE FROM serie_idioma_sub WHERE serie_id = ?", $serieId);

            // Insert relaciones
            $this->insertSeriePlataformas($pdo, $serieId, $plataformas);
            $this->insertSerieActores($pdo, $serieId, $actores);
            $this->insertSerieIdiomasAudio($pdo, $serieId, $audios);
            $this->insertSerieIdiomasSub($pdo, $serieId, $subs);

            $pdo->commit();
            return $ok;

        } catch (Throwable $e) {
            $pdo->rollBack();

            if ($e instanceof PDOException && (int)($e->errorInfo[1] ?? 0) === 1452) {
                throw new Exception("❌ Hay referencias inválidas (director/plataformas/actores/idiomas).");
            }

            throw $e;
        }
    }


    // HELPERS RELACIONES

    private function sanitizeIdArray($arr): array
    {
        if (!is_array($arr)) return [];

        $arr = array_map("intval", $arr);
        $arr = array_filter($arr, fn($x) => $x > 0);
        $arr = array_values(array_unique($arr));
        return $arr;
    }

    private function clearBridge(PDO $pdo, string $sql, int $serieId): void
    {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([(int)$serieId]);
    }

    private function insertSeriePlataformas(PDO $pdo, int $serieId, array $plataformas): void
    {
        if (count($plataformas) === 0) return;

        $stmt = $pdo->prepare("INSERT INTO serie_plataforma (serie_id, plataforma_id) VALUES (?, ?)");
        foreach ($plataformas as $pid) {
            $stmt->execute([(int)$serieId, (int)$pid]);
        }
    }

    private function insertSerieActores(PDO $pdo, int $serieId, array $actores): void
    {
        if (count($actores) === 0) return;

        $stmt = $pdo->prepare("INSERT INTO serie_actor (serie_id, actor_id) VALUES (?, ?)");
        foreach ($actores as $aid) {
            $stmt->execute([(int)$serieId, (int)$aid]);
        }
    }

    private function insertSerieIdiomasAudio(PDO $pdo, int $serieId, array $idiomas): void
    {
        if (count($idiomas) === 0) return;

        $stmt = $pdo->prepare("INSERT INTO serie_idioma_audio (serie_id, idioma_id) VALUES (?, ?)");
        foreach ($idiomas as $iid) {
            $stmt->execute([(int)$serieId, (int)$iid]);
        }
    }

    private function insertSerieIdiomasSub(PDO $pdo, int $serieId, array $idiomas): void
    {
        if (count($idiomas) === 0) return;

        $stmt = $pdo->prepare("INSERT INTO serie_idioma_sub (serie_id, idioma_id) VALUES (?, ?)");
        foreach ($idiomas as $iid) {
            $stmt->execute([(int)$serieId, (int)$iid]);
        }
    }
}
