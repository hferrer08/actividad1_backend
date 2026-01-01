<?php
require_once __DIR__ . "/../../controllers/DirectorController.php";

$controller = new DirectorController();

$mensaje = null;
$tipo = "success";

if (!isset($_GET["id"])) {
    $mensaje = "❌ Falta el parámetro id.";
    $tipo = "danger";
    $director = null;
} else {
    try {
        $director = $controller->getDirector($_GET["id"]);
        if (!$director) {
            $mensaje = "❌ No existe el director con ese id.";
            $tipo = "danger";
        }
    } catch (Throwable $e) {
        $mensaje = $e->getMessage();
        $tipo = "danger";
        $director = null;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $controller->deleteDirector($_POST["id"] ?? null);
        header("Location: list.php");
        exit;
    } catch (Throwable $e) {
        $mensaje = $e->getMessage();
        $tipo = "danger";
    }
}

$title = "Directores - Eliminar";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Eliminar director</h1>
  <a class="btn btn-outline-secondary" href="list.php">Volver</a>
</div>

<?php if ($mensaje): ?>
  <div class="alert alert-<?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<?php if ($director): ?>
  <div class="card shadow-sm">
    <div class="card-body">
      <p class="mb-3">
        ¿Seguro que deseas eliminar al director
        <strong><?= htmlspecialchars($director["nombre"] . " " . $director["apellidos"]) ?></strong>?
      </p>

      <form method="POST" class="d-flex gap-2">
        <input type="hidden" name="id" value="<?= (int)$director["id"] ?>">
        <button type="submit" class="btn btn-danger">Sí, eliminar</button>
        <a class="btn btn-outline-secondary" href="list.php">Cancelar</a>
      </form>
    </div>
  </div>
<?php else: ?>
  <a class="btn btn-outline-secondary" href="list.php">Volver al listado</a>
<?php endif; ?>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
