<?php
require_once __DIR__ . "/../models/Director.php";
require_once __DIR__ . "/../helpers/Validator.php";

class DirectorController
{
    public function listDirectores()
    {
        
        $model = new Director();
        return $model->getAll();
    }

    public function createDirector($nombre, $apellidos, $fechaNacimiento, $nacionalidad)
    {
        $nombre = Validator::required($nombre, "nombre", 100, 2);
        $apellidos = Validator::required($apellidos, "apellidos", 120, 2);
        $fechaNacimiento = Validator::optionalDate($fechaNacimiento, "fecha de nacimiento");
        $nacionalidad = Validator::optionalString($nacionalidad, "nacionalidad", 100, false);
        $model = new Director();
        return $model->create($nombre, $apellidos, $fechaNacimiento, $nacionalidad);
    }

    public function getDirector($id)
    {
        $id = Validator::id($id, "id");
        $model = new Director();
        return $model->getById($id);
    }

    public function updateDirector($id, $nombre, $apellidos, $fechaNacimiento, $nacionalidad)
    {
        $id = Validator::id($id, "id");
        $nombre = Validator::required($nombre, "nombre", 100, 2);
        $apellidos = Validator::required($apellidos, "apellidos", 120, 2);
        $fechaNacimiento = Validator::optionalDate($fechaNacimiento, "fecha de nacimiento");
        $nacionalidad = Validator::optionalString($nacionalidad, "nacionalidad", 100, false);
        $model = new Director();
        return $model->update($id, $nombre, $apellidos, $fechaNacimiento, $nacionalidad);
    }

    public function deleteDirector($id)
    {
        $id = Validator::id($id, "id");
        $model = new Director();
        return $model->delete($id);
    }
}
