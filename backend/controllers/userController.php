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
        $values["USR_address_VC"] = $user->get('USR_address_VC');
        $values["USR_zipcode_CH"] = $user->get('USR_zipcode_CH');
        $values["USR_birthdate_DATE"] = $user->get('USR_birthdate_DATE');
        $values["USR_notification_frequency_CH"] = $user->get('USR_notification_frequency_CH');
        $values["USR_notify_proposal_BOOL"] = $user->get('USR_notify_proposal_BOOL');
        $values["USR_notify_vote_BOOL"] = $user->get('USR_notify_vote_BOOL');
        $values["USR_notify_reaction_BOOL"] = $user->get('USR_notify_reaction_BOOL');
        $values["USR_newsletter_BOOL"] = $user->get('USR_newsletter_BOOL');

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