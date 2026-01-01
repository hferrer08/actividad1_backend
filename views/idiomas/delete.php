<?php
require_once __DIR__ . "/../../controllers/IdiomaController.php";

$controller = new IdiomaController();

$mensaje = null;
$tipo = "success";

if (!isset($_GET["id"])) {
    $mensaje = "❌ Falta el parámetro id.";
    $tipo = "danger";
    $idioma = null;
} else {
    try {
        $idioma = $controller->getIdioma($_GET["id"]);
        if (!$idioma) {
            $mensaje = "❌ No existe el idioma con ese id.";
            $tipo = "danger";
        }
    } catch (Throwable $e) {
        $mensaje = $e->getMessage();
        $tipo = "danger";
        $idioma = null;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $controller->deleteIdioma($_POST["id"] ?? null);
        header("Location: list.php");
        exit;
    } catch (Throwable $e) {
        $mensaje = $e->getMessage();
        $tipo = "danger";
    }
}

$title = "Idiomas - Eliminar";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Eliminar idioma</h1>
  <a class="btn btn-outline-secondary" href="list.php">Volver</a>
</div>

<?php if ($mensaje): ?>
  <div class="alert alert-<?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<?php if ($idioma): ?>
  <div class="card shadow-sm">
    <div class="card-body">
      <p class="mb-3">
        ¿Seguro que deseas eliminar el idioma
        <strong><?= htmlspecialchars($idioma["nombre"] ?? "") ?></strong>
        (<?= htmlspecialchars($idioma["codigo"] ?? "") ?>)?
      </p>

      <form method="POST" class="d-flex gap-2">
        <input type="hidden" name="id" value="<?= (int)($idioma["id"] ?? 0) ?>">
        <button type="submit" class="btn btn-danger">Sí, eliminar</button>
        <a class="btn btn-outline-secondary" href="list.php">Cancelar</a>
      </form>
    </div>
  </div>
<?php else: ?>
  <a class="btn btn-outline-secondary" href="list.php">Volver al listado</a>
<?php endif; ?>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
