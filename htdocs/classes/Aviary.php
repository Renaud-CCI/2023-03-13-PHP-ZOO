<?php

class Aviary extends Enclosure {

    private float $height;
    private string $avatar = "https://img.icons8.com/color-glass/48/000000/cage-of-a-bird.png";
    private array $acceptedAnimals = ["Eagle"];

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
}

?>