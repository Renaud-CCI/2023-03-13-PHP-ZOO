<?php

class Aquarium extends Enclosure {

    private float $salinity;
    private string $avatar = "https://img.icons8.com/dusk/64/null/aquarium.png";
    private array $acceptedAnimals = ["Fish"];
    private int $price = 1200;
    private int $animalPrice = 800;

    public function __construct(array $data){
        parent::__construct($data);
        $this->hydrate($data);
    }

    public function getSalinity(){
        return $this->salinity;
    }

    public function setSalinity($salinity){
        $this->salinity = $salinity;

        return $this;
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