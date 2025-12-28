<?php
require_once __DIR__ . "/../../controllers/IdiomaController.php";
$controller = new IdiomaController();

$mensaje = null;
$tipo = "success";

$nombre = "";
$codigo = "";

if (isset($_POST["nombre"]) && isset($_POST["codigo"])) {
    $nombre = trim($_POST["nombre"]);
    $codigo = strtolower(trim($_POST["codigo"])); // estilo ISO: en, es, fr...

    if ($nombre === "" || $codigo === "") {
        $mensaje = "Nombre y código son obligatorios.";
        $tipo = "danger";
    } elseif (strlen($codigo) > 10) {
        $mensaje = "El código no puede superar 10 caracteres.";
        $tipo = "danger";
    } else {
        try {
            $ok = $controller->createIdioma($nombre, $codigo);
            $mensaje = $ok ? "Idioma creado correctamente." : "No se pudo crear el idioma.";
            $tipo = $ok ? "success" : "danger";

            if ($ok) { $nombre = ""; $codigo = ""; }
        } catch (Exception $e) {
            $mensaje = "Error: puede que el nombre o código ya existan.";
            $tipo = "danger";
        }
    }
}

$title = "Idiomas - Crear";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Crear idioma</h1>
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
        <label class="form-label">Código</label>
        <input class="form-control" name="codigo" value="<?= htmlspecialchars($codigo) ?>" required maxlength="10">
        <div class="form-text">Ejemplos: <code>es</code>, <code>en</code>, <code>fr</code>.</div>
      </div>

      <button class="btn btn-primary" type="submit">Guardar</button>
      <a class="btn btn-outline-secondary" href="list.php">Cancelar</a>
    </form>
  </div>
</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
