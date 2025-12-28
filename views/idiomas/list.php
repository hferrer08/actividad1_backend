<?php
require_once __DIR__ . "/../../controllers/IdiomaController.php";
$controller = new IdiomaController();
$idiomas = $controller->listIdiomas();

$title = "Idiomas - Listado";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Idiomas</h1>
  <a class="btn btn-primary" href="create.php">+ Nuevo idioma</a>
</div>

<?php if ($idiomas && count($idiomas) > 0): ?>
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover table-striped mb-0 align-middle">
        <thead class="table-dark">
          <tr>
            <th style="width:80px;">ID</th>
            <th>Nombre</th>
            <th style="width:120px;">Código</th>
            <th style="width:220px;" class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($idiomas as $i): ?>
            <tr>
              <td><?= $i->getId() ?></td>
              <td><?= htmlspecialchars($i->getNombre()) ?></td>
              <td><span class="badge bg-secondary"><?= htmlspecialchars($i->getCodigo()) ?></span></td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-secondary" href="edit.php?id=<?= $i->getId() ?>">Editar</a>

                <form method="post" action="delete.php" class="d-inline"
                      onsubmit="return confirm('¿Seguro que deseas borrar este idioma?');">
                  <input type="hidden" name="id" value="<?= $i->getId() ?>">
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
  <div class="alert alert-info">No existe todavía ningún idioma.</div>
<?php endif; ?>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
