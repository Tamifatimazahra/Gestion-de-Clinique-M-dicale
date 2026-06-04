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
}