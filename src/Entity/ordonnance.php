<?php

class Ordonnance {
    private $id;
    private $description;
    private $id_rendez_vous;

    public function __construct($description, $id_rendez_vous, $id = null) {
        $this->id = $id;
        $this->description = $description;
        $this->id_rendez_vous = $id_rendez_vous;
    }
}