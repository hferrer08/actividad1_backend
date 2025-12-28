<?php
require_once __DIR__ . "/../../controllers/PlataformaController.php";
$controller = new PlataformaController();

if (!isset($_POST["id"])) {
    die("❌ Falta el id por POST.");
}

$id = (int)$_POST["id"];
$ok = $controller->deletePlataforma($id);

echo $ok ? "✅ Borrado correcto." : "❌ No se pudo borrar.";

echo '<p><a href="list.php">Volver al listado</a></p>';
