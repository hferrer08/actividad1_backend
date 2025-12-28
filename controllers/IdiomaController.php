<?php
require_once __DIR__ . "/../models/Idioma.php";

class IdiomaController
{
    public function listIdiomas()
    {
        $model = new Idioma();
        return $model->getAll();
    }

    public function createIdioma($nombre, $codigo)
    {
        $model = new Idioma();
        return $model->create($nombre, $codigo);
    }

    public function getIdioma($id)
    {
        $model = new Idioma();
        return $model->getById($id);
    }

    public function updateIdioma($id, $nombre, $codigo)
    {
        $model = new Idioma();
        return $model->update($id, $nombre, $codigo);
    }

    public function deleteIdioma($id)
    {
        $model = new Idioma();
        return $model->delete($id);
    }
}
