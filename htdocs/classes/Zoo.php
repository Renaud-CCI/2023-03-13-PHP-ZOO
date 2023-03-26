<?php

class Zoo {

    private int $id;
    private string $name;
    private int $user_id;
    private array $employees_id;
    private int $enclosures_max_number;
    private int $day;
    private int $budget;

    public function __construct(array $data){

        // On fait une boucle avec le tableau de données
        foreach ($data as $key => $value) {
            // On récupère le nom des setters correspondants
            // si la clef est id le setter est setId
            // il suffit de mettre la 1ere lettre de key en Maj et de le préfixer par set
            $method = 'set'.ucfirst($key);

            // On vérifie que le setter correspondant existe
            if (method_exists($this, $method)) {
                // S'il existe, on l'appelle
                $this->$method($value);
            }
        }
    }
    
    
    // GETTERS & SETTERS
    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;

        return $this;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;

        return $this;
    }

    public function getEmployees_id(){
        return $this->employees_id;
    }

    public function setEmployees_id($employees_id){
        $this->employees_id = $employees_id;

        return $this;
    }

    public function getEnclosures_max_number(){
        return $this->enclosures_max_number;
    }

    public function setEnclosures_max_number($enclosures_max_number){
        $this->enclosures_max_number = $enclosures_max_number;

        return $this;
    }

    public function getUser_id(){
        return $this->user_id;
    }

    public function setUser_id($user_id){
        $this->user_id = $user_id;

        return $this;
    }

    public function getDay(){
        return $this->day;
    }

    public function setDay($day){
        $this->day = $day;

        return $this;
    }

    public function getBudget(){
        return $this->budget;
    }

    public function setBudget($budget){
        $this->budget = $budget;

        return $this;
    }

}

?>