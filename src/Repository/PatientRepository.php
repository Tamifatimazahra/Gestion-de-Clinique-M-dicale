<?php
class PatientRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    
   public function getAllMedecins() {
    try {
     
        $sql = "SELECT m.id, u.nom, s.nom as specialite, s.description as description
                FROM users u 
                INNER JOIN medecins m ON u.id = m.id_user
                INNER JOIN specialites s ON m.id_specialite = s.id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

    public function getCreneauxDisponibles($id_medecin, $date_db) {
        try {
            $sql = "SELECT id, heure_debut FROM creneaux 
                    WHERE id_medecin = ? AND date_creneau = ? AND disponible = 1 
                    ORDER BY heure_debut ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_medecin, $date_db]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function reserverRendezVous($patient_id, $medecin_id, $id_creneau) {
        try {
            $this->pdo->beginTransaction();

            $sql_ins = "INSERT INTO rendez_vous (id_patient, id_medecin, id_creneau, statut) VALUES (?, ?, ?, 'En attente')";
            $this->pdo->prepare($sql_ins)->execute([$patient_id, $medecin_id, $id_creneau]);

            $sql_up = "UPDATE creneaux SET disponible = 0 WHERE id = ?";
            $this->pdo->prepare($sql_up)->execute([$id_creneau]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    public function getOrdonnancesByPatient($patient_id) {
    try {
        $sql = "SELECT o.id, o.description, c.date_creneau, u.nom AS medecin_nom, s.nom AS specialite
                FROM ordonnances o
                INNER JOIN rendez_vous r ON o.id_rendez_vous = r.id
                INNER JOIN creneaux c ON r.id_creneau = c.id
                INNER JOIN medecins m ON r.id_medecin = m.id
                INNER JOIN users u ON m.id_user = u.id
                INNER JOIN specialites s ON m.id_specialite = s.id
                WHERE r.id_patient = ?
                ORDER BY c.date_creneau DESC";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$patient_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}
public function getRendezVousByPatient($patient_id) {
        try {
            $sql = "SELECT * FROM rendez_vous WHERE id_patient = ? ORDER BY id DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$patient_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }


    public function getCreneauById($id_creneau) {
        try {
            $stmt = $this->pdo->prepare("SELECT date_creneau, heure_debut FROM creneaux WHERE id = ?");
            $stmt->execute([$id_creneau]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }


    public function getMedecinDetailsById($id_medecin) {
        try {
            $sql = "SELECT u.nom,s.description as description ,s.nom as specialite 
                    FROM medecins m 
                    INNER JOIN users u ON m.id_user = u.id 
                    INNER JOIN specialites s ON m.id_specialite = s.id 
                    WHERE m.id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_medecin]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

   
    public function countOrdonnancesByPatient($patient_id) {
        try {
            $sql = "SELECT COUNT(*) 
                    FROM ordonnances o 
                    INNER JOIN rendez_vous r ON o.id_rendez_vous = r.id 
                    WHERE r.id_patient = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$patient_id]);
            return $stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
}