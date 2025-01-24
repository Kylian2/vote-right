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

        $mail = Mailer::init();
        $mail->Subject = 'Code de verification';
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
        $mail->Body = $htmlBody;
        $mail->SMTPKeepAlive = true;

        $mail->addBCC($body["email"]);
        try{
            Mailer::send($mail);
        }catch (Exception $e) {
            echo "Erreur d'envoi : {$mail->ErrorInfo}";
        }
        $mail->SmtpClose();

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
    public static function sendRecuperationCode(){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if(!$body["email"]){
            http_response_code(422);
            echo '{"Unprocessable Entity":"missing data for processing"}';
            return;
        }

        if(User::getByEmail($body['email'])){
            
        } else {
            echo '{"Error":"email not exist"}';
            http_response_code(409);
            return;
        }

        $code = Code::generateCode($body["email"], 'create');

        $mail = Mailer::init();
        $mail->Subject = 'Code de recuperation';
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
        $mail->Body = $htmlBody;
        $mail->SMTPKeepAlive = true;

        $mail->addBCC($body["email"]);
        try{
            Mailer::send($mail);
        }catch (Exception $e) {
            echo "Erreur d'envoi : {$mail->ErrorInfo}";
        }
        $mail->SmtpClose();

        echo json_encode(true);
    }
}