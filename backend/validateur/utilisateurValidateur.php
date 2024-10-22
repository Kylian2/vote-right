<?php

class UtilisateurValidateur{

    public static function creationDataValidateur(Array $data){

        try{

            if(!filter_var($data["email"], FILTER_VALIDATE_EMAIL)){
                throw new Exception("Invalid email format");
            }

            if(strlen($data["email"]) > 150){
                throw new Exception("Invalid size of email");
            }

            if(strlen($data["nom"]) > 50){
                throw new Exception("Invalid size of name");
            }

            if(strlen($data["prenom"]) > 50){
                throw new Exception("Invalid size of firstname");
            }

            if(strlen($data["adresse"]) > 200){
                throw new Exception("Invalid size of address");
            }

            if(strlen($data["codepostal"]) !== 5){
                throw new Exception("Incorrect postcode");
            }

            if (!Utilisateur::validateDate($data["naissance"], 'Y-m-d')) { 
                throw new Exception("Invalid date format (must be in Y-m-d)");
            }

            return true;
        }catch(Exception $e){
            return "Erreur : ".$e;
        }

    }

}

?>