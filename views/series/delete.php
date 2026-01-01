<?php
require_once __DIR__ . "/../../controllers/SerieController.php";

$controller = new SerieController();

$mensaje = null;
$tipo = "success";

if (!isset($_GET["id"])) {
    $mensaje = "❌ Falta el parámetro id.";
    $tipo = "danger";
    $serie = null;
} else {
    try {
        $serie = $controller->getSerie($_GET["id"]);
        if (!$serie) {
            $mensaje = "❌ No existe la serie con ese id.";
            $tipo = "danger";
        }
    } catch (Throwable $e) {
        $mensaje = $e->getMessage();
        $tipo = "danger";
        $serie = null;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $controller->deleteSerie($_POST["id"] ?? null);
        header("Location: list.php");
        exit;
    } catch (Throwable $e) {
        $mensaje = $e->getMessage();
        $tipo = "danger";
    }
}

$title = "Series - Eliminar";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Eliminar serie</h1>
  <a class="btn btn-outline-secondary" href="list.php">Volver</a>
</div>

<?php if ($mensaje): ?>
  <div class="alert alert-<?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<?php if ($serie): ?>
  <div class="card shadow-sm">
    <div class="card-body">
      <p class="mb-3">
        ¿Seguro que deseas eliminar la serie
        <strong><?= htmlspecialchars($serie["titulo"] ?? "") ?></strong>?
      </p>

      <?php if (!empty($serie["anio"])): ?>
        <p class="text-muted mb-3">Año: <?= htmlspecialchars($serie["anio"]) ?></p>
      <?php endif; ?>

      <form method="POST" class="d-flex gap-2">
        <input type="hidden" name="id" value="<?= (int)($serie["id"] ?? 0) ?>">
        <button type="submit" class="btn btn-danger">Sí, eliminar</button>
        <a class="btn btn-outline-secondary" href="list.php">Cancelar</a>
      </form>
    </div>
  </div>
<?php else: ?>
  <a class="btn btn-outline-secondary" href="list.php">Volver al listado</a>
<?php endif; ?>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
