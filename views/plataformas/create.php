<?php
require_once __DIR__ . "/../../controllers/PlataformaController.php";
$controller = new PlataformaController();

$mensaje = null;

if (isset($_POST["platformName"])) {
    $name = trim($_POST["platformName"]);

    // Validación
    if ($name === "") {
        $mensaje = "❌ El nombre no puede estar vacío.";
    } else {
        $ok = $controller->createPlataforma($name);
        $mensaje = $ok ? "✅ Plataforma creada correctamente." : "❌ Error al crear la plataforma.";
    }
}
?>

<h1>Crear plataforma</h1>

<form method="post" action="">
    <label>Nombre:</label>
    <input type="text" name="platformName" required>
    <button type="submit">Crear</button>
</form>

<?php if ($mensaje) echo "<p>$mensaje</p>"; ?>

<p><a href="list.php">Volver</a></p>
