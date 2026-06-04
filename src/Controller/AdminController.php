<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Repository/SpecialiteRepository.php';
require_once __DIR__ . '/../Repository/UserRepository.php';
require_once __DIR__ . '/../Repository/RendezVousRepository.php';

class AdminController {
    private $specialiteRepo;
    private $userRepo;
    private $rendezVousRepo;

    public function __construct(){
        $database = new Database();
        $dbConn = $database->getConnection();

        $this->specialiteRepo = new SpecialiteRepository($dbConn);
        $this->userRepo = new UserRepository($dbConn);
        $this->rendezVousRepo = new RendezVousRepository($dbConn);
    }
    public function afficherDashboard(){
        $stats_global = $this->rendezVousRepo->getGlobalKPIs();
        $medecins_stats = $this->rendezVousRepo->getMedecinsPerformance();
        require_once __DIR__ . '/../../templates/admin/dashboard.php';
    }

    public function gererSpecialites(){
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_specialite'){
            $nom = trim($_POST['nom']);
            $description = trim($_POST['description']);

            if (!empty($nom)){
                $this->specialiteRepo->create($nom, $description);
                header('Location: specialites.php');
                exit();
            }
        }
    }
}