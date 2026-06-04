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
    }
}