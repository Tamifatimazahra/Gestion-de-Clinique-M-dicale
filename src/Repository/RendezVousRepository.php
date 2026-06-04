<?php

class RendezVousRepository {
    private $db;

    public function __construct($databaseConnection){
        $this->db = $databaseConnection;
    }
}