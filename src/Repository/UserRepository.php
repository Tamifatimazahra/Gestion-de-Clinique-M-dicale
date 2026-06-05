<?php

class UserRepository{
    private $db;

    public function __construct($databaseConnection){
        $this->db = $databaseConnection;
    }

    public function findAllMedecins(){ 
        $query = "SELECT u.id AS user_id, m.id AS medecin_id, u.nom, u.prenom, u.email, m.actif, s.nom AS specialite
            FROM medecins m
            JOIN users u ON m.id_user = u.id
            JOIN specialites s ON m.id_specialite = s.id
            ORDER BY u.id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createMedecin($nom, $prenom, $email, $password, $id_specialite){
        try{
            $this->db->beginTransaction();

            $queryUser = "INSERT INTO users (nom, prenom, email, password, role) VALUES(:nom, :prenom, :email, :password, 'medecin')";
            $stmtUser = $this->db->prepare($queryUser);
            $stmtUser->execute([
                ':nom' => $nom,
                ':prenom' => $prenom,
                ':email' => $email,
                ':password' => $password
            ]);
            $idUser = $this->db->lastInsertId();

            $queryMedecin = "INSERT INTO medecins (id_user, id_specialite, actif) VALUES (:id_user, :id_specialite, TRUE)";
            $stmtMedecin = $this->db->prepare($queryMedecin);
            $stmtMedecin->execute([
                ':id_user' => $idUser,
                ':id_specialite' => $id_specialite
            ]);
            $this->db->commit();
            return true;
        }catch (Exception $e){
            $this->db->rollBack();
            return false;
        }
    }
    
    public function toggleMedecinStatus($medecinId, $status){
        $query = "UPDATE medecins SET actif = :status WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':status' => $status ? 1 : 0,
            ':id' => $medecinId
        ]);
    }
}