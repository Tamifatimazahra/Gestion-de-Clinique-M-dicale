<?php

require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/src/Repository/SpecialiteRepository.php';
require_once dirname(__DIR__, 2) . '/src/Repository/UserRepository.php';
require_once dirname(__DIR__, 2) . '/src/Repository/RendezVousRepository.php';
require_once dirname(__DIR__, 2) . '/src/Repository/CreneauRepository.php'; 

class AdminController {
    private $specialiteRepo;
    private $userRepo;
    private $rendezVousRepo;
    private $creneauRepo; 

    public function __construct(){
        $database = new Database();
        $dbConn = $database->getConnection();

        $this->specialiteRepo = new SpecialiteRepository($dbConn);
        $this->userRepo = new UserRepository($dbConn);
        $this->rendezVousRepo = new RendezVousRepository($dbConn);
        $this->creneauRepo = new CreneauRepository($dbConn); 
    }
    
    public function afficherDashboard(){
        $stats_global = $this->rendezVousRepo->getGlobalKPIs();
        $medecins_stats = $this->rendezVousRepo->getMedecinsPerformance();
        
        require_once dirname(__DIR__, 2) . '/templates/admin/dashboard_view.php';
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

        if(isset($_GET['action']) && $_GET['action'] === 'delete_specialite' && isset($_GET['id'])){
            $id = intval($_GET['id']);
            $this->specialiteRepo->delete($id);
            header('Location: specialites.php');
            exit();
        }

        $specialites = $this->specialiteRepo->findAll();
        
        require_once dirname(__DIR__, 2) . '/templates/admin/specialites_view.php';
    }

    public function gererCreneaux(){
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_creneau'){
            $id_medecin   = $_POST['id_medecin'];
            $date_creneau = $_POST['date_creneau']; 
            $heure_debut  = $_POST['heure_debut'];  
            $heure_fin    = $_POST['heure_fin'];    

            if (!empty($id_medecin) && !empty($date_creneau) && !empty($heure_debut) && !empty($heure_fin)){
                $this->creneauRepo->create($id_medecin, $date_creneau, $heure_debut, $heure_fin);
                header('Location: creneaux.php');
                exit();
            }
        }

        $medecins = $this->userRepo->findAllMedecins();
        $creneaux = $this->creneauRepo->findAllWithMedecin();
        
        require_once dirname(__DIR__, 2) . '/templates/admin/creneaux_view.php';
    }
}