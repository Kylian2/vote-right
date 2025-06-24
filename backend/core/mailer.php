<?php

use Mailgun\Mailgun;


class Mailer{
    
    /**
     * Send an email using Mailgun API.
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
