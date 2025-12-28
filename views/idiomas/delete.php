<?php
require_once __DIR__ . "/../../controllers/IdiomaController.php";
$controller = new IdiomaController();

if (!isset($_POST["id"])) {
    header("Location: list.php");
    exit;
}

$id = (int)$_POST["id"];
$controller->deleteIdioma($id);

header("Location: list.php");
exit;
