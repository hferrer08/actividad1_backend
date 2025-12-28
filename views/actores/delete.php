<?php
require_once __DIR__ . "/../../controllers/ActorController.php";
$controller = new ActorController();

if (!isset($_POST["id"])) {
    header("Location: list.php");
    exit;
}

$id = (int)$_POST["id"];
$controller->deleteActor($id);

header("Location: list.php");
exit;
