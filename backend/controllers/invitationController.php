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

    /**
     * Modifie l'invitation concernée dans la base de données
     * 
     * @param $params, un tableau correspondant aux paramètres attendus dans l'URL. 
     * 
     * Compositon de $params : 
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
     *   "codeSend" : "867911",
     * }
     * 
     * @return void renvoie l'invitation au format json si la modification à été faite
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
            echo '{"Unprocessable Entity":"missing data for processing"}';
            return ;
        }

        $values["INV_id_VC"] = $params[0];
        $values["INV_code_VC"] = $body["codeSend"];

        $verifCode = Invitation::getCodeById($params[0]);

        if($verifCode === null || $values["INV_code_VC"] != $verifCode){
            http_response_code(401);
            echo '{"invalid code":"the code entered by the user is invalid"}';
            return ;
        }

        Invitation::accept($values);
        $invitation = Invitation::getById($params[0]);
        echo json_encode($invitation);
        return true;
    }

    /**
     * Supprime l'invitation concernée dans la base de données
     * 
     * @param $params, un tableau correspondant aux paramètres attendus dans l'URL. 
     * 
     * Compositon de $params : 
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
     *   "codeSend" : "867911",
     * }
     * 
     * @return void renvoie un message de confirmation de suppression
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
            echo '{"Unprocessable Entity":"missing data for processing"}';
            return ;
        }

        $values["INV_id_VC"] = $params[0];
        $values["INV_code_VC"] = $body["codeSend"];

        $verifCode = Invitation::getCodeById($params[0]);

        if($verifCode === null || $values["INV_code_VC"] != $verifCode){
            http_response_code(401);
            echo '{"invalid code":"the code entered by the user is invalid"}';
            return ;
        }

        $invitation = Invitation::reject($values);
        echo "Supression effectuée avec succès";
        return true;
    }
}

?>