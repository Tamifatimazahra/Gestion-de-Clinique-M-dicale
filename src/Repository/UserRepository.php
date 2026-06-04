<?php

class UserRepository{
    private $db;

    public function __construct($databaseConnection){
        $this->db = $databaseConnection;
    }

    
}