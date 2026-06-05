<?php
require_once __DIR__ . '/../Repository/MedecinRepository.php';

class HomeController {
    private $medecinRepository;

    public function __construct($medecinRepository) {
        $this->medecinRepository = $medecinRepository;
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // جلب الداتا واجدة من الـ Repo
        $top_medecins = $this->medecinRepository->getTopMedecins(3);

        return [
            'top_medecins' => $top_medecins
        ];
    }
}