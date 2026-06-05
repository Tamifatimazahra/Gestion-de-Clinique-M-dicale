<?php

class CreneauRepository {
    private $db;

    public function __construct($dbConn) {
        $this->db = $dbConn;
    }

    public function create($id_medecin, $date_creneau, $heure_debut, $heure_fin) {
        $query = "INSERT INTO creneaux (id_medecin, date_creneau, heure_debut, heure_fin, disponible) 
                  VALUES (:id_medecin, :date_creneau, :heure_debut, :heure_fin, 1)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':id_medecin'   => $id_medecin,
            ':date_creneau' => $date_creneau,
            ':heure_debut'  => $heure_debut,
            ':heure_fin'    => $heure_fin
        ]);
    }

    public function findAllWithMedecin() {
        $query = "SELECT c.*, u.nom, u.prenom, s.nom as specialite
                  FROM creneaux c 
                  JOIN medecins m ON c.id_medecin = m.id
                  JOIN users u ON m.id_user = u.id
                  JOIN specialites s ON m.id_specialite = s.id
                  ORDER BY c.date_creneau DESC, c.heure_debut ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}