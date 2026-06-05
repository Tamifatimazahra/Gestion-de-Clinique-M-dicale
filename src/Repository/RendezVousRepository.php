<?php

require_once __DIR__ . "/../../config/database.php";


class RendezVousRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findByMedecin(int $medecinId): array
    {
        $sql = "SELECT r.*, u.nom, u.prenom
                FROM rendez_vous r
                JOIN users u ON u.id = r.id_patient
                WHERE r.id_medecin = ?
                ORDER BY r.appointment_date ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$medecinId]);

        return $stmt->fetchAll();
    }
}

