<?php
require_once __DIR__ . "/../../controllers/SerieController.php";
$controller = new SerieController();

if (!isset($_POST["id"])) {
    header("Location: list.php");
    exit;
}

$id = (int)$_POST["id"];
$controller->deleteSerie($id);

header("Location: list.php");
exit;
