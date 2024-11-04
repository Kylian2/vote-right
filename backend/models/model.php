<?php

class Model{

    function __construct($values = NULL){
        if(!is_null($values)){
            foreach($values as $attribut => $valeur){
                $this->set($attribut, $valeur);
            }
        }
    }

    public function get($attribut){
        return $this->$attribut;
    }

    public function set($attribut, $valeur){
        $this->$attribut = $valeur;
    }
}

?>