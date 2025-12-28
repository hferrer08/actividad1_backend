<?php
require_once __DIR__ . "/../../controllers/PlataformaController.php";
$controller = new PlataformaController();
$plataformas = $controller->listPlataformas();

$title = "Plataformas - Listado";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Plataformas</h1>
  <a class="btn btn-primary" href="create.php">+ Nueva plataforma</a>
</div>

<?php if ($plataformas && count($plataformas) > 0): ?>
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover table-striped mb-0 align-middle">
        <thead class="table-dark">
          <tr>
            <th style="width: 80px;">ID</th>
            <th>Nombre</th>
            <th style="width: 220px;" class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($plataformas as $p): ?>
            <tr>
              <td><?= $p->getId() ?></td>
              <td><?= htmlspecialchars($p->getNombre()) ?></td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-secondary" href="edit.php?id=<?= $p->getId() ?>">Editar</a>

                <form method="post" action="delete.php" class="d-inline"
                      onsubmit="return confirm('¿Seguro que deseas borrar esta plataforma?');">
                  <input type="hidden" name="id" value="<?= $p->getId() ?>">
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
  <div class="alert alert-info">No existe todavía ninguna plataforma.</div>
<?php endif; ?>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
