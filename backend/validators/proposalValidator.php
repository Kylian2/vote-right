<?php

class proposalValidator{

    /**
     * Valide une entrée de proposition
     * 
     * Vérifie les points suivants : 
     * - Toutes les données requises sont présentes
     * - Les tailles de données sont correcte vis à vis des contraintes de la base de données
     * 
     * Fini en nettoyant les strings des caractères inoportuns pouvant causer des problèmes. 
     * 
     * @return true si les données sont valides
     */
    public static function storeDataValidator(array &$data){

        if(!isset($data['title']) || !isset($data['description'])|| !isset($data['community'])|| !isset($data['theme'])){
            throw new Error("Missing data");
        }

        if(strlen($data["title"]) > 150){
            throw new Error("Invalid size of title");
        }

        if(isset($data['location']) && strlen($data["location"]) > 255){
            throw new Error("Invalid size of location");
        }

        if(!is_numeric($data["community"])){
            throw new Error("Invalid type of community, expected an integer");
        }

        if(!is_numeric($data["theme"])){
            throw new Error("Invalid type of theme, expected an integer");
        }

        $data["title"] = filter_var($data["title"], FILTER_FLAG_STRIP_BACKTICK);
        $data["title"] = str_replace('"', '', $data["title"]);
        $data["title"] = filter_var($data["title"], FILTER_FLAG_EMPTY_STRING_NULL);

        $data["description"] = filter_var($data["description"], FILTER_FLAG_STRIP_BACKTICK);
        $data["description"] = str_replace('"', '', $data["description"]);
        $data["description"] = filter_var($data["description"], FILTER_FLAG_EMPTY_STRING_NULL);

        if(isset($data['location'])){
            $data["location"] = filter_var($data["location"], FILTER_FLAG_STRIP_BACKTICK);
            $data["location"] = str_replace('"', '', $data["location"]);
            $data["location"] = filter_var($data["location"], FILTER_FLAG_EMPTY_STRING_NULL);
        }

        return true;
    }   

}

?>