<?php

require_once __DIR__ . '/../config/database.php';

abstract class DBconnect
{
    protected $db;

    public function __construct()
    
    {

      $this->db = Database::connect();

       
    }

    
}
