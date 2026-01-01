<?php
require_once __DIR__ . "/../../controllers/DirectorController.php";

$controller = new DirectorController();

$mensaje = null;
$tipo = "success";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$director = null;

if ($id <= 0) {
    $mensaje = "❌ Falta el parámetro id.";
    $tipo = "danger";
} else {
    try {
        $director = $controller->getDirector($id);
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

// Valores iniciales (si existe el director)
$nombre = $director ? $director->getNombre() : "";
$apellidos = $director ? $director->getApellidos() : "";
$fechaNacimiento = $director ? $director->getFechaNacimiento() : "";
$nacionalidad = $director ? $director->getNacionalidad() : "";

// POST: guardar cambios
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"] ?? "";
    $apellidos = $_POST["apellidos"] ?? "";
    $fechaNacimiento = $_POST["fecha_nacimiento"] ?? "";
    $nacionalidad = $_POST["nacionalidad"] ?? "";

    try {
        $controller->updateDirector($id, $nombre, $apellidos, $fechaNacimiento, $nacionalidad);
        header("Location: list.php");
        exit;
    } catch (Throwable $e) {
        $mensaje = $e->getMessage();
        $tipo = "danger";
    }
}

$title = "Directores - Editar";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Editar director</h1>
  <a class="btn btn-outline-secondary" href="list.php">Volver</a>
</div>

<?php if ($mensaje): ?>
  <div class="alert alert-<?= htmlspecialchars($tipo) ?>">
    <?= htmlspecialchars($mensaje) ?>
  </div>
<?php endif; ?>

<?php if ($director): ?>
  <div class="card shadow-sm">
    <div class="card-body">
      <form method="POST" novalidate>
        <div class="row g-3">
          <div class="col-md-6">
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

          <div class="col-md-6">
            <label class="form-label">Apellidos <span class="text-danger">*</span></label>
            <input
              type="text"
              name="apellidos"
              class="form-control"
              value="<?= htmlspecialchars($apellidos) ?>"
              required
              maxlength="120"
            >
          </div>

          <div class="col-md-6">
            <label class="form-label">Fecha nacimiento</label>
            <input
              type="date"
              name="fecha_nacimiento"
              class="form-control"
              value="<?= htmlspecialchars($fechaNacimiento) ?>"
            >
          </div>

          <div class="col-md-6">
            <label class="form-label">Nacionalidad</label>
            <input
              type="text"
              name="nacionalidad"
              class="form-control"
              value="<?= htmlspecialchars($nacionalidad) ?>"
              maxlength="100"
            >
          </div>
        </div>

        <div class="mt-4 d-flex gap-2">
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
