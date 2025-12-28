<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plataformas</title>
</head>
<body>

<h1>Listado de Plataformas</h1>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>URL</th>
    </tr>

    <?php foreach ($plataformas as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= $p['nombre'] ?></td>
            <td><?= $p['descripcion'] ?></td>
            <td><?= $p['url'] ?></td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>