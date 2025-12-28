<?php
require_once __DIR__ . "/../../controllers/ActorController.php";
$controller = new ActorController();
$actores = $controller->listActores();

$title = "Actores - Listado";
require_once __DIR__ . "/../partials/header.php";

function formatDateDMY($date) {
    if (!$date) return "-";
    $ts = strtotime($date);
    return $ts ? date("d/m/Y", $ts) : "-";
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Actores</h1>
  <a class="btn btn-primary" href="create.php">+ Nuevo actor</a>
</div>

<?php if ($actores && count($actores) > 0): ?>
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover table-striped mb-0 align-middle">
        <thead class="table-dark">
          <tr>
            <th style="width:80px;">ID</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th style="width:160px;">Nacimiento</th>
            <th style="width:220px;" class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($actores as $a): ?>
            <tr>
              <td><?= $a->getId() ?></td>
              <td><?= htmlspecialchars($a->getNombre()) ?></td>
              <td><?= htmlspecialchars($a->getApellidos()) ?></td>
              <td><?= formatDateDMY($a->getFechaNacimiento()) ?></td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-secondary" href="edit.php?id=<?= $a->getId() ?>">Editar</a>

                <form method="post" action="delete.php" class="d-inline"
                      onsubmit="return confirm('¿Seguro que deseas borrar este actor?');">
                  <input type="hidden" name="id" value="<?= $a->getId() ?>">
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
  <div class="alert alert-info">No existe todavía ningún actor.</div>
<?php endif; ?>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
