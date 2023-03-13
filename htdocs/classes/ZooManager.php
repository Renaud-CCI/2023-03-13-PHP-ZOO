<?php


class ZooManager {
    private $db; 

    public function __construct(PDO $db){
        $this->setDb($db);
    }

    
    // GETTERS & SETTERS
    public function setDb($db)    {
        $this->db = $db;

        return $this;
    }
}


?>