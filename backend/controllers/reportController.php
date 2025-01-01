<?php

@require_once('models/report.php');
@require_once('models/proposal.php');
@require_once('core/sessionGuard.php');

define("ROLE_ADMIN",1);
define("ROLE_MODERATOR",4);

class reportController{
    
    /**
     * Affiche un json contenant les informations de chacun des signalements dans le groupe recherché
     * 
     * $params[0] contient l'identifiant du groupe dont on recherche les signalements
     * 
     * @param array $params un tableau contenant les paramètres contenus dans l'URL
     * 
     * @return void les données des signalements sont renvoyées en JSON par echo
     */
    public static function show($params){
        $allProposal = Proposal::allOfCommunity($params[0]);

        $proposalArray = array ();
        foreach ($allProposal as $proposal) {
            $proposalArray[] = get_object_vars($proposal);
        }

        $allReports = array();
        foreach ($proposalArray as $proposal) {
            if (isset($proposal['PRO_id_NB'])) {
                $reports = Report::getByProposal($proposal['PRO_id_NB']);
                $allReports = array_merge($allReports, $reports);
            }
        }
        echo json_encode($allReports);
    }

    /**
     * Mets à jour un signalement dans l'une des deux situations suivantes : 
     *      - Le signalement est résolu
     *      - Le commentaire pointé par le signalement est supprimé
     * 
     * @param $params, un tableau correspondant aux paramètres attendus dans l'URL. 
     * 
     * Composition de $params : 
     * - $params[0] = $comment, l'identifiant du commentaire signalé. 
     * - $params[1] = $community, l'identifiant de la communauté dans laquelle le commentaire est signalé. 
     * 
     * La fonction attend l'élément suivant : 
     * - une information sur le type d'action à effectuer (bool)
     * 
     * Procède à une vérification au niveau des droits avant de modifier la base
     * 
     * ex de données acceptées : 
     * 
     * {
     *  "delete" : true,
     * }
     * 
     * @return void renvoie true si l'objectif de la fonction a été rempli
     */
    public static function solvReport($params){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        $return["Ok"] = 'mission accomplish';
        echo json_encode($return);

        if($body === null){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing data';
            echo json_encode($return);
            return ;
        }

        if(!isset($body["delete"]) || !isset($params[0]) || !isset($params[0])){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing data for processing';
            echo json_encode($return);
            return ;
        }
        
        $commentId = intval($params[0]);
        $communityId = intval($params[1]);
        $userId = SessionGuard::getUserId();

        $user = SessionGuard::getUser();
        $userInformation = $user->getRole($communityId);

        if($userInformation['MEM_role_NB'] != (ROLE_ADMIN || ROLE_MODERATOR)){
            http_response_code(403);
            $return["Error"] = 'You have no rights on this action';
            echo json_encode($return);
            return ;
        }

        if($body["delete"] == true){
            $values['RPT_user_NB'] = $userId;
            $values['RPT_comment_NB'] = $commentId;
            Report::deleteById($values);
        }else{
            $values['RPT_user_NB'] = $userId;
            $values['RPT_comment_NB'] = $commentId;
            Report::resolveById($values);
        }

        $return["Ok"] = 'mission accomplish';
        echo json_encode($return);
    }
}

?>