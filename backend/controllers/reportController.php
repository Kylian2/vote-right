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
    public static function index($params){
        $reports = Report::getAllOf($params[0]);
        echo json_encode($reports);
    }

    /**
     * Mets à jour un signalement dans l'une des deux situations suivantes : 
     *      - Le signalement est résolu
     *      - Le commentaire pointé par le signalement est supprimé
     * 
     * @param $params, un tableau correspondant aux paramètres attendus dans l'URL (la double clé primaire identifiant un report). 
     * 
     * Composition de $params : 
     * - $params[0] = $user, l'identifiant de l'utilisateur ayant signalé. 
     * - $params[1] = $comment, l'identifiant du commentaire signalé. 
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

        if($body === null){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing data';
            echo json_encode($return);
            return ;
        }

        if(!isset($body["delete"]) || !isset($params[0]) || !isset($params[0]) || !is_bool($body['delete'])){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing data for processing';
            echo json_encode($return);
            return ;
        }

        $moderator = SessionGuard::getUserId();

        $values['RPT_user_NB'] = intval($params[0]);
        $values['RPT_comment_NB'] = intval($params[1]);

        $report = new Report($values);
        
        $result = $body['delete'] ? $report->delete($moderator) : $report->close($moderator);

        if($result == false){
            http_response_code(403);
            $return["Error"] = 'You have no rights on this action';
            echo json_encode($return);
            return ;
        }

        echo json_encode(true);
    }
}

?>