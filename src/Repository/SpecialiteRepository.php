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
    public function findById($id){
        $query = "SELECT * FROM specialites WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
}