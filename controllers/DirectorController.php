<?php
require_once __DIR__ . "/../models/Director.php";

class DirectorController
{
    public function listDirectores()
    {
        $model = new Director();
        return $model->getAll();
    }

    public function createDirector($nombre, $apellidos, $fechaNacimiento, $nacionalidad)
    {
        $model = new Director();
        return $model->create($nombre, $apellidos, $fechaNacimiento, $nacionalidad);
    }

    public function getDirector($id)
    {
        $model = new Director();
        return $model->getById($id);
    }

    public function updateDirector($id, $nombre, $apellidos, $fechaNacimiento, $nacionalidad)
    {
        $model = new Director();
        return $model->update($id, $nombre, $apellidos, $fechaNacimiento, $nacionalidad);
    }

    public function deleteDirector($id)
    {
        $model = new Director();
        return $model->delete($id);
    }
}
