<?php
require_once __DIR__ . '/../Repository/OrdonnanceRepository.php';

class OrdonnanceController {
    private $repository;

    public function __construct($pdo) {
        $this->repository = new OrdonnanceRepository($pdo);
    }

    public function handleRedigerOrdonnance() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

   
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'medecin') {
            header('Location: ../auth/login.php');
            exit();
        }

      
        $id_rdv = isset($_GET['id_rdv']) ? intval($_GET['id_rdv']) : 0;
        $message = "";


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $description = trim($_POST['description'] ?? '');
            $id_rdv_post = intval($_POST['id_rendez_vous'] ?? 0);

            if (!empty($description) && $id_rdv_post > 0) {
                try {
                    $result = $this->repository->enregistrerEtCloturer($id_rdv_post, $description);
                    if ($result) {
                        header('Location: dashboard.php');
                        exit();
                    }
                } catch (Exception $e) {
                    $message = "Erreur lors de l'enregistrement : " . $e->getMessage();
                }
            } else {
                $message = "Veuillez rédiger la description de l'ordonnance.";
            }
        }

        return [
            'id_rdv' => $id_rdv,
            'message' => $message
        ];
    }
}