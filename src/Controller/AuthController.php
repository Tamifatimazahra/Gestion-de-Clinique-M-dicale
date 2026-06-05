<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/database.php';

class AuthController {
    
    public function login() {
        global $pdo;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            if (empty($email) || empty($password)) {
                return "Veuillez remplir tous les champs.";
            }

           
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            
            if ($user && ($password === $user['password'] || password_verify($password, $user['password']))) {
                
           
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nom'] = $user['nom'] . ' ' . $user['prenom'];
                $_SESSION['role'] = $user['role'];

               
                switch ($user['role']) {
                    case 'medecin':
                        header('Location: ../../templates/doctor/dashboard.php');
                        break;
                    case 'patient':
                        header('Location: ../../templates/patient/dashboard.php'); // بدلها لمسار المريض عندك
                        break;
                    case 'admin':
                        header('Location: ../../templates/admin/dashboard.php'); // بدلها لمسار الآدمين عندك
                        break;
                    default:
                        header('Location: ../../templates/auth/login.php');
                }
                exit();
            } else {
                return "Email ou mot de passe incorrect.";
            }
        }
    }
}


$auth = new AuthController();
$erreur = $auth->login();