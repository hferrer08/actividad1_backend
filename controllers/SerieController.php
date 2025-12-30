<?php
require_once __DIR__ . "/../models/Serie.php";
require_once __DIR__ . "/../models/Plataforma.php";
require_once __DIR__ . "/../models/Actor.php";
require_once __DIR__ . "/../models/Idioma.php";

class SerieController
{
    public function listSeries()
    {
        $model = new Serie();
        return $model->getAll();
    }

    public function getSerie($id)
    {
        $model = new Serie();
        return $model->getById($id);
    }

    public function createSerie($titulo, $sinopsis, $anio, $temporadas, $plataformas, $actores, $idiomasAudio, $idiomasSub)
    {
        $model = new Serie();

        return $model->createFull([
            "titulo" => $titulo,
            "sinopsis" => $sinopsis,
            "anio" => $anio,
            "temporadas" => $temporadas,
            "plataformas" => $plataformas,
            "actores" => $actores,
            "idiomas_audio" => $idiomasAudio,
            "idiomas_sub" => $idiomasSub,
        ]);
    }

    public function updateSerie($id, $titulo, $sinopsis, $anio, $temporadas, $plataformas, $actores, $idiomasAudio, $idiomasSub)
    {
        $model = new Serie();

        return $model->updateFull((int)$id, [
            "titulo" => $titulo,
            "sinopsis" => $sinopsis,
            "anio" => $anio,
            "temporadas" => $temporadas,
            "plataformas" => $plataformas,
            "actores" => $actores,
            "idiomas_audio" => $idiomasAudio,
            "idiomas_sub" => $idiomasSub,
        ]);
    }

    public function deleteSerie($id)
    {
        $model = new Serie();
        return $model->delete((int)$id);
    }

    // Catálogos (para los multiselect)
    public function listPlataformas()
    {
        $m = new Plataforma();
        return $m->getAll(); // devuelve objetos Plataforma
    }

    public function listActores()
    {
        $m = new Actor();
        return $m->getAll(); // devuelve objetos Actor
    }

    public function listIdiomas()
    {
        $m = new Idioma();
        return $m->getAll(); // devuelve objetos Idioma
    }

    // IDs seleccionados por Serie (para precargar edit)
    public function getPlataformaIdsBySerie($serieId): array
    {
        $m = new Serie();
        return $m->getPlataformaIdsBySerie((int)$serieId);
    }

    public function getActorIdsBySerie($serieId): array
    {
        $m = new Serie();
        return $m->getActorIdsBySerie((int)$serieId);
    }

    public function getAudioIdiomaIdsBySerie($serieId): array
    {
        $m = new Serie();
        return $m->getAudioIdiomaIdsBySerie((int)$serieId);
    }

    public function getSubIdiomaIdsBySerie($serieId): array
    {
        $m = new Serie();
        return $m->getSubIdiomaIdsBySerie((int)$serieId);
    }
}
