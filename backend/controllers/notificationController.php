<?php 

@require_once('models/user.php');
@require_once('models/community.php');
@require_once('core/mailer.php');

class notificationController{

    public static function notifyNewProposal(Proposal $proposal){

        $request = "SELECT USR_lastname_VC, USR_firstname_VC, USR_email_VC
                    FROM member
                    INNER JOIN user ON USR_id_NB = MEM_user_NB
                    WHERE MEM_community_NB = :community AND USR_notify_proposal_BOOL = 1";
        $prepare = connexion::pdo()->prepare($request);
        $values['community'] = $proposal->get('PRO_community_NB');
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "user");
        $users = $prepare->fetchAll();

        $request = "SELECT * FROM community WHERE CMY_id_NB = :community";
        $prepare = connexion::pdo()->prepare($request);
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "community");
        $community = $prepare->fetch();

        $request = "SELECT USR_lastname_VC, USR_firstname_VC FROM user WHERE USR_id_NB = :user";
        $prepare = connexion::pdo()->prepare($request);
        $values = array(
            "user" => $proposal->get('PRO_initiator_NB')
        );
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "user");
        $senderUser = $prepare->fetch();

        $mail = Mailer::init();
        $mail->Subject = 'Nouvelle proposition';
        $htmlBody = file_get_contents('./view/mail/notify-proposal.html');
        $htmlBody = str_replace(
            [
                '[backgroundColor]', 
                '{{firstname}}', 
                '{{lastname}}', 
                '{{community}}', 
                '{{proposal}}', 
                '{{proposalId}}'
            ],
            [
                $community->get("CMY_color_VC"), 
                $senderUser->get("USR_firstname_VC"),
                $senderUser->get("USR_lastname_VC"),
                $community->get("CMY_name_VC"),
                $proposal->get("PRO_title_VC"),
                $proposal->get("PRO_id_NB"),
            ],
            $htmlBody
        );
        $mail->Body = $htmlBody;
        $mail->SMTPKeepAlive = true;

        foreach($users as $user){
            try{
                $mail->addBCC($user->get('USR_email_VC'));
            }catch (Exception $e) {
                echo "Erreur d'envoi : {$mail->ErrorInfo}";
            }
        }
        try{
            Mailer::send($mail);
        }catch (Exception $e) {
            echo "Erreur d'envoi : {$mail->ErrorInfo}";
        }
        $mail->SmtpClose();
    }

}

?>