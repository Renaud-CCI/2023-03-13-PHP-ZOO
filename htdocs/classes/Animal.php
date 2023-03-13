<?php

class Animal {
    protected int $id;
    protected float $weight;
    protected float $height;
    protected float $age;
    protected bool $isAngry;
    protected bool $isSleppy;
    protected bool $isSick;

    public function __construct(array $data){
        $this->hydrate($data);
        }


    protected function hydrate(array $data){
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

    public function getWeight(){
        return $this->weight;
    }

    public function setWeight($weight){
        $this->weight = $weight;

        return $this;
    }

    public function getHeight(){
        return $this->height;
    }

    public function setHeight($height){
        $this->height = $height;

        return $this;
    }

    public function getAge(){
        return $this->age;
    }

    public function setAge($age){
        $this->age = $age;

        return $this;
    }

    public function getIsAngry(){
        return $this->isAngry;
    }

    public function setIsAngry($isAngry){
        $this->isAngry = $isAngry;

        return $this;
    }

    public function getIsSleppy(){
        return $this->isSleppy;
    }

    public function setIsSleppy($isSleppy){
        $this->isSleppy = $isSleppy;

        return $this;
    }

    public function getIsSick(){
        return $this->isSick;
    }

    public function setIsSick($isSick){
        $this->isSick = $isSick;

        return $this;
    }
}

?>