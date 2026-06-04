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
    }
}