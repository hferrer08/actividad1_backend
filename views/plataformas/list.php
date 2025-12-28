<?php
require_once __DIR__ . "/../../controllers/PlataformaController.php";
$controller = new PlataformaController();
$plataformas = $controller->listPlataformas();
?>

<h1>Listado de plataformas</h1>

<a href="create.php">Crear plataforma</a>

<?php if ($plataformas && count($plataformas) > 0): ?>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($plataformas as $p): ?>
    <tr>
        <td><?= $p->getId() ?></td>
        <td><?= $p->getNombre() ?></td>
        <td>
            <a href="edit.php?id=<?= $p->getId() ?>">Editar</a>

            <form method="post" action="delete.php" style="display:inline;">
                <input type="hidden" name="id" value="<?= $p->getId() ?>">
                <button type="submit">Borrar</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <p>No existe todavía ninguna plataforma.</p>
<?php endif; ?>
