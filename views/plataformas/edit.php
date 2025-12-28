<?php
require_once __DIR__ . "/../../controllers/PlataformaController.php";
$controller = new PlataformaController();

$mensaje = null;
$tipo = "success";

//Validar que venga el id por GET
if (!isset($_GET["id"])) {
    die("❌ Falta el parámetro id.");
}

$id = (int)$_GET["id"];
$platformObject = $controller->getPlataforma($id);

if (!$platformObject) {
    die("❌ No existe la plataforma con ese id.");
}

//Procesar POST (la misma vista, estilo actividad)
if (isset($_POST["platformId"]) && isset($_POST["platformName"])) {
    $pid = (int)$_POST["platformId"];
    $name = trim($_POST["platformName"]);

    if ($name === "") {
        $mensaje = "El nombre no puede estar vacío.";
        $tipo = "danger";
    } else {
        $ok = $controller->updatePlataforma($pid, $name);
        $mensaje = $ok ? "Plataforma modificada correctamente." : "No se pudo modificar la plataforma.";
        $tipo = $ok ? "success" : "danger";

        // refrescar objeto (para que se vea el nombre actualizado)
        $platformObject = $controller->getPlataforma($id);
    }
}

$title = "Plataformas - Editar";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 mb-0">Editar plataforma</h1>
        <div class="text-muted">ID: <?= $platformObject->getId() ?></div>
    </div>

    <a class="btn btn-outline-secondary" href="list.php">Volver</a>
</div>

<?php if ($mensaje): ?>
    <div class="alert alert-<?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="post" action="">
            <input type="hidden" name="platformId" value="<?= $platformObject->getId() ?>">

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input
                    class="form-control"
                    type="text"
                    name="platformName"
                    value="<?= htmlspecialchars($platformObject->getNombre()) ?>"
                    required
                    autofocus
                >
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
                <a class="btn btn-outline-secondary" href="list.php">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
