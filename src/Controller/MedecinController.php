<?php

require_once __DIR__ . "/../Repository/RendezVousRepository.php";

class MedecinController
{
    private RendezVousRepository $repo;

    public function __construct()
    {
        $this->repo = new RendezVousRepository();
    }

    public function planning($medecinId)
    {
        $rdvs = $this->repo->findByMedecin($medecinId);

    

        require __DIR__ . "/../../templates/doctor/planning.php";
    }
}