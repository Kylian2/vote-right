<?php

@require_once('models/invitation.php');
@require_once('models/user.php');
@require_once('models/community.php');
@require_once('core/sessionGuard.php');

class InvitationController{

    /**
     * Affiche un json contenant les données de l'invitation passée dans l'URL
     * 
     * @param $params, un tableau correspondant aux paramètres attendus dans l'URL. 
     * 
     * Compositon de $params : 
     * - $params[0] = $id, l'identifiant de l'invitation recherchée. 
     * 
     * @return void renvoie des éléments concernant l'invitation
     */
    public static function show(array $params){
        $invitation = Invitation::getById($params[0]);

        if(empty($invitation)){
            $return["invitation_status"] = 'invitation already answer';
            echo json_encode($return);
            return ;
        } 

        echo json_encode($invitation);
    }

    /**
     * - Modifie l'invitation concernée dans la base de données si elle est acceptée
     * - Ajoute le membre qui accepte l'invitation dans la communauté qui l'a invitée
     * - Créer une nouvelle session au membre qui vient d'être ajouté
     * 
     * @param $params, un tableau correspondant aux paramètres attendus dans l'URL. 
     * 
     * Composition de $params : 
     * - $params[0] = $id, l'identifiant de l'invitation recherchée. 
     * 
     * La fonction attend l'élément suivant : 
     * - un code (string)
     * - un numero d'utilisateur (int)
     * 
     * Procède à des vérifications de validité avant de modifier la base
     * 
     * ex de données acceptées : 
     * 
     * {
     *   "codeSend" : "867911",
     *   "newMemberId" : 78,
     * }
     * 
     * @return void renvoie true si fonction à été exécutée correctement 
     */
    public static function accepted(array $params){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if($body === null){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing data';
            echo json_encode($return);
            return ;
        }

        if(!isset($body["codeSend"]) || !isset($body["newMemberId"])  || !isset($body["communityId"]) ||!isset($params[0])){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing data for processing';
            echo json_encode($return);
            return ;
        }

        $confirmCode["INV_id_VC"] = $params[0];
        $confirmCode["INV_code_VC"] = $body["codeSend"];

        $verifCode = Invitation::getCodeById($params[0]);
        json_encode($verifCode);

        if($verifCode === null || $confirmCode["INV_code_VC"] != $verifCode['INV_code_VC']){
            $return["Invalid code"] = 'The code entered by the user is invalid';
            echo json_encode($return);
            return ;
        }

        Invitation::accept($confirmCode);

        $member["CMY_id_NB"] = $body["communityId"];
        $member["CMY_member_NB"] = $body["newMemberId"];

        Community::addMember($member);

        $user = User::getById($member["CMY_member_NB"]);
        if($user){
            SessionGuard::start($user);
            echo json_encode(true);
        }else{
            echo json_encode(false);
            SessionGuard::stop();
        }
    } 

    /**
     * Supprime l'invitation concernée dans la base de données si elle est refusée
     * 
     * @param $params, un tableau correspondant aux paramètres attendus dans l'URL. 
     * 
     * Composition de $params : 
     * - $params[0] = $id, l'identifiant de l'invitation recherchée. 
     * 
     * La fonction attend l'élément suivant : 
     * - un code (string)
     * 
     * Procède à des vérifications de validité avant de modifier la base
     * 
     * ex de données acceptées : 
     * 
     * {
     *   "codeSend" : "867911"
     * }
     * 
     * @return void renvoie true si fonction à été exécutée correctement
     */
    public static function rejected(array $params){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if($body === null){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing data';
            echo json_encode($return);
            return ;
        }

        if(!isset($body["codeSend"]) || !isset($params[0])){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing data for processing';
            echo json_encode($return);
            return ;
        }

        $values["INV_id_VC"] = $params[0];
        $values["INV_code_VC"] = $body["codeSend"];

        $verifCode = Invitation::getCodeById($params[0]);
        json_encode($verifCode);

        if($verifCode === null || $values["INV_code_VC"] != $verifCode['INV_code_VC']){
            $return["Invalid code"] = 'The code entered by the user is invalid';
            echo json_encode($return);
            return ;
        }

        Invitation::reject($values);
        echo json_encode(true);
    }
}

?>