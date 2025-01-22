<?php

@require_once('models/user.php');
@require_once('models/code.php');
@require_once('validators/userValidator.php');
@require_once('core/sessionGuard.php');
@require_once('core/mailer.php');

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
        || !isset($body["firstname"]) || !isset($body["address"]) || !isset($body["zipcode"]) || !isset($body["birthdate"]) || !isset($body["code"])){
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

        $values["USR_password_VC"] = password_hash($body["password"], PASSWORD_ARGON2ID);

        $values["USR_email_VC"] = $body["email"];
        $values["USR_lastname_VC"] = $body["lastname"];
        $values["USR_firstname_VC"] = $body["firstname"];
        $values["USR_address_VC"] = $body["address"];
        $values["USR_zipcode_CH"] = $body["zipcode"];
        $values["USR_birthdate_DATE"] = $body["birthdate"];

        $user = new User($values);
        $code = $body["code"];

        try{
            $user->insert($code);
        }catch(Exception $e){
            http_response_code(400);
            $return["Erreur"] = $e->getMessage();
            echo json_encode($return);
            return;
        }
        SessionGuard::start($user);
        echo json_encode($user);

        try{
            $mail = Mailer::init();
            $mail->addAddress($user->get('USR_email_VC'));
            $mail->Subject = 'Création de compte VoteRight.fr';
            $htmlBody = file_get_contents('./view/mail/register.html');
            $htmlBody = str_replace(
                ['{{firstname}}', '{{imageUrl}}'],
                [$user->get("USR_firstname_VC"), $_ENV['IMAGE_URL']],
                $htmlBody
            );
            $mail->Body = $htmlBody;
            Mailer::send($mail);
        }catch (Exception $e) {
            echo "Erreur d'envoi : {$mail->ErrorInfo}";
        }
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