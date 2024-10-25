<?php

class UserValidator{

    public static function creationDataValidator(Array $data){

        if(!filter_var($data["email"], FILTER_VALIDATE_EMAIL)){
            throw new Error("Invalid email format");
        }

        if(strlen($data["email"]) > 150){
            throw new Error("Invalid size of email");
        }

        if(strlen($data["lastname"]) > 50){
            throw new Error("Invalid size of name");
        }

        if(strlen($data["firstname"]) > 50){
            throw new Error("Invalid size of firstname");
        }

        if(strlen($data["address"]) > 200){
            throw new Error("Invalid size of address");
        }

        if(strlen($data["zipcode"]) !== 5){
            throw new Error("Incorrect postcode");
        }

        if (!User::validateDate($data["birthdate"], 'Y-m-d')) { 
            throw new Error("Invalid date format (must be in Y-m-d)");
        }
        
        return true;
    }
}

?>