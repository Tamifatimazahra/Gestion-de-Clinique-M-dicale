<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Repository/SpecialiteRepository.php';
require_once __DIR__ . '/../Repository/UserRepository.php';
require_once __DIR__ . '/../Repository/RendezVousRepository.php';

class AdminController {
    private $specialiteRepo;
    private $userRepo;
    private $rendezVousRepo;

    public function __construct(){
        $database = new Database();
        $dbConn = $database->getConnection();
    }
}