<?php
require_once __DIR__ . "/../../controllers/DirectorController.php";
$controller = new DirectorController();
$directores = $controller->listDirectores();

$title = "Directores - Listado";
require_once __DIR__ . "/../partials/header.php";

function formatDateDMY($date) {
    if (!$date) return "-";
    $ts = strtotime($date);
    return $ts ? date("d/m/Y", $ts) : "-";
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Directores</h1>
  <a class="btn btn-primary" href="create.php">+ Nuevo director</a>
</div>

<?php if ($directores && count($directores) > 0): ?>
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover table-striped mb-0 align-middle">
        <thead class="table-dark">
          <tr>
            <th style="width:80px;">ID</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th style="width:160px;">Nacimiento</th>
            <th>Nacionalidad</th>
            <th style="width:220px;" class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($directores as $d): ?>
            <tr>
              <td><?= $d->getId() ?></td>
              <td><?= htmlspecialchars($d->getNombre()) ?></td>
              <td><?= htmlspecialchars($d->getApellidos()) ?></td>
              <td><?= formatDateDMY($d->getFechaNacimiento()) ?></td>
              <td><?= htmlspecialchars($d->getNacionalidad()) ?></td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-secondary" href="edit.php?id=<?= $d->getId() ?>">Editar</a>

                <form method="post" action="delete.php" class="d-inline"
                      onsubmit="return confirm('¿Seguro que deseas borrar este director?');">
                  <input type="hidden" name="id" value="<?= $d->getId() ?>">
                  <button type="submit" class="btn btn-sm btn-outline-danger">Borrar</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php else: ?>
  <div class="alert alert-info">No existe todavía ningún director.</div>
<?php endif; ?>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
