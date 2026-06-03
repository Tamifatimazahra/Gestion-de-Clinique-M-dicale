<?php

class MedecinController
{
    private RyendezVousRepository $repo;

    public function __construct()
    {
        $this->repo = new RendezVousRepository();
    }

    public function planning($medecinId)
    {
        $rdvs = $this->repo->findByMedecin($medecinId);

        require "templates/doctor/planning.php";
    }
}