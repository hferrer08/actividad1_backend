<?php
require_once __DIR__ . "/../../controllers/ActorController.php";
$controller = new ActorController();

$mensaje = null;
$tipo = "success";

if (!isset($_GET["id"])) die("❌ Falta el parámetro id.");

$id = (int)$_GET["id"];
$actor = $controller->getActor($id);
if (!$actor) die("❌ No existe el actor con ese id.");

$nombre = $actor->getNombre();
$apellidos = $actor->getApellidos();
$fecha = $actor->getFechaNacimiento() ?: "";

if (isset($_POST["actorId"]) && isset($_POST["nombre"]) && isset($_POST["apellidos"])) {
    $pid = (int)$_POST["actorId"];
    $nombre = trim($_POST["nombre"]);
    $apellidos = trim($_POST["apellidos"]);
    $fecha = isset($_POST["fecha_nacimiento"]) ? trim($_POST["fecha_nacimiento"]) : "";

    if ($nombre === "" || $apellidos === "") {
        $mensaje = "Nombre y apellidos son obligatorios.";
        $tipo = "danger";
    } else {
        $ok = $controller->updateActor($pid, $nombre, $apellidos, $fecha);
        $mensaje = $ok ? "Actor modificado correctamente." : "No se pudo modificar el actor.";
        $tipo = $ok ? "success" : "danger";

        $actor = $controller->getActor($id); 
    }
}

$title = "Actores - Editar";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h1 class="h3 mb-0">Editar actor</h1>
    <div class="text-muted">ID: <?= $actor->getId() ?></div>
  </div>
  <a class="btn btn-outline-secondary" href="list.php">Volver</a>
</div>

<?php if ($mensaje): ?>
  <div class="alert alert-<?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="post" action="">
      <input type="hidden" name="actorId" value="<?= $actor->getId() ?>">

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
