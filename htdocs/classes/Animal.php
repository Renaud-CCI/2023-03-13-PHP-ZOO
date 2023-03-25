<?php

class Animal {
    protected int $id;
    protected string $name;
    protected string $sex;
    protected float $weight;
    protected float $height;
    protected int $birthday;
    protected float $isHungry;
    protected bool $isSleppy;
    protected float $isSick;
    protected string $genderSymbol;
    protected string $species;
    protected int $dead;
    protected int $enclosure_id;

    public function __construct(array $data){
        $this->hydrate($data);


        if (isset($data["sex"])){
            switch ($data["sex"]){
                case "Male":
                    $this->setGenderSymbol('https://img.icons8.com/office/16/null/male.png');
                    break;
                case 'Femelle':
                    $this->setGenderSymbol('https://img.icons8.com/office/16/null/female.png');
                    break;
                case 'Autre':
                    $this->setGenderSymbol('https://img.icons8.com/external-flaticons-lineal-color-flat-icons/64/null/external-gender-lgbt-flaticons-lineal-color-flat-icons-2.png');
                    break;
                default:
                    $this->setGenderSymbol('');
            }
        }
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

    public function getIsHungry(){
        return $this->isHungry;
    }

    public function setIsHungry($isHungry){
        $this->isHungry = $isHungry;

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

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;

        return $this;
    }

    public function getGenderSymbol(){
        return $this->genderSymbol;
    }

    public function setGenderSymbol($genderSymbol){
        $this->genderSymbol = $genderSymbol;

        return $this;
    }

    public function getSex(){
        return $this->sex;
    }

    public function setSex($sex){
        $this->sex = $sex;

        return $this;
    }

    public function getSpecies(){
        return $this->species;
    }

    public function setSpecies($species){
        $this->species = $species;

        return $this;
    }

    public function getBirthday(){
        return $this->birthday;
    }

    public function setBirthday($birthday){
        $this->birthday = $birthday;

        return $this;
    }

    public function getDead(){
        return $this->dead;
    }

    public function setDead($dead){
        $this->dead = $dead;

        return $this;
    }

    public function getEnclosure_id(){
        return $this->enclosure_id;
    }

    public function setEnclosure_id($enclosure_id){
        $this->enclosure_id = $enclosure_id;

        return $this;
    }
}

?>