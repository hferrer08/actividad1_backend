<?php
require_once __DIR__ . "/../models/Actor.php";
require_once __DIR__ . "/../helpers/Validator.php";

class ActorController
{
    public function listActores()
    {
        $model = new Actor();
        return $model->getAll();
    }

    public function createActor($nombre, $apellidos, $fechaNacimiento, $nacionalidad)
    {   
        $nombre = Validator::required($nombre, "nombre", 100, 2);
        $apellidos = Validator::required($apellidos, "apellidos", 120, 2);
        $fechaNacimiento = Validator::optionalDate($fechaNacimiento, "fecha de nacimiento");
        $nacionalidad = Validator::optionalString($nacionalidad, "nacionalidad", 100, false); // false, guarda "" si viene vacío

        $model = new Actor();
        return $model->create($nombre, $apellidos, $fechaNacimiento,  $nacionalidad);
    }

    public function getActor($id)
    {
         $id = Validator::id($id, "id");
        $model = new Actor();
        return $model->getById($id);
    }

    public function updateActor($id, $nombre, $apellidos, $fechaNacimiento, $nacionalidad)
    {
        $id = Validator::id($id, "id");
        $nombre = Validator::required($nombre, "nombre", 100, 2);
        $apellidos = Validator::required($apellidos, "apellidos", 120, 2);
        $fechaNacimiento = Validator::optionalDate($fechaNacimiento, "fecha de nacimiento");
        $nacionalidad = Validator::optionalString($nacionalidad, "nacionalidad", 100, false);

        $model = new Actor();
        return $model->update($id, $nombre, $apellidos, $fechaNacimiento, $nacionalidad);
    }

    public function deleteActor($id)
    {
        $id = Validator::id($id, "id");
        $model = new Actor();
        return $model->delete($id);
    }
}
