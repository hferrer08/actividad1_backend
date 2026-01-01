<?php
require_once __DIR__ . "/../models/Plataforma.php";
require_once __DIR__ . "/../helpers/Validator.php";

class PlataformaController
{
    public function listPlataformas()
    {
        $model = new Plataforma();
        return $model->getAll();
    }

    public function createPlataforma($nombre)
    {
        $nombre = Validator::required($nombre, "nombre", 100, 2);
        $model = new Plataforma();
        return $model->create($nombre);
    }

    public function getPlataforma($id)
    {
        $id = Validator::id($id, "id");
        $model = new Plataforma();
        return $model->getById($id);
    }

    public function updatePlataforma($id, $nombre)
    {
         $id = Validator::id($id, "id");
         $nombre = Validator::required($nombre, "nombre", 100, 2);

        $model = new Plataforma();
        return $model->update($id, $nombre);
    }

    public function deletePlataforma($id)
    {
        $id = Validator::id($id, "id");
        $model = new Plataforma();
        return $model->delete($id);
    }
}
