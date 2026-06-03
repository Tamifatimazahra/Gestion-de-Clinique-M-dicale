<?php

class SpecialiteRepository {
    private $db;

    public function __construct($databaseConnection){
        $this->db = $databaseConnection;

    }
    public function findAll(){
        $query = "SELECT * FROM specialites";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}