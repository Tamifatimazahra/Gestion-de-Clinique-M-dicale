<?php

class UserRepository{
    private $db;

    public function __construct($databaseConnection){
        $this->db = $databaseConnection;
    }

    piblic function findAllMedecins(){
        $query = "SELECT u.id AS user_id, m.id AS medecin_id, u.nom, u.prenom, u.email, m.actif, s.nom AS specialite
            FROM medecins m
            JOIN users u ON m.id_user = u.id
            JOIN specialites s ON m.id_specialite = s.id
            ORDER BY u.id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}