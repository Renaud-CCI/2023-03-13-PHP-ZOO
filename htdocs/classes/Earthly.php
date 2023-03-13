<?php

class Earthly extends Animal {

    public function __construct(array $data){
        parent::__construct($data);
        $this->hydrate($data);
    }
}

?>