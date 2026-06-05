<?php

require_once __DIR__ . '/../Repository/PatientRepository.php';

class PatientController {
    private $repository;

    public function __construct($pdo) {
        $this->repository = new PatientRepository($pdo);
    }

    public function handlePrendreRDV() {
        if (session_status() === PHP_SESSION_NONE) { 
            session_start(); 
        }

      
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
            header('Location: ../auth/login.php');
            exit();
        }

        $patient_id = $_SESSION['user_id'];
        $message_success = "";
        $message_error = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmer_creneau'])) {
            $id_creneau = $_POST['id_creneau'];
            $medecin_id = $_POST['id_medecin'];

            if (!empty($id_creneau) && !empty($medecin_id)) {
                $is_saved = $this->repository->reserverRendezVous($patient_id, $medecin_id, $id_creneau);
                if ($is_saved) {
                    $message_success = "Félicitations ! Votre rendez-vous a bien été enregistré.";
                } else {
                    $message_error = "Une erreur est survenue lors de la réservation.";
                }
            }
        }

        $les_medecins = $this->repository->getAllMedecins();

      
        return [
            'les_medecins' => $les_medecins,
            'message_success' => $message_success,
            'message_error' => $message_error,
            'repository' => $this->repository 
        ];
    }

    public function handleDashboard() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

  
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
            header('Location: ../auth/login.php');
            exit();
        }

        $patient_id = $_SESSION['user_id'];
        $patient_nom = $_SESSION['nom'];

        $rendez_vous = [];

        $mes_rdv = $this->repository->getRendezVousByPatient($patient_id);

  
        foreach ($mes_rdv as $rdv) {
            $creneau = $this->repository->getCreneauById($rdv['id_creneau']);
            $medecin = $this->repository->getMedecinDetailsById($rdv['id_medecin']);

            if ($creneau && $medecin) {
                $rendez_vous[] = [
                    'id' => $rdv['id'],
                    'statut' => $rdv['statut'],
                    'date_creneau' => $creneau['date_creneau'],
                    'heure_debut' => $creneau['heure_debut'],
                    'medecin_nom' => $medecin['nom'],
                    'description' => $medecin['description'],
                    'specialite' => $medecin['specialite']
                    
                ];
            }
        }

      
        $count_rdv = count($rendez_vous);
        $count_ordonnances = $this->repository->countOrdonnancesByPatient($patient_id);

        return [
            'patient_nom' => $patient_nom,
            'rendez_vous' => $rendez_vous,
            'count_rdv' => $count_rdv,
            'count_ordonnances' => $count_ordonnances
        ];
    }

}