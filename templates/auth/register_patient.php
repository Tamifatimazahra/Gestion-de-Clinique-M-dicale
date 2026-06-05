<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/database.php';
global $pdo;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($password)) {
        try {
           
            $checkEmail = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $checkEmail->execute([$email]);
            if ($checkEmail->fetchColumn() > 0) {
                header('Location: ../../public/index.php?error=Cet email est déjà utilisé.');
                exit();
            }

            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
             

            $stmt = $pdo->prepare("INSERT INTO users (nom,prenom, email, password, role) VALUES (?,?, ?, ?, 'patient')");
            $stmt->execute([$nom,$prenom, $email, $hashed_password]);


            header('Location: login.php?success=Compte créé avec succès. Connectez-vous.');
            exit();
        } catch (Exception $e) {
            header('Location: ../index.php?error=Erreur système : ' . urlencode($e->getMessage()));
            exit();
        }
    } else {
        header('Location: ../../public/index.php?error=Veuillez remplir tous les champs.');
        exit();
    }
} else {
    header('Location: ../../public/index.php');
    exit();
}
