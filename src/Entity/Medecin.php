<?php 
class Medecin{
    private $id;
    private $id_user;
    private $id_specilete;
    private $actif;


    public function __construct($id_user,$id_specilete,$actif,$id = null)  {
        $this->id_user=$id_user;
        $this->id_specilete=$id_specilete;
        $this->actif=$actif;
        $this->id=$id;
    }


}