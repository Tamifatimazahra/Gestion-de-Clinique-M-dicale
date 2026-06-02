
CREATE DATABASE medflow_db ;
USE medflow_db;


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,          
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,  
   
    role ENUM('admin', 'medecin', 'patient') NOT NULL
) ;

CREATE TABLE specialites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description VARCHAR(255)
) ;


CREATE TABLE medecins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,        
    id_specialite INT NOT NULL,          
    actif BOOLEAN DEFAULT TRUE,    

    CONSTRAINT fk_medecin_utilisateur
        FOREIGN KEY (id_utilisateur)
            REFERENCES utilisateurs(id)
            ON DELETE CASCADE,

    CONSTRAINT fk_medecin_specialite
        FOREIGN KEY (id_specialite)
            REFERENCES specialites(id)
            ON DELETE RESTRICT
) ;


CREATE TABLE creneaux (
    id INT AUTO_INCREMENT PRIMARY KEY,
    heure_debut TIMESTAMP NOT NULL,     
    heure_fin TIMESTAMP NOT NULL,       
    disponible BOOLEAN DEFAULT TRUE, 
    id_medecin INT NOT NULL,             

    CONSTRAINT fk_creneau_medecin
        FOREIGN KEY (id_medecin)
            REFERENCES medecins(id)
            ON DELETE CASCADE
) ;


CREATE TABLE rendez_vous (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_patient INT NOT NULL,
    id_medecin INT NOT NULL,           

    statut ENUM(
        'En attente',
        'Annulé',
        'Terminé'
    ) NOT NULL DEFAULT 'En attente',     

    id_creneau INT NOT NULL,            

    CONSTRAINT fk_rendezvous_patient
        FOREIGN KEY (id_patient)
            REFERENCES utilisateurs(id)
            ON DELETE CASCADE,

    CONSTRAINT fk_rendezvous_medecin
        FOREIGN KEY (id_medecin)
            REFERENCES medecins(id)
            ON DELETE CASCADE,

    CONSTRAINT fk_rendezvous_creneau
        FOREIGN KEY (id_creneau)
            REFERENCES creneaux_horaires(id)
            ON DELETE CASCADE
) ;


CREATE TABLE ordonnances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(255) NOT NULL,
    id_rendez_vous INT NOT NULL UNIQUE, 

    CONSTRAINT fk_ordonnance_rendezvous
        FOREIGN KEY (id_rendez_vous)
            REFERENCES rendez_vous(id)
            ON DELETE CASCADE
) ;