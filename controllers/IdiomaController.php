<?php
require_once __DIR__ . "/../models/Idioma.php";
require_once __DIR__ . "/../helpers/Validator.php";

class IdiomaController
{
    public function listIdiomas()
    {
        $model = new Idioma();
        return $model->getAll();
    }

    public function createIdioma($nombre, $codigo)
    {
        $nombre = Validator::required($nombre, "nombre", 80, 2);

        // codigo: requerido, max 10, sin espacios, minúscula
        $codigo = Validator::required($codigo, "codigo", 10, 2);
        $codigo = strtolower(trim($codigo));
        $codigo = preg_replace('/\s+/', '', $codigo);
        $model = new Idioma();
        return $model->create($nombre, $codigo);
    }

    public function getIdioma($id)
    {
        $id = Validator::id($id, "id");
        $model = new Idioma();
        return $model->getById($id);
    }

    public function updateIdioma($id, $nombre, $codigo)
    {
        $id = Validator::id($id, "id");

        $nombre = Validator::required($nombre, "nombre", 80, 2);

        $codigo = Validator::required($codigo, "codigo", 10, 2);
        $codigo = strtolower(trim($codigo));
        $codigo = preg_replace('/\s+/', '', $codigo);
        $model = new Idioma();
        return $model->update($id, $nombre, $codigo);
    }

    public function deleteIdioma($id)
    {
        $id = Validator::id($id, "id");
        $model = new Idioma();
        return $model->delete($id);
    }
}
