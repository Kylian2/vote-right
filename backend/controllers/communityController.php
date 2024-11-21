<?php

@require_once('models/community.php');
@require_once('core/sessionGuard.php');

class CommunityController{

    public static function index(){
        $userId = SessionGuard::getUserId();
        $communities = Community::communitiesOf($userId);
        echo json_encode($communities);
    }

    public static function administered(){
        $userId = SessionGuard::getUserId();
        $communities = Community::communitiesBy($userId);
        echo json_encode($communities);
    }
    
}

?>