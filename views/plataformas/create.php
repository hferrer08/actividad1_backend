<?php
require_once __DIR__ . "/../../controllers/PlataformaController.php";

$controller = new PlataformaController();

$mensaje = null;
$tipo = "success";

$nombre = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"] ?? "";

    try {
        $controller->createPlataforma($nombre);
        header("Location: list.php");
        exit;
    } catch (Throwable $e) {
        $mensaje = $e->getMessage();
        $tipo = "danger";
    }
}

$title = "Plataformas - Nueva";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Nueva plataforma</h1>
  <a class="btn btn-outline-secondary" href="list.php">Volver</a>
</div>

<?php if ($mensaje): ?>
  <div class="alert alert-<?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Nombre <span class="text-danger">*</span></label>
        <input type="text" name="nombre" class="form-control"
               value="<?= htmlspecialchars($nombre) ?>" required maxlength="100">
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-primary" type="submit">Guardar</button>
        <a class="btn btn-outline-secondary" href="list.php">Cancelar</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
