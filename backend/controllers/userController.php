<?php

@require_once('models/user.php');
@require_once('validators/userValidator.php');

class UserController{

    public function index(){
        $users = User::getAll();
        echo json_encode($users);
    }

    public function store(){

        $body = file_get_contents('php://input');

        // Décoder le JSON en tableau associatif
        $userData = json_decode($body, true);

        // Vérifier que toutes les données sont reçues
        if(!isset($userData["email"]) || !isset($userData["password"]) || !isset($userData["lastname"]) 
        || !isset($userData["firstname"]) || !isset($userData["address"]) || !isset($userData["zipcode"]) || !isset($userData["birthdate"])){
            http_response_code(422);
            echo '{"Unprocessable Entity":"missing data for processing"}';
            return;
        }

        //Validation des données
        $validate = UserValidator::creationDataValidator($userData);
        if($validate !== true){
            http_response_code(422);
            echo json_encode($validate);
            return;
        }

        //mettre en place un cryptage de mot de passe
        $values["email"] = $userData["email"];
        $values["password"] = $userData["password"];
        $values["lastname"] = $userData["lastname"];
        $values["firstname"] = $userData["firstname"];
        $values["address"] = $userData["address"];
        $values["zipcode"] = $userData["zipcode"];
        $values["birthdate"] = $userData["birthdate"];

        $user = User::createUser($values["lastname"], $values["firstname"], $values["email"], $values["password"], $values["address"], $values["zipcode"], $values["birthdate"]);

        $result = $user->insert();

        if($result === true){
            echo json_encode($user);
        } else {
            echo json_encode($result);
        }
    }
}

?>