<?php
class RendezVousRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

  
    public function trouverRendezVousActifs($id_user_medecin) {
        try {
            
         
            $sql = "SELECT 
                        r.id, 
                        r.statut, 
                        c.date_creneau, 
                        c.heure_debut, 
                        u.nom AS patient_nom
                    FROM rendez_vous r
                    INNER JOIN creneaux c ON r.id_creneau = c.id
                    INNER JOIN medecins m ON r.id_medecin = m.id
                    INNER JOIN users u ON r.id_patient = u.id
                    WHERE m.id_user = ? AND r.statut IN ('En attente', 'Confirme')
                    ORDER BY c.date_creneau ASC, c.heure_debut ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_user_medecin]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    
    public function trouverRendezVousPasses($id_user_medecin) {
        try {
            $sql = "SELECT 
                        r.id, 
                        r.statut, 
                        c.date_creneau, 
                        c.heure_debut, 
                        u.nom AS patient_nom
                    FROM rendez_vous r
                    INNER JOIN creneaux c ON r.id_creneau = c.id
                    INNER JOIN medecins m ON r.id_medecin = m.id
                    INNER JOIN users u ON r.id_patient = u.id
                    WHERE m.id_user = ? AND r.statut IN ('Termine', 'Annule')
                    ORDER BY c.date_creneau DESC, c.heure_debut DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id_user_medecin]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }


    public function modifierStatut($id_rdv, $nouveau_statut) {
        try {
            $stmt = $this->pdo->prepare("UPDATE rendez_vous SET statut = ? WHERE id = ?");
            return $stmt->execute([$nouveau_statut, $id_rdv]);
        } catch (Exception $e) {
            return false;
        }
    }
}