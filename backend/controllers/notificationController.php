<?php 

@require_once('models/user.php');
@require_once('models/community.php');
@require_once('models/comment.php');
@require_once('models/proposal.php');
@require_once('core/mailer.php');

define("LIKE",1);
define("DISLIKE",2);
define("LOVE",3);
define("HATE",4);

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

    public static function notifyCommentReaction(){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if(!isset($body["comment"]) || !is_numeric($body["comment"])){
            http_response_code(422);
            echo '{"Unprocessable Entity":"missing or incorrect data for processing"}';
            return;
        }

        $userId = SessionGuard::getUserId(); //L'utilisateur qui a réagit

        $request = "SELECT USR_notify_reaction_BOOL, USR_notification_frequency_CH, USR_email_VC 
                    FROM user 
                    INNER JOIN comment ON COM_sender_NB = USR_id_NB
                    WHERE COM_id_NB = :comment";
        $prepare = connexion::pdo()->prepare($request);
        $values["comment"] = $body["comment"];
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "user");
        $userSender = $prepare->fetch();

        echo json_encode($userSender);
        if(!$userSender->get("USR_notify_reaction_BOOL") || $userSender->get("USR_notification_frequency_CH") != 'Q'){
            return;
        }

        $request = "SELECT COM_id_NB, COM_message_VC
                    FROM comment 
                    WHERE COM_id_NB = :comment";
        $prepare = connexion::pdo()->prepare($request);
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "comment");
        $comment = $prepare->fetch();

        $request = "SELECT PRO_id_NB, PRO_title_VC, CMY_color_VC as PRO_color_VC
                    FROM comment 
                    INNER JOIN proposal ON PRO_id_NB = COM_proposal_NB
                    INNER JOIN community ON PRO_community_NB = CMY_id_NB
                    WHERE COM_id_NB = :comment";
        $prepare = connexion::pdo()->prepare($request);
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "proposal");
        $proposal = $prepare->fetch();

        $request = "SELECT REC_reaction_NB
                    FROM comment_reaction 
                    WHERE REC_comment_NB = :comment AND REC_user_NB = :user";
        $prepare = connexion::pdo()->prepare($request);
        $values["user"] = $userId;
        $prepare->execute($values);
        $reaction = $prepare->fetch();

        $request = "SELECT * FROM user WHERE USR_id_NB = :user";
        $prepare = connexion::pdo()->prepare($request);
        unset($values["comment"]);
        $values["user"] = $userId;
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "user");
        $user = $prepare->fetch();

        $mail = Mailer::init();
        $mail->Subject = "Quelqu'un a réagit à votre commentaire";
        
        if(!$reaction){
            return;
        }

        if($reaction[0] === LIKE || $reaction[0] === LOVE){
            $htmlBody = file_get_contents('./view/mail/reaction-comment-like.html');
        }else{
            $htmlBody = file_get_contents('./view/mail/reaction-comment-dislike.html');
        }
        $htmlBody = str_replace(
            [
                '[backgroundColor]', 
                '{{firstname}}', 
                '{{lastname}}', 
                '{{comment}}', 
                '{{proposal}}', 
                '{{proposalId}}'
            ],
            [
                $proposal->get("PRO_color_VC"), 
                $user->get("USR_firstname_VC"),
                $user->get("USR_lastname_VC"),
                $comment->get("COM_message_VC"),
                $proposal->get("PRO_title_VC"),
                $proposal->get("PRO_id_NB"),
            ],
            $htmlBody
        );
        $mail->Body = $htmlBody;
        $mail->SMTPKeepAlive = true;

        try{
            $mail->addAddress($userSender->get('USR_email_VC'));
            echo Mailer::send($mail);
            $mail->SmtpClose();
        }catch (Exception $e) {
            echo "Erreur d'envoi : {$mail->ErrorInfo}";
        }
    }

    public static function notifyReactionProposal(){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if(!isset($body['firstname']) || !isset($body['lastname']) || !isset($body['reaction']) ||
           !is_numeric($body['reaction']) || !isset($body['proposal']) || !is_numeric($body['proposal']) || !isset($body['initiator']) ){
            http_response_code(422);
            echo '{"Unprocessable Entity":"missing or incorrect data for processing"}';
            return;
        }

        $request = "SELECT USR_notify_reaction_BOOL, USR_notification_frequency_CH FROM user WHERE USR_email_VC = :email";
        $prepare = connexion::pdo()->prepare($request);
        $values["email"] = $body["initiator"];
        $prepare->execute($values);
        $notify = $prepare->fetch();
        $values = array();
        echo json_encode($notify);

        if(!$notify["USR_notify_reaction_BOOL"] || $notify["USR_notification_frequency_CH"] != 'Q'){
            return;
        }

        $request = "SELECT PRO_id_NB, PRO_title_VC, CMY_color_VC as PRO_color_VC 
                    FROM proposal
                    INNER JOIN community ON CMY_id_NB = PRO_community_NB
                    WHERE PRO_id_NB = :proposal";
        $prepare = connexion::pdo()->prepare($request);
        $values["proposal"] = $body["proposal"];
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "proposal");
        $proposal = $prepare->fetch();

        $request = "SELECT nblove + nblike FROM proposal_total_reaction WHERE PRO_id_NB = :proposal";
        $prepare = connexion::pdo()->prepare($request);
        $prepare->execute($values);
        $nbReaction = $prepare->fetch();

        $mail = Mailer::init();
        $mail->Subject = "Quelqu'un a réagit à votre proposition";

        if($body['reaction'] === LIKE || $body['reaction'] === LOVE){
            $htmlBody = file_get_contents('./view/mail/reaction-proposal-like.html');
        }else{
            $htmlBody = file_get_contents('./view/mail/reaction-proposal-dislike.html');
        }
        $htmlBody = str_replace(
            [
                '[backgroundColor]', 
                '{{firstname}}', 
                '{{lastname}}', 
                '{{proposal}}', 
                '{{nbReaction}}', 
                '{{proposalId}}'
            ],
            [
                $proposal->get("PRO_color_VC"), 
                $body["firstname"],
                $body["lastname"],
                $proposal->get("PRO_title_VC"),
                $nbReaction[0],
                $proposal->get("PRO_id_NB"),
            ],
            $htmlBody
        );
        $mail->Body = $htmlBody;
        $mail->SMTPKeepAlive = true;

        try{
            $mail->addAddress($body['initiator']);
            echo Mailer::send($mail);
            $mail->SmtpClose();
        }catch (Exception $e) {
            echo "Erreur d'envoi : {$mail->ErrorInfo}";
        }
    }

}

?>