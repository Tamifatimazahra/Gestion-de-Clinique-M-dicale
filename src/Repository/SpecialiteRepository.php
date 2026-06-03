<?php

class SpecialiteRepository {
    private $db;

    public function __construct($databaseConnection){
        $this->db = $databaseConnection;
        
    }
}