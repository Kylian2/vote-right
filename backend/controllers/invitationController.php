<?php

@require_once('models/invitation.php');
@require_once('models/community.php');
@require_once('models/user.php');
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
    public static function show($params){
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
     * }
     * 
     * @return void renvoie true si l'objectif de la fonction a été rempli
     */
    public static function accepted($params){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if($body === null){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing data';
            echo json_encode($return);
            return ;
        }

        if(!isset($body["codeSend"]) ||!isset($params[0])){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing data for processing';
            echo json_encode($return);
            return ;
        }

        $invitationId = $params[0];

        $invitationCode = Invitation::getCodeById($invitationId);
        $invitation = Invitation::getById($invitationId);

        if($invitationCode === null || $body["codeSend"] != $invitationCode){
            $return["Invalid code"] = 'The code entered by the user is invalid';
            echo json_encode($return);
            return ;
        }

        Invitation::accept($invitationId);

        $community = new Community(array('CMY_id_NB' => $invitation->get('INV_community_NB')));

        $community->addMember($invitation->get('INV_recipient_NB'));
        $user = User::getById($invitation->get('INV_recipient_NB'));

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
    public static function rejected($params){
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

        $invitationId = $params[0];

        $invitationCode = Invitation::getCodeById($invitationId);

        if($invitationCode === null || $body["codeSend"] != $invitationCode){
            $return["Invalid code"] = 'The code entered by the user is invalid';
            echo json_encode($return);
            return ;
        }

        Invitation::reject($invitationId);
        echo json_encode(true);
    }
}
?>
