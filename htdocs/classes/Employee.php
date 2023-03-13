<?php

class Employee {

    private int $id;
    private string $name;
    private float $age;
    private string $sex;

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

    public function getAge(){
        return $this->age;
    }
 
    public function setAge($age){
        $this->age = $age;

        return $this;
    }

    public function getSex(){
        return $this->sex;
    }

    public function setSex($sex){
        $this->sex = $sex;

        return $this;
    }
}

?>