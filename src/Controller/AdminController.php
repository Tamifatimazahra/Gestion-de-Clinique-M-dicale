<?php

require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/src/Repository/SpecialiteRepository.php';
require_once dirname(__DIR__, 2) . '/src/Repository/UserRepository.php';
require_once dirname(__DIR__, 2) . '/src/Repository/RendezVousRepository.php';

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
        
        require_once dirname(__DIR__, 2) . '/templates/admin/dashboard_view.php';
    }

    public function gererMedecins(){
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_medecin'){
            $nom = trim($_POST['nom']);
            $prenom = trim($_POST['prenom']);
            $email = trim($_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Chiffrage mt-7an
            $id_specialite = intval($_POST['id_specialite']);

            if(!empty($nom) && !empty($prenom) && !empty($email) && !empty($id_specialite)){
                $this->userRepo->createMedecin($nom, $prenom, $email, $password, $id_specialite);
                header('Location: medcins.php');
                exit();
            }
        }

        if(isset($_GET['action']) && $_GET['action'] === 'toggle_status' && isset($_GET['id']) && isset($_GET['status'])){
            $medecinId = intval($_GET['id']);
            $currentStatus = intval($_GET['status']);
            $newStatus = ($currentStatus === 1) ? 0 : 1;

            $this->userRepo->toggleMedecinStatus($medecinId, $newStatus);
            header('Location: medcins.php');
            exit();
        }

        $medecins = $this->userRepo->findAllMedecins();
        $specialites = $this->specialiteRepo->findAll();

        require_once dirname(__DIR__, 2) . '/templates/admin/medcins_view.php';
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
}