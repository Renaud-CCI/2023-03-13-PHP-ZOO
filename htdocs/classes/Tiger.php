<?php

class Tiger extends Earthly {


    public function __construct(array $data){
        parent::__construct($data);
        $this->hydrate($data);
    }
}

?>