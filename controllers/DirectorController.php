<?php
require_once __DIR__ . "/../models/Director.php";

class DirectorController
{
    public function listDirectores()
    {
        $model = new Director();
        return $model->getAll();
    }

    public function createDirector($nombre, $apellidos, $fechaNacimiento)
    {
        $model = new Director();
        return $model->create($nombre, $apellidos, $fechaNacimiento);
    }

    public function getDirector($id)
    {
        $model = new Director();
        return $model->getById($id);
    }

    public function updateDirector($id, $nombre, $apellidos, $fechaNacimiento)
    {
        $model = new Director();
        return $model->update($id, $nombre, $apellidos, $fechaNacimiento);
    }

    public function deleteDirector($id)
    {
        $model = new Director();
        return $model->delete($id);
    }
}
