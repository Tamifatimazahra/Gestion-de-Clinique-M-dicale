<?php

class Database{
    private $host = "localhost";
    private $db_name = "medflow_db";
    private $username = "root";
    private $password = "";
    private $conn = null;

    public function getConnection(){
        if ($this->conn === null){
            try{
                $this->conn = new PDO(
                    "mysql:host=" .$this->host . ";dbname=" .$this->db_name . ";charset=utf8",
                    $this->username,
                    $this->password
                );

                $this->conn->setAttribute(PDO::ATTER_ERRMODE, PDO::ERRMODE_EXEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            }
            catch (PDOException $exeception){
                die("Erreur de connexion : " . $exeception->getMessage());
            }
        }
        return $this->conn;
    }
}