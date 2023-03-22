<?php

class Earthly extends Animal {

    private int $price = 400;

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