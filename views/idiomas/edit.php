<?php
require_once __DIR__ . "/../../controllers/IdiomaController.php";
$controller = new IdiomaController();

$mensaje = null;
$tipo = "success";

if (!isset($_GET["id"])) die("❌ Falta el parámetro id.");

$id = (int)$_GET["id"];
$idioma = $controller->getIdioma($id);
if (!$idioma) die("❌ No existe el idioma con ese id.");

$nombre = $idioma->getNombre();
$codigo = $idioma->getCodigo();

if (isset($_POST["idiomaId"]) && isset($_POST["nombre"]) && isset($_POST["codigo"])) {
    $pid = (int)$_POST["idiomaId"];
    $nombre = trim($_POST["nombre"]);
    $codigo = strtolower(trim($_POST["codigo"]));

    if ($nombre === "" || $codigo === "") {
        $mensaje = "Nombre y código son obligatorios.";
        $tipo = "danger";
    } elseif (strlen($codigo) > 10) {
        $mensaje = "El código no puede superar 10 caracteres.";
        $tipo = "danger";
    } else {
        try {
            $ok = $controller->updateIdioma($pid, $nombre, $codigo);
            $mensaje = $ok ? "Idioma modificado correctamente." : "No se pudo modificar el idioma.";
            $tipo = $ok ? "success" : "danger";

            $idioma = $controller->getIdioma($id); // refresca
        } catch (Exception $e) {
            $mensaje = "Error: puede que el nombre o código ya existan.";
            $tipo = "danger";
        }
    }
}

$title = "Idiomas - Editar";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h1 class="h3 mb-0">Editar idioma</h1>
    <div class="text-muted">ID: <?= $idioma->getId() ?></div>
  </div>
  <a class="btn btn-outline-secondary" href="list.php">Volver</a>
</div>

<?php if ($mensaje): ?>
  <div class="alert alert-<?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="post" action="">
      <input type="hidden" name="idiomaId" value="<?= $idioma->getId() ?>">

      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input class="form-control" name="nombre" value="<?= htmlspecialchars($nombre) ?>" required autofocus>
      </div>

      <div class="mb-3">
        <label class="form-label">Código</label>
        <input class="form-control" name="codigo" value="<?= htmlspecialchars($codigo) ?>" required maxlength="10">
      </div>

      <button class="btn btn-primary" type="submit">Guardar cambios</button>
      <a class="btn btn-outline-secondary" href="list.php">Cancelar</a>
    </form>
  </div>
</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
