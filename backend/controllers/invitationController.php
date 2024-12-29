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
     * La fonction attend les éléments suivants : 
     * - un code (int)
     * - un numero de communauté (int)
     * - un numero d'utilisateur (int)
     * 
     * Procède à des vérifications de validité avant de modifier la base
     * 
     * ex de données acceptées : 
     * 
     * {
     *  "codeSend" : 744670,
     *  "communityId" : 12,
     *  "newMemberId" : 35
     * }
     * 
     * @return void renvoie true si l'objectif de la fonction a été rempli
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

        $IdInvitation["INV_id_VC"] = $params[0];
        $codeUtilisateur["INV_code_NB"] = $body["codeSend"];

        $codeInvitation = Invitation::getCodeById($IdInvitation["INV_id_VC"]);

        if($codeInvitation === null || $codeUtilisateur["INV_code_NB"] != $codeInvitation["INV_code_NB"]){
            $return["Invalid code"] = 'The code entered by the user is invalid';
            echo json_encode($return);
            return ;
        }

        Invitation::accept($IdInvitation);

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
     * - un code (int)
     * 
     * Procède à des vérifications de validité avant de modifier la base
     * 
     * ex de données acceptées : 
     * 
     * {
     *   "codeSend" : 867911
     * }
     * 
     * @return void renvoie true si l'objectif de la fonction a été rempli
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

        $IdInvitation["INV_id_VC"] = $params[0];
        $codeUtilisateur["INV_code_NB"] = $body["codeSend"];

        $codeInvitation = Invitation::getCodeById($IdInvitation["INV_id_VC"]);

        if($codeInvitation === null || $codeUtilisateur["INV_code_NB"] != $codeInvitation["INV_code_NB"]){
            $return["Invalid code"] = 'The code entered by the user is invalid';
            echo json_encode($return);
            return ;
        }

        Invitation::reject($IdInvitation);
        echo json_encode(true);
    }
}

?>