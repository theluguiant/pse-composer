<?php

namespace theluguiant\PseComposer;

class Pse{
    public $services; 
    public function __construct() {
        $this->services = new ServicePseBank();
    }
}