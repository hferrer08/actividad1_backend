<?php
require_once __DIR__ . "/../../controllers/SerieController.php";
$controller = new SerieController();

$mensaje = null;
$tipo = "success";

if (!isset($_GET["id"])) die("❌ Falta el parámetro id.");

$id = (int)$_GET["id"];
$serie = $controller->getSerie($id);
if (!$serie) die("❌ No existe la serie con ese id.");

// catálogos
$plataformas = $controller->listPlataformas();
$actores = $controller->listActores();
$idiomas = $controller->listIdiomas();

function selectedOpt(array $selected, int $id): string {
    return in_array($id, $selected, true) ? "selected" : "";
}

// valores iniciales
$titulo = $serie["titulo"];
$sinopsis = $serie["sinopsis"] ?? "";
$anio = $serie["anio"] ?? "";
$temporadas = $serie["temporadas"] ?? "";

// selected inicial desde BD
$selectedPlataformas = $controller->getPlataformaIdsBySerie($id);
$selectedActores = $controller->getActorIdsBySerie($id);
$selectedAudio = $controller->getAudioIdiomaIdsBySerie($id);
$selectedSub = $controller->getSubIdiomaIdsBySerie($id);

if (isset($_POST["serieId"]) && isset($_POST["titulo"])) {
    $pid = (int)$_POST["serieId"];
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
            $ok = $controller->updateSerie(
                $pid,
                $titulo,
                $sinopsis,
                $anio,
                $temporadas,
                $selectedPlataformas,
                $selectedActores,
                $selectedAudio,
                $selectedSub
            );

            $mensaje = $ok ? "Serie modificada correctamente." : "No se pudo modificar la serie.";
            $tipo = $ok ? "success" : "danger";

            // refrescar serie desde BD
            $serie = $controller->getSerie($id);
        } catch (Throwable $e) {
            $mensaje = "Error al modificar: " . $e->getMessage();
            $tipo = "danger";
        }
    }
}

$title = "Series - Editar";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h1 class="h3 mb-0">Editar serie</h1>
  </div>
  <a class="btn btn-outline-secondary" href="list.php">Volver</a>
</div>

<?php if ($mensaje): ?>
  <div class="alert alert-<?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<div class="card shadow-sm">
  <div class="card-body">
    <form method="post" action="">
      <input type="hidden" name="serieId" value="<?= (int)$id ?>">

      <div class="mb-3">
        <label class="form-label">Título</label>
        <input class="form-control" name="titulo" value="<?= htmlspecialchars($titulo) ?>" required autofocus>
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
          <label class="form-label">Plataformas</label>
          <select class="form-select" name="plataformas[]" multiple size="8">
            <?php foreach ($plataformas as $p): $pid = (int)$p->getId(); ?>
              <option value="<?= $pid ?>" <?= selectedOpt($selectedPlataformas, $pid) ?>>
                <?= htmlspecialchars($p->getNombre()) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Actores/Actrices</label>
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
          <label class="form-label">Idiomas Audio</label>
          <select class="form-select" name="idiomas_audio[]" multiple size="8">
            <?php foreach ($idiomas as $i): $iid = (int)$i->getId(); ?>
              <option value="<?= $iid ?>" <?= selectedOpt($selectedAudio, $iid) ?>>
                <?= htmlspecialchars($i->getNombre() . " (" . $i->getCodigo() . ")") ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label">Idiomas Subtítulos</label>
          <select class="form-select" name="idiomas_sub[]" multiple size="8">
            <?php foreach ($idiomas as $i): $iid = (int)$i->getId(); ?>
              <option value="<?= $iid ?>" <?= selectedOpt($selectedSub, $iid) ?>>
                <?= htmlspecialchars($i->getNombre() . " (" . $i->getCodigo() . ")") ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <button class="btn btn-primary" type="submit">Guardar cambios</button>
      <a class="btn btn-outline-secondary" href="list.php">Cancelar</a>
    </form>
  </div>
</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
