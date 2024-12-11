<?php 

@require_once('models/comment.php');
@require_once('core/sessionGuard.php');

class CommentController{

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

}
?>