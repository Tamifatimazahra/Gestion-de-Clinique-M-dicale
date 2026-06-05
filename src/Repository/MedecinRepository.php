<?php
class MedecinRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function getTopMedecins($limit = 3) {
        try {
            $sql = "SELECT u.id, u.nom, s.nom as specialite
                    FROM users u 
                    INNER JOIN medecins m ON u.id = m.id_user 
                    INNER JOIN specialites s ON s.id = m.id_specialite
                    WHERE u.role = 'medecin' 
                    LIMIT :limit";
                    
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            
            return [];
        }
    }
    public function ajouterCreneau($id_medecin, $date_creneau, $heure_debut) {
        try {
          
            $check = $this->pdo->prepare("SELECT id FROM creneaux WHERE id_medecin = ? AND date_creneau = ? AND heure_debut = ?");
            $check->execute([$id_medecin, $date_creneau, $heure_debut]);
            if ($check->fetch()) {
                return "existe";
            }

        
            $sql = "INSERT INTO creneaux (id_medecin, date_creneau, heure_debut, disponible) VALUES (?, ?, ?, 1)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id_medecin, $date_creneau, $heure_debut]);
        } catch (Exception $e) {
            return false;
        }
    }

    
    public function getCreneauxByMedecin($id_medecin) {
        try {
            $sql = "SELECT * FROM creneaux WHERE id_medecin = ? ORDER BY date_creneau DESC, heure_debut ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_medecin]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
}