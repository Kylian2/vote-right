<?php

@require_once('models/user.php');
@require_once('validators/userValidator.php');
@require_once('core/sessionGuard.php');

//On peut acceder aux durées max de session et paramètre du garbage collector dans php.ini ou en 
//les parametrant avec php_ini().

class AuthController{

    /**
     * Inscris un utilisateur avec les données passées dans la requête 
     * 
     * Données attendues : email, password, lastname, firstname, addresse, zipcode, birthdate (Y-m-d)
     * 
     * REPONSE JSON: json associe de l'entite user si inscription reussie, sinon indique le problème
     * 
     * @return void Réponse sous forme de JSON
     */
    public function register(){

        $body = file_get_contents('php://input');

        // Décoder le JSON en tableau associatif
        $body = json_decode($body, true);

        // Vérifier que toutes les données sont reçues
        if(!isset($body["email"]) || !isset($body["password"]) || !isset($body["lastname"]) 
        || !isset($body["firstname"]) || !isset($body["address"]) || !isset($body["zipcode"]) || !isset($body["birthdate"])){
            http_response_code(422);
            echo '{"Unprocessable Entity":"missing data for processing"}';
            return;
        }

        //Validation des données
        try{
            UserValidator::creationDataValidator($body);
        }catch (Error $e){
            http_response_code(422);
            $return["Unprocessable Entity"] = $e->getMessage();
            echo json_encode($return);
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

        try{
            $result = $user->insert();
        }catch(Exception $e){
            $return["Erreur"] = $e->getMessage();
            echo json_encode($return);
            return;
        }

        echo json_encode($user);
    }

    /**
     * Connecte un utilisateur 
     * 
     * Données attendues : email, password
     * 
     * REPONSE JSON : true si connexion, faux sinon
     * @return void Renvoie la réponse en JSON
     */
    public function login(){

        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        $email = $body["email"];
        $clearPassword = $body["password"];

        
        $user = SessionGuard::verifyCredentials($email, $clearPassword);
        if($user){
            SessionGuard::start($user);
            echo json_encode(true);
        }else{
            echo json_encode(false);
            SessionGuard::stop();
        }
    }

    /**
     * Verifie l'état de la session
     * 
     * REPONSE JSON : true si la session est valide, false sinon
     * @return void Réponse sous forme de JSON
     */
    public function check(){        
        echo json_encode(SessionGuard::checkSessionValidity());
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout(){
        SessionGuard::stop();
        echo json_encode(true);
    }
}

?>