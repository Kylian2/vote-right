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
}

?>