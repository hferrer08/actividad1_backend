<?php
require_once __DIR__ . "/../../controllers/PlataformaController.php";
$controller = new PlataformaController();

$mensaje = null;
$tipo = "success";

if (isset($_POST["platformName"])) {
  $name = trim($_POST["platformName"]);

  if ($name === "") {
    $mensaje = "El nombre no puede estar vacío.";
    $tipo = "danger";
  } else {
    $ok = $controller->createPlataforma($name);
    $mensaje = $ok ? "Plataforma creada correctamente." : "No se pudo crear la plataforma.";
    $tipo = $ok ? "success" : "danger";
  }
}

$title = "Plataformas - Crear";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Crear plataforma</h1>
  <a class="btn btn-outline-secondary" href="list.php">Volver</a>
</div>

<?php if ($mensaje): ?>
  <div class="alert alert-<?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="post" action="">
      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input class="form-control" type="text" name="platformName" required>
      </div>
      <button class="btn btn-primary" type="submit">Guardar</button>
    </form>
  </div>
</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
