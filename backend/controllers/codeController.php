<?php

@require_once('models/user.php');
@require_once('models/code.php');
@require_once('core/mailer.php');

class CodeController{
    
    public function sendVerificationCode(){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if(!$body["email"]){
            http_response_code(422);
            echo '{"Unprocessable Entity":"missing data for processing"}';
            return;
        }

        if(User::getByEmail($body['email'])){
            echo '{"Error":"email already used"}';
            http_response_code(409);
            return;
        }

        $code = Code::generateCode($body["email"], 'create');

        $htmlBody = file_get_contents('./view/mail/verification-code.html');
        $htmlBody = str_replace(
            [
                '{{code}}', 
                '{{imageUrl}}',
            ],
            [
                $code, 
                $_ENV['IMAGE_URL'],
            ],
            $htmlBody
        );

        try{
            $mail = Mailer::send(
                $body["email"], 
                'Code de verification', 
                $htmlBody
            );
        }catch (Exception $e) {
            echo "Erreur d'envoi : {$e->getMessage()}";
        }

        echo json_encode(true);
    }

    /**
     * Enregistre et envoie une demande de réinitialisation de mdp
     * 
     * Le body attend un élément :
     * - `string` email: l'email pour lequel on envoie un mail de récupération de mdp
     * 
     * @return void renvoie au format json `true` si le mail de récupération a été envoyé
     * 
     */
    public function sendRecuperationCode(){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if(!$body["email"]){
            http_response_code(422);
            echo '{"Unprocessable Entity":"missing data for processing"}';
            return;
        }

        if(!User::getByEmail($body['email'])){
            http_response_code(400);
            return;
        }

        $code = Code::generateCode($body["email"], 'recuperation-code');

        $htmlBody = file_get_contents('./view/mail/recuperation-code.html');
        $htmlBody = str_replace(
            [
                '{{code}}', 
                '{{imageUrl}}',
            ],
            [
                $code, 
                $_ENV['IMAGE_URL'],
            ],
            $htmlBody
        );

        try{
            $mail = Mailer::send(
                $body["email"], 
                'Code de recuperation', 
                $htmlBody
            );
        }catch (Exception $e) {
            echo "Erreur d'envoi : {$e->getMessage()}";
        }

        echo json_encode(true);
    }

    /**
     * Vérifie si le code est correct
     * 
     * Le body attend les éléments suivants :
     * - `string` email: l'email de l'utilisateur voulant récupérer son compte
     * - `string` code: le code à vérifier
     * - `string` action: l'action associée au code
     * 
     * @return void renvoie au format json `true` si le code est correct
     * 
     */
    public function checkCode(){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if(!$body["email"] || !$body["code"] || !$body["action"]){
            http_response_code(422);
            echo '{"Unprocessable Entity":"missing data for processing"}';
            return;
        }

        if (!Code::checkCode($body["email"], $body["code"], $body["action"])) {
            http_response_code(400);
            return;
        }

        echo json_encode(true);
    }

}