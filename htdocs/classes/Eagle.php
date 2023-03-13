<?php

class Eagle extends Bird {

    public function __construct(array $data){
        parent::__construct($data);
        $this->hydrate($data);
    }
}

?>