<?php
require_once __DIR__ . "/../../controllers/ActorController.php";

$controller = new ActorController();

$mensaje = null;
$tipo = "success";

if (!isset($_GET["id"])) {
    $mensaje = "❌ Falta el parámetro id.";
    $tipo = "danger";
    $actor = null;
} else {
    try {
        $actor = $controller->getActor($_GET["id"]);
        if (!$actor) {
            $mensaje = "❌ No existe el actor con ese id.";
            $tipo = "danger";
        }
    } catch (Throwable $e) {
        $mensaje = $e->getMessage();
        $tipo = "danger";
        $actor = null;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $controller->deleteActor($_POST["id"] ?? null);
        header("Location: list.php");
        exit;
    } catch (Throwable $e) {
        $mensaje = $e->getMessage();
        $tipo = "danger";
    }
}

$title = "Actores - Eliminar";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Eliminar actor</h1>
  <a class="btn btn-outline-secondary" href="list.php">Volver</a>
</div>

<?php if ($mensaje): ?>
  <div class="alert alert-<?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<?php if ($actor): ?>
  <div class="card shadow-sm">
    <div class="card-body">
      <p class="mb-3">
        ¿Seguro que deseas eliminar al actor
        <strong><?= htmlspecialchars($actor["nombre"] . " " . $actor["apellidos"]) ?></strong>?
      </p>

      <form method="POST" class="d-flex gap-2">
        <input type="hidden" name="id" value="<?= (int)$actor["id"] ?>">
        <button type="submit" class="btn btn-danger">Sí, eliminar</button>
        <a class="btn btn-outline-secondary" href="list.php">Cancelar</a>
      </form>
    </div>
  </div>
<?php else: ?>
  <a class="btn btn-outline-secondary" href="list.php">Volver al listado</a>
<?php endif; ?>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
