<?php

class Fish extends Marine {

    public function __construct(array $data){
        parent::__construct($data);
        $this->hydrate($data);
    }
}

?>