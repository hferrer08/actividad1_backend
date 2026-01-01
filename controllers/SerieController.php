<?php
require_once __DIR__ . "/../models/Serie.php";
require_once __DIR__ . "/../models/Plataforma.php";
require_once __DIR__ . "/../models/Actor.php";
require_once __DIR__ . "/../models/Idioma.php";
require_once __DIR__ . "/../models/Director.php";
require_once __DIR__ . "/../helpers/Validator.php";

class SerieController
{
    public function listSeries()
    {
        $model = new Serie();
        return $model->getAll();
    }

    public function getSerie($id)
    {
        $id = Validator::id($id, "id");
        $model = new Serie();
        return $model->getById($id);
    }

    public function createSerie($titulo, $sinopsis, $anio, $temporadas, $directorId, $plataformas, $actores, $idiomasAudio, $idiomasSub)
    {
        $titulo = Validator::required($titulo, "titulo", 150, 2);
        $sinopsis = Validator::optionalString($sinopsis, "sinopsis", 5000, true);

        $anio = Validator::yearOptional($anio, "anio");
        $temporadas = Validator::temporadasOptional($temporadas, "temporadas");

        $directorId = Validator::optionalInt($directorId, "director", 1, null, true);

        $plataformas = Validator::idArray($plataformas, "plataformas", true);
        $actores = Validator::idArray($actores, "actores", true);
        $idiomasAudio = Validator::idArray($idiomasAudio, "idiomas de audio", true);
        $idiomasSub = Validator::idArray($idiomasSub, "idiomas de subtítulos", true);

        $model = new Serie();
        return $model->createFull([
            "titulo" => $titulo,
            "sinopsis" => $sinopsis,
            "anio" => $anio,
            "temporadas" => $temporadas,
            "director_id" => $directorId,
            "plataformas" => $plataformas,
            "actores" => $actores,
            "idiomas_audio" => $idiomasAudio,
            "idiomas_sub" => $idiomasSub,
        ]);
    }

    public function updateSerie($id, $titulo, $sinopsis, $anio, $temporadas, $directorId, $plataformas, $actores, $idiomasAudio, $idiomasSub)
    {
        $id = Validator::id($id, "id");

        $titulo = Validator::required($titulo, "titulo", 150, 2);
        $sinopsis = Validator::optionalString($sinopsis, "sinopsis", 5000, true);

        $anio = Validator::yearOptional($anio, "anio");
        $temporadas = Validator::temporadasOptional($temporadas, "temporadas");

        $directorId = Validator::optionalInt($directorId, "director", 1, null, true);

        $plataformas = Validator::idArray($plataformas, "plataformas", true);
        $actores = Validator::idArray($actores, "actores", true);
        $idiomasAudio = Validator::idArray($idiomasAudio, "idiomas de audio", true);
        $idiomasSub = Validator::idArray($idiomasSub, "idiomas de subtítulos", true);

        $model = new Serie();
        return $model->updateFull($id, [
            "titulo" => $titulo,
            "sinopsis" => $sinopsis,
            "anio" => $anio,
            "temporadas" => $temporadas,
            "director_id" => $directorId,
            "plataformas" => $plataformas,
            "actores" => $actores,
            "idiomas_audio" => $idiomasAudio,
            "idiomas_sub" => $idiomasSub,
        ]);
    }

    public function deleteSerie($id)
    {
        $id = Validator::id($id, "id");
        $model = new Serie();
        return $model->delete($id);
    }

    // Catálogos
    public function listPlataformas()
    {
        $m = new Plataforma();
        return $m->getAll();
    }

    public function listActores()
    {
        $m = new Actor();
        return $m->getAll();
    }

    public function listDirectores()
    {
        $m = new Director();
        return $m->getAll();
    }

    public function listIdiomas()
    {
        $m = new Idioma();
        return $m->getAll();
    }

    // IDs seleccionados por Serie
    public function getPlataformaIdsBySerie($serieId): array
    {
        $serieId = Validator::id($serieId, "serieId");
        $m = new Serie();
        return $m->getPlataformaIdsBySerie($serieId);
    }

    public function getActorIdsBySerie($serieId): array
    {
        $serieId = Validator::id($serieId, "serieId");
        $m = new Serie();
        return $m->getActorIdsBySerie($serieId);
    }

    public function getAudioIdiomaIdsBySerie($serieId): array
    {
        $serieId = Validator::id($serieId, "serieId");
        $m = new Serie();
        return $m->getAudioIdiomaIdsBySerie($serieId);
    }

    public function getSubIdiomaIdsBySerie($serieId): array
    {
        $serieId = Validator::id($serieId, "serieId");
        $m = new Serie();
        return $m->getSubIdiomaIdsBySerie($serieId);
    }
}
