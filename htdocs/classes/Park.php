<?php

class Park extends Enclosure {

    private string $avatar = "https://img.icons8.com/officel/80/null/defensive-wood-wall.png";
    private array $acceptedAnimals = ["Bears", "Tiger"];

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
}

?>