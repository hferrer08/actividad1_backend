<?php
$host = "localhost";
$db   = "actividad1_backend";
$user = "root";
$pass = ""; // en XAMPP por defecto es vacío
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    echo "<h2>✅ Conexión OK</h2>";

    $stmt = $pdo->query("SELECT id, nombre, descripcion, url, activo, created_at FROM plataformas ORDER BY id");
    $rows = $stmt->fetchAll();

    echo "<h3>Plataformas:</h3>";
    echo "<pre>";
    print_r($rows);
    echo "</pre>";

} catch (PDOException $e) {
    echo "<h2>❌ Error de conexión</h2>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}