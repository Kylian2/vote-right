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
     * Modifie l'invitation concernée dans la base de données si elle est acceptée
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
     * @return void renvoie un message pour confirmer la modification
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

        Invitation::accept($values);
        echo "Modification effectuée avec succès";
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
     * @return void renvoie un message pour confirmer la suppression de l'invitation
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
        echo "Supression effectuée avec succès";
    }
}

?>