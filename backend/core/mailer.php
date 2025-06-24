<?php

use Mailgun\Mailgun;


class Mailer{
    
    /**
     * Envoyer un email avec l'api Mailgun
     * 
     * @return true si la requête s'est correctement déroulée
     */
    public static function send(string $to, string $subject, string $message)
    {
        $mg = Mailgun::create($_ENV['EMAIL_API_KEY'], 'https://api.eu.mailgun.net');

        $result = $mg->messages()->send(
            $_ENV['EMAIL_DOMAIN'], 
            [
                'from'    => $_ENV['APP_NAME'] . ' <' . $_ENV['EMAIL_USER'] . '>',
                'to'      => $to,
                'subject' => $subject,
                'html'    => $message
            ]
        );

        if ($result->getMessage() === 'Queued. Thank you.') {
            return true;
        } else {
            throw new Exception('Error sending email: ' . $result->getMessage());
        }
    }
}

?>
