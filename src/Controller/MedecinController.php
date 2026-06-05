<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclusions des dépôts (Repositories) et de la connexion DB
require_once __DIR__ . '/../Repository/MedecinRepository.php'; 
require_once __DIR__ . '/../Repository/RendezVousRepository.php';
require_once __DIR__ . '/../../config/database.php';

class MedecinController {
    private $repository;
    private $pdo;

    // Le constructeur attend impérativement l'objet $pdo transmis depuis la View
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->repository = new MedecinRepository($pdo);
    }
    
  
    public function handleGestionCreneaux() {

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'medecin') {
        header('Location: ../auth/login.php');
        exit();
    }

    $id_user = $_SESSION['user_id'];
    $message_success = "";
    $message_error = "";

   
    $stmt = $this->pdo->prepare("SELECT id FROM medecins WHERE id_user = ?");
    $stmt->execute([$id_user]);
    $medecin_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$medecin_data) {
        $message_error = "Erreur: Profil médecin introuvable.";
        return [
            'mes_creneaux' => [],
            'message_success' => '',
            'message_error' => $message_error
        ];
    }

    $id_medecin = $medecin_data['id']; 
    $_SESSION['medecin_id'] = $id_medecin;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_creneau'])) {
        $date_creneau = $_POST['date_creneau'];
        $heure_debut = $_POST['heure_debut'];

        if (!empty($date_creneau) && !empty($heure_debut)) {
            $result = $this->repository->ajouterCreneau($id_medecin, $date_creneau, $heure_debut);
            
            if ($result === true) {
                $message_success = "Le créneau horaire a été ajouté avec succès !";
            } elseif ($result === "existe") {
                $message_error = "Ce créneau horaire existe déjà dans votre planning.";
            } else {
                $message_error = "Une erreur est survenue lors de l'ajout.";
            }
        } else {
            $message_error = "Veuillez remplir tous les champs.";
        }
    }

    $mes_creneaux = $this->repository->getCreneauxByMedecin($id_medecin);

    return [
        'mes_creneaux' => $mes_creneaux,
        'message_success' => $message_success,
        'message_error' => $message_error
    ];
}

    public function afficherDashboard() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'medecin') {
            header('Location: ../auth/login.php');
            exit();
        }
        $repo = new RendezVousRepository($this->pdo); 
        return $repo->trouverRendezVousActifs($_SESSION['user_id']);
    }

    public function afficherHistorique() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'medecin') {
            header('Location: ../auth/login.php');
            exit();
        }
        $repo = new RendezVousRepository($this->pdo);
        return $repo->trouverRendezVousPasses($_SESSION['user_id']);
    }


    public function gererStatut($action, $id_rdv) {
        $repo = new RendezVousRepository($this->pdo);

        if ($action === 'confirmer') {
            $repo->modifierStatut($id_rdv, 'Confirme');
        } 
        elseif ($action === 'annuler') {
            $repo->modifierStatut($id_rdv, 'Annule');

           
            $stmt = $this->pdo->prepare("UPDATE creneaux c 
                                   JOIN rendez_vous r ON r.id_creneau = c.id 
                                   SET c.disponible = 1 
                                   WHERE r.id = ?");
            $stmt->execute([$id_rdv]);
        }

        header('Location: ../../templates/doctor/dashboard.php');
        exit();
    }
}