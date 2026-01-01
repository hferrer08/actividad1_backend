<?php
require_once __DIR__ . "/../../controllers/PlataformaController.php";

$controller = new PlataformaController();

$mensaje = null;
$tipo = "success";

if (!isset($_GET["id"])) {
    $mensaje = "❌ Falta el parámetro id.";
    $tipo = "danger";
    $plataforma = null;
} else {
    try {
        $plataforma = $controller->getPlataforma($_GET["id"]);
        if (!$plataforma) {
            $mensaje = "❌ No existe la plataforma con ese id.";
            $tipo = "danger";
        }
    } catch (Throwable $e) {
        $mensaje = $e->getMessage();
        $tipo = "danger";
        $plataforma = null;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $controller->deletePlataforma($_POST["id"] ?? null);
        header("Location: list.php");
        exit;
    } catch (Throwable $e) {
        $mensaje = $e->getMessage();
        $tipo = "danger";
    }
}

$title = "Plataformas - Eliminar";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Eliminar plataforma</h1>
  <a class="btn btn-outline-secondary" href="list.php">Volver</a>
</div>

<?php if ($mensaje): ?>
  <div class="alert alert-<?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<?php if ($plataforma): ?>
  <div class="card shadow-sm">
    <div class="card-body">
      <p class="mb-3">
        ¿Seguro que deseas eliminar la plataforma
        <strong><?= htmlspecialchars($plataforma["nombre"] ?? "") ?></strong>?
      </p>

      <form method="POST" class="d-flex gap-2">
        <input type="hidden" name="id" value="<?= (int)($plataforma["id"] ?? 0) ?>">
        <button type="submit" class="btn btn-danger">Sí, eliminar</button>
        <a class="btn btn-outline-secondary" href="list.php">Cancelar</a>
      </form>
    </div>
  </div>
<?php else: ?>
  <a class="btn btn-outline-secondary" href="list.php">Volver al listado</a>
<?php endif; ?>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
