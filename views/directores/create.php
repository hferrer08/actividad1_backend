<?php
require_once __DIR__ . "/../../controllers/DirectorController.php";
$controller = new DirectorController();

$mensaje = null;
$tipo = "success";

$nombre = "";
$apellidos = "";
$fecha = "";

if (isset($_POST["nombre"]) && isset($_POST["apellidos"])) {
    $nombre = trim($_POST["nombre"]);
    $apellidos = trim($_POST["apellidos"]);
    $fecha = isset($_POST["fecha_nacimiento"]) ? trim($_POST["fecha_nacimiento"]) : "";

    if ($nombre === "" || $apellidos === "") {
        $mensaje = "Nombre y apellidos son obligatorios.";
        $tipo = "danger";
    } else {
        $ok = $controller->createDirector($nombre, $apellidos, $fecha);
        $mensaje = $ok ? "Director creado correctamente." : "No se pudo crear el director.";
        $tipo = $ok ? "success" : "danger";

        if ($ok) { $nombre = ""; $apellidos = ""; $fecha = ""; }
    }
}

$title = "Directores - Crear";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Crear director</h1>
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
        <input class="form-control" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Apellidos</label>
        <input class="form-control" name="apellidos" value="<?= htmlspecialchars($apellidos) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Fecha de nacimiento</label>
        <input class="form-control" type="date" name="fecha_nacimiento" value="<?= htmlspecialchars($fecha) ?>">
        <div class="form-text">Opcional.</div>
      </div>

      <button class="btn btn-primary" type="submit">Guardar</button>
      <a class="btn btn-outline-secondary" href="list.php">Cancelar</a>
    </form>
  </div>
</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
