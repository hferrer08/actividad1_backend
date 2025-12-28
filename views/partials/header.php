<?php
if (!isset($title)) $title = "Actividad 1";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($title) ?></title>

  <!-- Bootstrap 5 (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="/actividad1_backend/index.html">Actividad 1</a>
    <div class="navbar-nav">
      <a class="nav-link" href="/actividad1_backend/views/plataformas/list.php">Plataformas</a>
    </div>
  </div>
</nav>

<main class="container py-4">
