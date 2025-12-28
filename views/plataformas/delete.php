<?php
require_once __DIR__ . "/../../controllers/PlataformaController.php";
$controller = new PlataformaController();

if (!isset($_POST["id"])) {
  header("Location: list.php");
  exit;
}

$id = (int)$_POST["id"];
$controller->deletePlataforma($id);

header("Location: list.php");
exit;
