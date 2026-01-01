<?php
require_once __DIR__ . "/../../controllers/PlataformaController.php";

$controller = new PlataformaController();

$mensaje = null;
$tipo = "success";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$plataforma = null;

if ($id <= 0) {
    $mensaje = "❌ Falta el parámetro id.";
    $tipo = "danger";
} else {
    try {
        $plataforma = $controller->getPlataforma($id);
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

// Valor inicial (si existe la plataforma)
$nombre = $plataforma ? $plataforma->getNombre() : "";

// POST: guardar cambios
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"] ?? "";

    try {
        $controller->updatePlataforma($id, $nombre);
        header("Location: list.php");
        exit;
    } catch (Throwable $e) {
        $mensaje = $e->getMessage();
        $tipo = "danger";
    }
}

$title = "Plataformas - Editar";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Editar plataforma</h1>
  <a class="btn btn-outline-secondary" href="list.php">Volver</a>
</div>

<?php if ($mensaje): ?>
  <div class="alert alert-<?= htmlspecialchars($tipo) ?>">
    <?= htmlspecialchars($mensaje) ?>
  </div>
<?php endif; ?>

<?php if ($plataforma): ?>
  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" novalidate>
        <div class="mb-3">
          <label class="form-label">Nombre <span class="text-danger">*</span></label>
          <input
            type="text"
            name="nombre"
            class="form-control"
            value="<?= htmlspecialchars($nombre) ?>"
            required
            maxlength="100"
          >
        </div>

        <div class="d-flex gap-2">
          <button class="btn btn-primary" type="submit">Guardar cambios</button>
          <a class="btn btn-outline-secondary" href="list.php">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
<?php else: ?>
  <a class="btn btn-outline-secondary" href="list.php">Volver al listado</a>
<?php endif; ?>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
