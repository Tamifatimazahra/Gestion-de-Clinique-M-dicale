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
    public function create($nom, $description){
        $query = "INSERT INTO specialites (nom, description) VALUES (:nom, :description)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':nom' => $nom,
            ':description' => $description
        ]);
    }
    
    public function update($id, $nom, $description){
        $query = "UPDATE specialites SET nom = :nom, description = :description WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':description' => $description
        ]);
    }

    public function delete($id){
        $query = "DELETE FROM specialites WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}