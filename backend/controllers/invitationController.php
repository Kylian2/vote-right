<?php

@require_once('models/invitation.php');

class InvitationController{

    /**
     * Affiche un json contenant les données de l'invitation passée dans l'URL
     * 
     * @param $params, un tableau correspondant aux paramètres attendus dans l'URL. 
     * 
     * Compositon de $params : 
     * - $params[0] = $id, l'identifiant de l'invitation recherchée. 
     */
    public static function show(array $params){
        $invitation = Invitation::getById($params[0]);
        echo json_encode($invitation);
    }

    public static function accept(){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if($body === null){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing data';
            echo json_encode($return);
            return;
        }

        if(!isset($body["newMemberId"]) || !isset($body["invitationId"]) ){
            http_response_code(422);
            echo '{"Unprocessable Entity":"missing data for processing"}';
            return;
        }

        $values = [
            "INV_id_VC" => $body["invitationId"],
            "INV_recipient_VC" => $body["newMemberId"],
        ];

        $invitation = Invitation::update($values);
        echo json_encode($invitation);
    }

    public static function reject(){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if($body === null){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing data';
            echo json_encode($return);
            return;
        }

        if(!isset($body["newMemberId"]) || !isset($body["invitationId"]) ){
            http_response_code(422);
            echo '{"Unprocessable Entity":"missing data for processing"}';
            return;
        }

        $values = [
            "INV_id_VC" => $body["invitationId"],
            "INV_recipient_VC" => $body["newMemberId"],
        ];

        $invitation = Invitation::update($values);
        echo json_encode($invitation);
    }
}

?>