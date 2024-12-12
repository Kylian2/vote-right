<?php 

@require_once('models/comment.php');
@require_once('core/sessionGuard.php');

class CommentController{

    /**
     * Enregistre le commentaire dans la base de donnée. 
     * 
     * Le body passé par la méthode POST doit contenir : 
     * - message : un string contenant le message (taille < 250)
     * - proposal: l'identifiant de la proposition
     * 
     * @return void le commentaire inseré dans la base
     */
    public static function store(){

        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if($body === null){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing data';
            echo json_encode($return);
            return;
        }

        $sender = SessionGuard::getUserId();

        $values["COM_message_VC"] = $body["message"];
        $values["COM_proposal_NB"] = $body["proposal"];
        $values["COM_sender_NB"] = $sender;

        $comment = new Comment($values);

        $result = $comment->insert();

        echo json_encode($comment);
    }

    /**
     * Affiche les reactions sur le commentaire dont l'identifiant est passé en paramètre
     * 
     * @param array $params un tableau composé des paramètre de l'url. $params[0] contient l'identifiant du commentaire.
     * 
     * @return void les données sont affichés en JSON
     */
    public static function reactions(array $params){
        $values["COM_id_NB"] = $params[0];
        $comment = new Comment($values);
        $user = SessionGuard::getUserId();
        $reactions = $comment->getReactions($user);
        echo json_encode($reactions);
    }

    /**
     * Permet de reagir à un commentaire
     * 
     * @param array $params les paramètres de l'url ($params[0] contient l'indentifiant du commentaire);
     * 
     * @return bool true si l'utilisateur a pu réagir, false sinon
     */
    public static function react($params){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if(!isset($body['reaction']) || !is_numeric($body['reaction'])){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing or incorrect data';
            echo json_encode($return);
            return;
        }

        $userId = SessionGuard::getUserId();

        $result = Comment::react($params[0], $body['reaction'], $userId);

        echo json_encode($result);
    }

}
?>