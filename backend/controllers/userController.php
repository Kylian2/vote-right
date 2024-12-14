<?php

@require_once('models/user.php');
@require_once('validators/userValidator.php');

class UserController{

    public function index(){
        $users = User::getAll();
        echo json_encode($users);
    }

    public function me(){
        $user = SessionGuard::getUser();

        $values["USR_id_NB"] = $user->get('USR_id_NB');
        $values["USR_lastname_VC"] = $user->get('USR_lastname_VC');
        $values["USR_firstname_VC"] = $user->get('USR_firstname_VC');

        $userInfos = new User($values);
        echo json_encode($userInfos);
    }

    public static function role($params){
        $user = SessionGuard::getUser();
        
        echo json_encode($user->getRole($params[0]));
    }

    public static function show($params){
        $user = User::getById($params[0]);
        echo json_encode($user);
    }

}

?>