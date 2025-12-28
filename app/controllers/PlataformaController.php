<?php
require_once __DIR__ . '/../models/Plataforma.php';

class PlataformaController
{
    public function index()
    {
        $plataforma = new Plataforma();
        $plataformas = $plataforma->getAll();

        require_once __DIR__ . '/../views/plataformas/index.php';
    }
}