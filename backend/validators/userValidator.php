<?php

class UserValidator
{

    public static function creationDataValidator(array $data)
    {

        if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            throw new Error("Invalid email format");
        }

        if (strlen($data["email"]) > 150) {
            throw new Error("Invalid size of email");
        }

        if (strlen($data["lastname"]) > 50) {
            throw new Error("Invalid size of name");
        }

        if (strlen($data["firstname"]) > 50) {
            throw new Error("Invalid size of firstname");
        }

        /* Ancien code
        if(strlen($data["address"]) > 200){
            throw new Error("Invalid size of address");
        }

        if(strlen($data["zipcode"]) !== 5){
            throw new Error("Incorrect postcode");
        }

        if (!User::validateDate($data["birthdate"], 'Y-m-d')) { 
            throw new Error("Invalid date format (must be in Y-m-d)");
        }
        */

        if (!is_numeric($data["code"])) {
            throw new Error("Incorrect code");
        }

        return true;
    }

    public static function informationDataValidator(array $data)
    {

        if (isset($data['email']) && !filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            throw new Error("Invalid email format");
        }

        if (isset($data['email']) && strlen($data["email"]) > 150) {
            throw new Error("Invalid size of email");
        }


        if (isset($data['address']) && strlen($data["address"]) > 200) {
            throw new Error("Invalid size of address");
        }

        if (isset($data['zipcode']) && strlen($data["zipcode"]) !== 5) {
            throw new Error("Incorrect postcode");
        }

        if (isset($data['birthdate']) && !User::validateDate($data["birthdate"], 'Y-m-d')) {
            throw new Error("Invalid date format (must be in Y-m-d)");
        }

        return true;
    }
}
