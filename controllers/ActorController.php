<?php
require_once __DIR__ . "/../models/Actor.php";

class ActorController
{
    public function listActores()
    {
        $model = new Actor();
        return $model->getAll();
    }

    public function createActor($nombre, $apellidos, $fechaNacimiento)
    {
        $model = new Actor();
        return $model->create($nombre, $apellidos, $fechaNacimiento);
    }

    public function getActor($id)
    {
        $model = new Actor();
        return $model->getById($id);
    }

    public function updateActor($id, $nombre, $apellidos, $fechaNacimiento)
    {
        $model = new Actor();
        return $model->update($id, $nombre, $apellidos, $fechaNacimiento);
    }

    public function deleteActor($id)
    {
        $model = new Actor();
        return $model->delete($id);
    }
}
