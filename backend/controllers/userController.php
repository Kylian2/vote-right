<?php

@require_once('models/user.php');
@require_once('validators/userValidator.php');

class UserController{

    public function index(){
        $users = User::getAll();
        echo json_encode($users);
    }

    public function name(){
        $user = SessionGuard::getUser();
        echo $user->get('USR_firstname_VC');
    }

    public static function role($params){
        $user = SessionGuard::getUser();
        
        echo json_encode($user->getRole($params[0]));
    }

}

?>