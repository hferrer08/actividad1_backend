<?php
require_once __DIR__ . "/../../controllers/DirectorController.php";

$controller = new DirectorController();

$mensaje = null;
$tipo = "success";

$nombre = "";
$apellidos = "";
$fechaNacimiento = "";
$nacionalidad = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"] ?? "";
    $apellidos = $_POST["apellidos"] ?? "";
    $fechaNacimiento = $_POST["fecha_nacimiento"] ?? "";
    $nacionalidad = $_POST["nacionalidad"] ?? "";

    try {
        $controller->createDirector($nombre, $apellidos, $fechaNacimiento, $nacionalidad);
        header("Location: list.php");
        exit;
    } catch (Throwable $e) {
        $mensaje = $e->getMessage();
        $tipo = "danger";
    }
}

$title = "Directores - Nuevo";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Nuevo director</h1>
  <a class="btn btn-outline-secondary" href="list.php">Volver</a>
</div>

<?php if ($mensaje): ?>
  <div class="alert alert-<?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="POST">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nombre <span class="text-danger">*</span></label>
          <input type="text" name="nombre" class="form-control"
                 value="<?= htmlspecialchars($nombre) ?>" required maxlength="100">
        </div>

        <div class="col-md-6">
          <label class="form-label">Apellidos <span class="text-danger">*</span></label>
          <input type="text" name="apellidos" class="form-control"
                 value="<?= htmlspecialchars($apellidos) ?>" required maxlength="120">
        </div>

        <div class="col-md-6">
          <label class="form-label">Fecha nacimiento</label>
          <input type="date" name="fecha_nacimiento" class="form-control"
                 value="<?= htmlspecialchars($fechaNacimiento) ?>">
        </div>

        <div class="col-md-6">
          <label class="form-label">Nacionalidad</label>
          <input type="text" name="nacionalidad" class="form-control"
                 value="<?= htmlspecialchars($nacionalidad) ?>" maxlength="100">
        </div>
      </div>

      <div class="mt-4 d-flex gap-2">
        <button class="btn btn-primary" type="submit">Guardar</button>
        <a class="btn btn-outline-secondary" href="list.php">Cancelar</a>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
