<?php
require_once __DIR__ . "/../../controllers/SerieController.php";
$controller = new SerieController();

$mensaje = null;
$tipo = "success";

if (!isset($_GET["id"])) die("❌ Falta el parámetro id.");

$id = (int)$_GET["id"];
$serie = $controller->getSerie($id);
if (!$serie) die("❌ No existe la serie con ese id.");

// Catálogos (objetos)
$plataformas = $controller->listPlataformas();
$actores = $controller->listActores();
$idiomas = $controller->listIdiomas();
$directores = $controller->listDirectores();

function isChecked(array $selectedIds, int $id): string {
    return in_array($id, $selectedIds, true) ? "checked" : "";
}

// Valores iniciales
$titulo = $serie["titulo"];
$sinopsis = $serie["sinopsis"] ?? "";
$anio = $serie["anio"] ?? "";
$temporadas = $serie["temporadas"] ?? "";
$directorId = $serie["director_id"] ?? "";

// Seleccionados desde BD (precarga checks)
$selectedPlataformas = $controller->getPlataformaIdsBySerie($id);
$selectedActores     = $controller->getActorIdsBySerie($id);
$selectedAudio       = $controller->getAudioIdiomaIdsBySerie($id);
$selectedSub         = $controller->getSubIdiomaIdsBySerie($id);

// POST update
if (isset($_POST["serieId"]) && isset($_POST["titulo"])) {
    $pid = (int)$_POST["serieId"];

    $titulo = trim($_POST["titulo"]);
    $sinopsis = isset($_POST["sinopsis"]) ? trim($_POST["sinopsis"]) : "";
    $anio = isset($_POST["anio"]) ? trim($_POST["anio"]) : "";
    $temporadas = isset($_POST["temporadas"]) ? trim($_POST["temporadas"]) : "";
    $directorId = isset($_POST["director_id"]) ? trim($_POST["director_id"]) : "";

    // arrays desde checkboxes
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
                $directorId,
                $selectedPlataformas,
                $selectedActores,
                $selectedAudio,
                $selectedSub
            );

            $mensaje = $ok ? "Serie modificada correctamente." : "No se pudo modificar la serie.";
            $tipo = $ok ? "success" : "danger";

            // refrescar serie desde BD (por si cambió algo)
            $serie = $controller->getSerie($id);
            $directorId = $serie["director_id"] ?? "";
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
        <div class="mb-3">
  <label class="form-label">Director</label>
  <select class="form-select" name="director_id">
    <option value="">-- Sin director --</option>

    <?php foreach ($directores as $d): ?>
      <?php $did = (int)$d->getId(); ?>
      <option value="<?= $did ?>" <?= ((string)$did === (string)$directorId) ? "selected" : "" ?>>
        <?= htmlspecialchars(trim($d->getNombre() . " " . $d->getApellidos())) ?>
      </option>
    <?php endforeach; ?>
  </select>
</div>
      </div>

      <hr class="my-4">

      <!-- PLATAFORMAS -->
      <h5 class="mb-2">Plataformas</h5>
      <div class="card shadow-sm mb-4">
        <div class="table-responsive">
          <table class="table table-sm table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr>
                <th style="width:70px;">Sel</th>
                <th>Nombre</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($plataformas as $p): $pid = (int)$p->getId(); ?>
                <tr>
                  <td>
                    <input class="form-check-input" type="checkbox"
                           name="plataformas[]" value="<?= $pid ?>"
                           <?= isChecked($selectedPlataformas, $pid) ?>>
                  </td>
                  <td><?= htmlspecialchars($p->getNombre()) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- ACTORES -->
      <h5 class="mb-2">Actores/Actrices</h5>
      <div class="card shadow-sm mb-4">
        <div class="table-responsive">
          <table class="table table-sm table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr>
                <th style="width:70px;">Sel</th>
                <th>Nombre</th>
                <th>Apellidos</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($actores as $a): $aid = (int)$a->getId(); ?>
                <tr>
                  <td>
                    <input class="form-check-input" type="checkbox"
                           name="actores[]" value="<?= $aid ?>"
                           <?= isChecked($selectedActores, $aid) ?>>
                  </td>
                  <td><?= htmlspecialchars($a->getNombre()) ?></td>
                  <td><?= htmlspecialchars($a->getApellidos()) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- IDIOMAS AUDIO -->
      <h5 class="mb-2">Idiomas de Audio</h5>
      <div class="card shadow-sm mb-4">
        <div class="table-responsive">
          <table class="table table-sm table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr>
                <th style="width:70px;">Sel</th>
                <th>Nombre</th>
                <th style="width:120px;">Código</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($idiomas as $i): $iid = (int)$i->getId(); ?>
                <tr>
                  <td>
                    <input class="form-check-input" type="checkbox"
                           name="idiomas_audio[]" value="<?= $iid ?>"
                           <?= isChecked($selectedAudio, $iid) ?>>
                  </td>
                  <td><?= htmlspecialchars($i->getNombre()) ?></td>
                  <td><?= htmlspecialchars($i->getCodigo()) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="card-body py-2">
          <div class="form-text">Idiomas disponibles como audio.</div>
        </div>
      </div>

      <!-- IDIOMAS SUB -->
      <h5 class="mb-2">Idiomas de Subtítulos</h5>
      <div class="card shadow-sm mb-4">
        <div class="table-responsive">
          <table class="table table-sm table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr>
                <th style="width:70px;">Sel</th>
                <th>Nombre</th>
                <th style="width:120px;">Código</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($idiomas as $i): $iid = (int)$i->getId(); ?>
                <tr>
                  <td>
                    <input class="form-check-input" type="checkbox"
                           name="idiomas_sub[]" value="<?= $iid ?>"
                           <?= isChecked($selectedSub, $iid) ?>>
                  </td>
                  <td><?= htmlspecialchars($i->getNombre()) ?></td>
                  <td><?= htmlspecialchars($i->getCodigo()) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="card-body py-2">
          <div class="form-text">Idiomas disponibles como subtítulos.</div>
        </div>
      </div>

      <button class="btn btn-primary" type="submit">Guardar cambios</button>
      <a class="btn btn-outline-secondary" href="list.php">Cancelar</a>
    </form>
  </div>
</div>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
