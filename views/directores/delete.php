<?php
require_once __DIR__ . "/../../controllers/DirectorController.php";
$controller = new DirectorController();

if (!isset($_POST["id"])) {
    header("Location: list.php");
    exit;
}

$id = (int)$_POST["id"];
$controller->deleteDirector($id);

header("Location: list.php");
exit;
