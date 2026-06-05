CREATE DATABASE medflow_db;
USE medflow_db;

-- ===================================
-- USERS
-- ===================================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'medecin', 'patient') NOT NULL
);

-- ===================================
-- SPECIALITES
-- ===================================

CREATE TABLE specialites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description VARCHAR(255)
);

-- ===================================
-- MEDECINS
-- ===================================

CREATE TABLE medecins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL UNIQUE,
    id_specialite INT NOT NULL,
    actif BOOLEAN DEFAULT TRUE,

    CONSTRAINT fk_medecin_user
        FOREIGN KEY (id_user)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_medecin_specialite
        FOREIGN KEY (id_specialite)
        REFERENCES specialites(id)
        ON DELETE RESTRICT
);

-- ===================================
-- CRENEAUX
-- ===================================

CREATE TABLE creneaux (
    id INT AUTO_INCREMENT PRIMARY KEY,

    heure_debut DATETIME NOT NULL,
    heure_fin DATETIME NOT NULL,

    disponible BOOLEAN DEFAULT TRUE,

    id_medecin INT NOT NULL,

    CONSTRAINT fk_creneau_medecin
        FOREIGN KEY (id_medecin)
        REFERENCES medecins(id)
        ON DELETE CASCADE
);

-- ===================================
-- RENDEZ_VOUS
-- ===================================

CREATE TABLE rendez_vous (
    id INT AUTO_INCREMENT PRIMARY KEY,

    id_patient INT NOT NULL,
    id_medecin INT NOT NULL,
    id_creneau INT NOT NULL,

    statut ENUM(
        'En attente',
        'Annulé',
        'Terminé'
    ) DEFAULT 'En attente',

    CONSTRAINT fk_rendezvous_patient
        FOREIGN KEY (id_patient)
        REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_rendezvous_medecin
        FOREIGN KEY (id_medecin)
        REFERENCES medecins(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_rendezvous_creneau
        FOREIGN KEY (id_creneau)
        REFERENCES creneaux(id)
        ON DELETE CASCADE
);

-- ===================================
-- ORDONNANCES
-- ===================================

CREATE TABLE ordonnances (
    id INT AUTO_INCREMENT PRIMARY KEY,

    description TEXT NOT NULL,

    id_rendez_vous INT NOT NULL UNIQUE,

    CONSTRAINT fk_ordonnance_rendezvous
        FOREIGN KEY (id_rendez_vous)
        REFERENCES rendez_vous(id)
        ON DELETE CASCADE
);