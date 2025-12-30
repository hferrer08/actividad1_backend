<?php
require_once __DIR__ . "/../../controllers/SerieController.php";
$controller = new SerieController();
$series = $controller->listSeries();

$title = "Series - Listado";
require_once __DIR__ . "/../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Series</h1>
  <a class="btn btn-primary" href="create.php">+ Nueva serie</a>
</div>

<?php if ($series && count($series) > 0): ?>
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-hover table-striped mb-0 align-middle">
        <thead class="table-dark">
          <tr>
            <th style="width:80px;">ID</th>
            <th>Título</th>
            <th style="width:120px;">Año</th>
            <th style="width:140px;">Temporadas</th>
            <th style="width:220px;" class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($series as $s): ?>
            <tr>
              <td><?= (int)$s["id"] ?></td>
              <td><?= htmlspecialchars($s["titulo"]) ?></td>
              <td><?= htmlspecialchars($s["anio"] ?? "-") ?></td>
              <td><?= htmlspecialchars($s["temporadas"] ?? "-") ?></td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-secondary" href="edit.php?id=<?= (int)$s["id"] ?>">Editar</a>

                <form method="post" action="delete.php" class="d-inline"
                      onsubmit="return confirm('¿Seguro que deseas borrar esta serie? Se borrarán también sus relaciones.');">
                  <input type="hidden" name="id" value="<?= (int)$s["id"] ?>">
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
  <div class="alert alert-info">No existe todavía ninguna serie.</div>
<?php endif; ?>

<?php require_once __DIR__ . "/../partials/footer.php"; ?>
