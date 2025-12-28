<?php
require_once __DIR__ . "/../models/Plataforma.php";

class PlataformaController
{
    public function listPlataformas()
    {
        $model = new Plataforma();
        return $model->getAll();
    }

    public function createPlataforma($nombre)
    {
        $model = new Plataforma();
        return $model->create($nombre);
    }

    public function getPlataforma($id)
    {
        $model = new Plataforma();
        return $model->getById($id);
    }

    public function updatePlataforma($id, $nombre)
    {
        $model = new Plataforma();
        return $model->update($id, $nombre);
    }

    public function deletePlataforma($id)
    {
        $model = new Plataforma();
        return $model->delete($id);
    }
}
