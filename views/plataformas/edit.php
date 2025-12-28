<?php
require_once __DIR__ . "/../../controllers/PlataformaController.php";
$controller = new PlataformaController();

$mensaje = null;

//Obtener plataforma por GET
if (!isset($_GET["id"])) {
    die("❌ Falta el parámetro id.");
}

$id = (int)$_GET["id"];
$platformObject = $controller->getPlataforma($id);

if (!$platformObject) {
    die("❌ No existe la plataforma con ese id.");
}

//Procesar POST
if (isset($_POST["platformId"]) && isset($_POST["platformName"])) {
    $pid = (int)$_POST["platformId"];
    $name = trim($_POST["platformName"]);

    if ($name === "") {
        $mensaje = "❌ El nombre no puede estar vacío.";
    } else {
        $ok = $controller->updatePlataforma($pid, $name);
        $mensaje = $ok ? "✅ Plataforma modificada correctamente." : "❌ Error al modificar la plataforma.";
        // refrescar objeto
        $platformObject = $controller->getPlataforma($id);
    }
}
?>

<h1>Editar plataforma</h1>

<form method="post" action="">
    <input type="hidden" name="platformId" value="<?= $platformObject->getId() ?>">
    <label>Nombre:</label>
    <input type="text" name="platformName" value="<?= $platformObject->getNombre() ?>" required>
    <button type="submit">Guardar</button>
</form>

<?php if ($mensaje) echo "<p>$mensaje</p>"; ?>

<p><a href="list.php">Volver</a></p>
