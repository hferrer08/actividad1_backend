<?php
// index.php (HOME)
require_once __DIR__ . "/views/partials/header.php";
?>

<main class="container my-4">

  <section class="p-4 p-md-5 rounded-4 text-white mb-4"
           style="background: linear-gradient(135deg, #0d6efd, #6f42c1);">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-md-center">
      <div>
        <h1 class="display-6 fw-bold mb-2">HOME</h1>
        <p class="mb-0">
          Acceso rápido a <strong>listados</strong> y <strong>formularios</strong> de cada módulo.
        </p>
      </div>
      <div class="d-flex gap-2 flex-wrap">
        <span class="badge rounded-pill px-3 py-2"
              style="background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.25);">MVC</span>
        <span class="badge rounded-pill px-3 py-2"
              style="background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.25);">PDO</span>
        <span class="badge rounded-pill px-3 py-2"
              style="background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.25);">MySQL</span>
      </div>
    </div>
  </section>

  <div class="row g-3">

    <!-- Plataformas -->
    <div class="col-12 col-md-6 col-lg-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title mb-1">Plataformas</h5>
          <p class="text-muted small mb-3">Administración de plataformas de streaming.</p>
          <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-primary btn-sm" href="views/plataformas/list.php">Ver listado</a>
            <a class="btn btn-outline-primary btn-sm" href="views/plataformas/create.php">Crear</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Directores -->
    <div class="col-12 col-md-6 col-lg-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title mb-1">Directores</h5>
          <p class="text-muted small mb-3">Gestión de directores para series.</p>
          <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-primary btn-sm" href="views/directores/list.php">Ver listado</a>
            <a class="btn btn-outline-primary btn-sm" href="views/directores/create.php">Crear</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Actores -->
    <div class="col-12 col-md-6 col-lg-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title mb-1">Actores</h5>
          <p class="text-muted small mb-3">Gestión del elenco (relación con series).</p>
          <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-primary btn-sm" href="views/actores/list.php">Ver listado</a>
            <a class="btn btn-outline-primary btn-sm" href="views/actores/create.php">Crear</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Idiomas -->
    <div class="col-12 col-md-6 col-lg-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title mb-1">Idiomas</h5>
          <p class="text-muted small mb-3">Idiomas para audio y subtítulos.</p>
          <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-primary btn-sm" href="views/idiomas/list.php">Ver listado</a>
            <a class="btn btn-outline-primary btn-sm" href="views/idiomas/create.php">Crear</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Series -->
    <div class="col-12 col-md-6 col-lg-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title mb-1">Series</h5>
          <p class="text-muted small mb-3">CRUD Series + relaciones.</p>
          <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-primary btn-sm" href="views/series/list.php">Ver listado</a>
            <a class="btn btn-outline-primary btn-sm" href="views/series/create.php">Crear</a>
          </div>
        </div>
      </div>
    </div>

  </div>

</main>

<?php
require_once __DIR__ . "/views/partials/footer.php";
?>
