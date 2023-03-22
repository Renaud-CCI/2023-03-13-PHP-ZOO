<?php

class Park extends Enclosure {

    private string $avatar = "https://img.icons8.com/officel/80/null/defensive-wood-wall.png";
    private array $acceptedAnimals = ["Bears", "Tiger"];
    private int $price = 800;
    private int $animalPrice = 400;

    public function __construct(array $data){
        parent::__construct($data);
        $this->hydrate($data);
    }


    public function getAvatar(){
        return $this->avatar;
    }

    public function setAvatar($avatar){
        $this->avatar = $avatar;

        return $this;
    }

    public function getAcceptedAnimals(){
        return $this->acceptedAnimals;
    }

    public function setAcceptedAnimals($acceptedAnimals){
        $this->acceptedAnimals = $acceptedAnimals;

        return $this;
    }

    public function getPrice(){
        return $this->price;
    }

    public function setPrice($price){
        $this->price = $price;

        return $this;
    }

    public function getAnimalPrice(){
        return $this->animalPrice;
    }

    public function setAnimalPrice($animalPrice){
        $this->animalPrice = $animalPrice;

        return $this;
    }
}

?>