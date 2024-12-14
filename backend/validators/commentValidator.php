<?php

class proposalValidator{

    /**
     * Valide les données d'un commentaire
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

        if(!isset($data['message']) || !isset($data['proposal'])){
            throw new Error("Missing data");
        }

        if(strlen($data["message"]) > 250){
            throw new Error("Invalid size of message");
        }

        if(!is_numeric($data["proposal"])){
            throw new Error("Invalid type of proposal, expected an integer");
        }

        $data["message"] = filter_var($data["message"], FILTER_FLAG_STRIP_BACKTICK);
        $data["message"] = str_replace('"', '', $data["message"]);
        $data["message"] = filter_var($data["message"], FILTER_FLAG_EMPTY_STRING_NULL);

        return true;
    }   

}

?>