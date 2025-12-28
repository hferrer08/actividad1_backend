<?php
require_once __DIR__ . "/../../controllers/DirectorController.php";
$controller = new DirectorController();

$mensaje = null;
$tipo = "success";

if (!isset($_GET["id"])) die("❌ Falta el parámetro id.");

$id = (int)$_GET["id"];
$director = $controller->getDirector($id);
if (!$director) die("❌ No existe el director con ese id.");

$nombre = $director->getNombre();
$apellidos = $director->getApellidos();
$fecha = $director->getFechaNacimiento() ?: "";

if (isset($_POST["directorId"]) && isset($_POST["nombre"]) && isset($_POST["apellidos"])) {
    $pid = (int)$_POST["directorId"];
    $nombre = trim($_POST["nombre"]);
    $apellidos = trim($_POST["apellidos"]);
    $fecha = isset($_POST["fecha_nacimiento"]) ? trim($_POST["fecha_nacimiento"]) : "";

    if ($nombre === "" || $apellidos === "") {
        $mensaje = "Nombre y apellidos son obligatorios.";
        $tipo = "danger";
    } else {
        $ok = $controller->updateDirector($pid, $nombre, $apellidos, $fecha);
        $mensaje = $ok ? "Director modificado correctamente." : "No se pudo modificar el director.";
        $tipo = $ok ? "success" : "danger";

        $director = $controller->getDirector($id); // refresca
    }
}

$title = "Directores - Editar";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h1 class="h3 mb-0">Editar director</h1>
    <div class="text-muted">ID: <?= $director->getId() ?></div>
  </div>
  <a class="btn btn-outline-secondary" href="list.php">Volver</a>
</div>

<?php if ($mensaje): ?>
  <div class="alert alert-<?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="post" action="">
      <input type="hidden" name="directorId" value="<?= $director->getId() ?>">

      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input class="form-control" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required autofocus>
      </div>

      <div class="mb-3">
        <label class="form-label">Apellidos</label>
        <input class="form-control" name="apellidos" value="<?= htmlspecialchars($apellidos) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Fecha de nacimiento</label>
        <input class="form-control" type="date" name="fecha_nacimiento" value="<?= htmlspecialchars($fecha) ?>">
      </div>

      <button class="btn btn-primary" type="submit">Guardar cambios</button>
      <a class="btn btn-outline-secondary" href="list.php">Cancelar</a>
    </form>
  </div>
</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
