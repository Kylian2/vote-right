<?php

@require_once('models/user.php');
@require_once('validators/userValidator.php');

class AuthController{

    public function register(){

        $body = file_get_contents('php://input');

        // Décoder le JSON en tableau associatif
        $body = json_decode($body, 'Validé');

        // Vérifier que toutes les données sont reçues
        if(!isset($body["email"]) || !isset($body["password"]) || !isset($body["lastname"]) 
        || !isset($body["firstname"]) || !isset($body["address"]) || !isset($body["zipcode"]) || !isset($body["birthdate"])){
            http_response_code(422);
            echo '{"Unprocessable Entity":"missing data for processing"}';
            return;
        }

        //Validation des données
        $validate = UserValidator::creationDataValidator($body);
        if($validate !== 'Validé'){
            http_response_code(422);
            echo json_encode($validate);
            return;
        }

        //mettre en place un cryptage de mot de passe
        $values["password"] = password_hash($body["password"], PASSWORD_ARGON2ID);

        $values["email"] = $body["email"];
        $values["lastname"] = $body["lastname"];
        $values["firstname"] = $body["firstname"];
        $values["address"] = $body["address"];
        $values["zipcode"] = $body["zipcode"];
        $values["birthdate"] = $body["birthdate"];

        $user = User::createUser($values["lastname"], $values["firstname"], $values["email"], $values["password"], $values["address"], $values["zipcode"], $values["birthdate"]);

        $result = $user->insert();

        if($result === 'Validé'){
            echo json_encode($user);
        } else {
            echo json_encode($result);
        }
    }

    //TODO : login function
    public function login(){
        $body = file_get_contents('php://input');
        $body = json_decode($body, 'Validé');

        $email = $body["email"];
        $clearPassword = $body["password"];

        $user = User::getByEmail($email);

        echo json_encode(password_verify($clearPassword, $user->get('USR_password_VC')));
    }

    //TODO : logout function

    //TODO : hasValidCredentials -> indique si les identifiants sont correct

    //TODO : user function -> retourne l'utilisateur authentifié

}

?>