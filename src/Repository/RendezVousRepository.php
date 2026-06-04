<?php

class RendezVousRepository {
    private $db;

    public function __construct($databaseConnection){
        $this->db = $databaseConnection;
    }
    public function getGlobalKPIs(){
        $q1 = "SELECT ROUND ((COUNT(CASE WHERE statut = 'Annule' THEN 1 END) / COUNT(*)) * 100, 1) AS taux_annulation FROM rendez_vous";
        $stmt1 = $this->db->query($q1);
        $res1 = $stmt1->fetch();

        $q2 = "SELECT COUNT(*) AS total_medecins FROM medecins WHERE actif = TRUE";
        $stmt2 = $this->db->query($q2);
        $res2 = $stmt2->fetch();

        $q3 = "SELECT COUNT(*) AS total_patients FROM users WHERE role = 'patient'";
        $stmt3 = $this->db->query($q3);
        $res3 = $stmt3->fetch();

        $kpis = new stdClass();
        $kpis->taux_annulation = $res1->taux_annulation ?? 0;
        $kpis->total_medecins = $res2->total_medecins ?? 0;
        $kpis->total_patients = $res3->total_patients ?? 0;

        return $kpis;
    }

    public function getMedecinsPerformance(){
        $query = "SELECT 
                    u.nom AS medecin_nom, 
                    u.prenom AS medecin_prenom, 
                    s.nom AS specialite,
                    COUNT(r.id) AS total_rdv_termine
                  FROM rendez_vous r
                  JOIN medecins m ON r.id_medecin = m.id
                  JOIN users u ON m.id_user = u.id
                  JOIN specialites s ON m.id_specialite = s.id
                  WHERE r.statut = 'Terminé'
                  GROUP BY m.id, u.nom, u.prenom, s.nom
                  ORDER BY total_rdv_termine DESC";

        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }
}