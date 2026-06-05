<?php

class User
{
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $password;
    private $role;

    public function __construct($id, $nom, $prenom, $email, $password, $role)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function getEmail()
    {
        return $this->email;
    }
   

    public function getRole()
    {
        return $this->role;
    }
}