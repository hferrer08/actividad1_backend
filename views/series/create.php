<?php
require_once __DIR__ . "/../../controllers/SerieController.php";
$controller = new SerieController();

$mensaje = null;
$tipo = "success";

$titulo = "";
$sinopsis = "";
$anio = "";
$temporadas = "";

// Catálogos (objetos)
$plataformas = $controller->listPlataformas();
$actores = $controller->listActores();
$idiomas = $controller->listIdiomas();

// seleccionados (para mantener selección si falla validación)
$selectedPlataformas = [];
$selectedActores = [];
$selectedAudio = [];
$selectedSub = [];

function selectedOpt(array $selected, int $id): string {
    return in_array($id, $selected, true) ? "selected" : "";
}

if (isset($_POST["titulo"])) {
    $titulo = trim($_POST["titulo"]);
    $sinopsis = isset($_POST["sinopsis"]) ? trim($_POST["sinopsis"]) : "";
    $anio = isset($_POST["anio"]) ? trim($_POST["anio"]) : "";
    $temporadas = isset($_POST["temporadas"]) ? trim($_POST["temporadas"]) : "";

    $selectedPlataformas = isset($_POST["plataformas"]) ? array_map("intval", (array)$_POST["plataformas"]) : [];
    $selectedActores     = isset($_POST["actores"]) ? array_map("intval", (array)$_POST["actores"]) : [];
    $selectedAudio       = isset($_POST["idiomas_audio"]) ? array_map("intval", (array)$_POST["idiomas_audio"]) : [];
    $selectedSub         = isset($_POST["idiomas_sub"]) ? array_map("intval", (array)$_POST["idiomas_sub"]) : [];

    if ($titulo === "") {
        $mensaje = "El título es obligatorio.";
        $tipo = "danger";
    } else {
        try {
            $controller->createSerie(
                $titulo,
                $sinopsis,
                $anio,
                $temporadas,
                $selectedPlataformas,
                $selectedActores,
                $selectedAudio,
                $selectedSub
            );

            $mensaje = "Serie creada correctamente.";
            $tipo = "success";

            // limpiar form
            $titulo = ""; $sinopsis = ""; $anio = ""; $temporadas = "";
            $selectedPlataformas = []; $selectedActores = []; $selectedAudio = []; $selectedSub = [];
        } catch (Throwable $e) {
            $mensaje = "No se pudo crear la serie: " . $e->getMessage();
            $tipo = "danger";
        }
    }
}

$title = "Series - Crear";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Crear serie</h1>
  <a class="btn btn-outline-secondary" href="list.php">Volver</a>
</div>

<?php if ($mensaje): ?>
  <div class="alert alert-<?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="post" action="">
      <div class="mb-3">
        <label class="form-label">Título</label>
        <input class="form-control" name="titulo" value="<?= htmlspecialchars($titulo) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Sinopsis</label>
        <textarea class="form-control" name="sinopsis" rows="4"><?= htmlspecialchars($sinopsis) ?></textarea>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Año</label>
          <input class="form-control" type="number" name="anio" value="<?= htmlspecialchars($anio) ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Temporadas</label>
          <input class="form-control" type="number" name="temporadas" value="<?= htmlspecialchars($temporadas) ?>">
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Plataformas (multi)</label>
          <select class="form-select" name="plataformas[]" multiple size="8">
            <?php foreach ($plataformas as $p): $pid = (int)$p->getId(); ?>
              <option value="<?= $pid ?>" <?= selectedOpt($selectedPlataformas, $pid) ?>>
                <?= htmlspecialchars($p->getNombre()) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Actores/Actrices (multi)</label>
          <select class="form-select" name="actores[]" multiple size="8">
            <?php foreach ($actores as $a): $aid = (int)$a->getId(); ?>
              <option value="<?= $aid ?>" <?= selectedOpt($selectedActores, $aid) ?>>
                <?= htmlspecialchars(trim($a->getNombre() . " " . $a->getApellidos())) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Idiomas Audio (multi)</label>
          <select class="form-select" name="idiomas_audio[]" multiple size="8">
            <?php foreach ($idiomas as $i): $iid = (int)$i->getId(); ?>
              <option value="<?= $iid ?>" <?= selectedOpt($selectedAudio, $iid) ?>>
                <?= htmlspecialchars($i->getNombre() . " (" . $i->getCodigo() . ")") ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Idiomas Subtítulos (multi)</label>
          <select class="form-select" name="idiomas_sub[]" multiple size="8">
            <?php foreach ($idiomas as $i): $iid = (int)$i->getId(); ?>
              <option value="<?= $iid ?>" <?= selectedOpt($selectedSub, $iid) ?>>
                <?= htmlspecialchars($i->getNombre() . " (" . $i->getCodigo() . ")") ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <button class="btn btn-primary" type="submit">Guardar</button>
      <a class="btn btn-outline-secondary" href="list.php">Cancelar</a>
    </form>
  </div>
</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
