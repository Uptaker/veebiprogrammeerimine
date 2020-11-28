<?php

class First {
    // variables are properties in a class
    private $mybusiness;
    public $everybodysbusiness;
    
    function __construct($limit) {
        $this->mybusiness = mt_rand(0, $limit);
        $this->everybodysbusiness = mt_rand(0, 100);
        echo "Arvude korrutis on: " .$this->mybusiness * $this->everybodysbusiness;
        $this->tellSecret();
    } // construct ends

    function __destruct() {
        echo "Nägite hulk mõttetud infot!";
    }

    // functions are called methods in classes
    public function tellMe() {
        echo " Salajane arv on: " .$this->mybusiness;
    }

    private function tellSecret() {
        echo " Kõik saladusi võib väljarääkida!";
    }


} // class ends