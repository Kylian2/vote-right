<?php 

class CommunityValidator{

    /**
     * Valide une entrée de communauté. 
     * 
     * Vérifie les points suivant 
     * - Les tailles des variables pour être en accord avec la taille stockée dans la base de données
     * - Vérifie la présence d'un # au début du code hexadecimal de la couleur
     * 
     * Fini en nettoyant les strings des caractères inoportuns pouvant causer des problèmes. 
     * 
     * @return true si les données sont valides. 
     */
    public static function storeDataValidator(array &$data){

        if(strlen($data["name"]) > 150){
            throw new Error("Invalid size of name");
        }

        if(strlen($data["image"]) > 50){
            throw new Error("Invalid size of image name");
        }

        if(strlen($data["emoji"]) !== 5){
            throw new Error("Invalid size of emoji (in Ascii)");
        }

        if(strlen($data["color"]) !== 7){
            throw new Error("Invalid size of color (must be in hex)");
        }

        if(strpos($data["color"], "#") !== 0){
            throw new Error("Invalid format of color (must be in hex)");
        }

        $data["name"] = filter_var($data["name"], FILTER_FLAG_STRIP_BACKTICK);
        $data["name"] = str_replace('"', '', $data["name"]);
        $data["name"] = filter_var($data["name"], FILTER_FLAG_EMPTY_STRING_NULL);

        $data["description"] = filter_var($data["description"], FILTER_FLAG_STRIP_BACKTICK);
        $data["description"] = str_replace('"', '', $data["description"]);
        $data["description"] = filter_var($data["description"], FILTER_FLAG_EMPTY_STRING_NULL);
    }

}

?>