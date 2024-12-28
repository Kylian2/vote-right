<?php

@require_once('models/invitation.php');
@require_once('models/community.php');
@require_once('core/sessionGuard.php');
@require_once('controllers/notificationController.php');

class InvitationController{

    /**
     * Enregistre une invitation
     * 
     * Le body attend deux éléments :
     * - `int`community : l'identifiant de la communauté
     * - `array` invitations: une liste d'email
     * 
     * @return void renvoie au format json `true` si des invitations ont été envoyées
     * 
     */
    public static function store(){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if(!isset($body['community']) || !is_numeric($body['community']) || !isset($body['invitations'])){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing data';
            echo json_encode($return);
            return;
        }

        $sender = SessionGuard::getUser();
        $values['INV_sender_NB'] = $sender->get('USR_id_NB');
        $values['INV_community_NB'] = $body['community'];

        $community = Community::getById($body['community']);

        $invitationSended = 0;

        foreach($body['invitations'] as $email){
            $values['INV_recipient_email_VC'] = $email;
            $invitation = new Invitation($values);
            $result = $invitation->insert();
            while($result === "Already used id"){
                $result = $invitation->insert();
            }
            if($result === true){
                if(notificationController::sendInvitation($email, $sender, $invitation, $community)){
                    $invitationSended++;
                }
            }
        }

        if($invitationSended != count($body["invitations"])){
            http_response_code(206);
            echo json_encode([
                "message" => "Some invitations could not be sent",
                "sent" => $invitationSended,
                "expected" => count($body["invitations"])
            ]);
            return;
        }

        echo json_encode(true);
    }
}
?> 