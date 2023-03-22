<?php

class Aviary extends Enclosure {

    private float $height;
    private string $avatar = "https://img.icons8.com/color-glass/48/000000/cage-of-a-bird.png";
    private array $acceptedAnimals = ["Eagle"];
    private int $price = 1000;
    private int $animalPrice = 600;

    public function __construct(array $data){
        parent::__construct($data);
        $this->hydrate($data);
    }

    // GETTERS & SETTERS
    public function getHeight(){
        return $this->height;
    }

    public function setHeight($height){
        $this->height = $height;

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