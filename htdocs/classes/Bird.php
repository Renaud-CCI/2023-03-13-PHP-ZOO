<?php

class Bird extends Animal {

    private int $price = 600;

    public function __construct(array $data){
        parent::__construct($data);
        $this->hydrate($data);
    }

    public function getPrice(){
        return $this->price;
    }

    public function setPrice($price){
        $this->price = $price;

        return $this;
    }
}

?>