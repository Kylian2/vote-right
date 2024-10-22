<?php

@require_once('models/user.php');
@require_once('validators/userValidator.php');

class UserController{

    public function index(){
        $utilisateur = User::getAll();
        echo json_encode($utilisateur);
    }

    public function store(){

        $body = file_get_contents('php://input');

        // Décoder le JSON en tableau associatif
        $userData = json_decode($body, true);

        //Vérifier que toutes les données sont reçus
        if(!isset($userData["email"]) || !isset($userData["motdepasse"]) || !isset($userData["nom"]) 
        || !isset($userData["prenom"]) || !isset($userData["adresse"]) || !isset($userData["codepostal"])|| !isset($userData["naissance"])){
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
        $values["motdepasse"] = $userData["motdepasse"];
        $values["nom"] = $userData["nom"];
        $values["prenom"] = $userData["prenom"];
        $values["adresse"] = $userData["adresse"];
        $values["codepostal"] = $userData["codepostal"];
        $values["naissance"] = $userData["naissance"];

        $utilisateur = User::createUser($values["nom"], $values["prenom"], $values["email"], $values["motdepasse"], $values["adresse"], $values["codepostal"], $values["naissance"]);

        $resultat = $utilisateur->insert();

        if($resultat === true){
            echo json_encode($utilisateur);
        }else{
            echo json_encode($resultat);
        }
    }
}

?>