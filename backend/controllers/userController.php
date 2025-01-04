<?php

@require_once('models/user.php');
@require_once('validators/userValidator.php');
@require_once('core/sessionGuard.php');

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
        $values["USR_email_VC"] = $user->get('USR_email_VC');
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

     /**
     * Met à jour les informations d'un utilisateur
     *
     * Données attendues : birthdate (Y-m-d), address, zipcode, email
     *
     * @return void
     * - 422 avec un message JSON si l'entrée est invalide
     * - 400 avec un message JSON en cas d'erreur lors de la mise à jour d'une donnée.
     * - true (JSON) si la mise à jour réussit.
     */
    public function editInformation(){

        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        // Vérifier que toutes les données sont reçues
        if(!isset($body["birthdate"]) || !isset($body["address"]) || !isset($body["zipcode"]) || !isset($body["email"])){
            http_response_code(422);
            echo '{"Unprocessable Entity":"missing data for processing"}';
            return;
        }

        //Validation des données
        try{
            UserValidator::informationDataValidator($body);
        }catch (Error $e){
            http_response_code(422);
            $return["Unprocessable Entity"] = $e->getMessage();
            echo json_encode($return);
            return;
        }

        $user = SessionGuard::getUser();

        $user->USR_birthdate_DATE = $body["birthdate"];
        $user->USR_address_VC = $body["address"];
        $user->USR_zipcode_CH = $body["zipcode"];
        $user->USR_email_VC = $body["email"];

        try{
            $user->updateInformation();
        }catch(Exception $e){
            http_response_code(400);
            $return["Erreur"] = $e->getMessage();
            echo json_encode($return);
            return;
        }

        echo json_encode(true);
    }

    /**
     * Met à jour le mot de passe d'un utilisateur avec la donnée passée dans la requête 
     *
     * Donnée attendue : password
     *
     * @return void
     * - 422 avec un message JSON si l'entrée est invalide
     * - 400 avec un message JSON en cas d'erreur lors de la mise à jour d'une donnée.
     * - true (JSON) si la mise à jour réussit.
     */
    public function editPassword(){

        $body = file_get_contents('php://input');

        // Décoder le JSON en tableau associatif
        $body = json_decode($body, true);

        // Vérifier que toutes les données sont reçues
        if(!isset($body["password"])){
            http_response_code(422);
            echo '{"Unprocessable Entity":"missing data for processing"}';
            return;
        }

        $user = SessionGuard::getUser();

        $user->USR_password_VC = password_hash($body["password"], PASSWORD_ARGON2ID);

        try{
            $user->updatePassword();
        }catch(Exception $e){
            http_response_code(400);
            $return["Erreur"] = $e->getMessage();
            echo json_encode($return);
            return;
        }

        echo json_encode(true);
    }

    /**
     * Met à jour les paramètres de notification d'un utilisateur avec les données passées dans la requête
     *
     * Donnée attendue : newProposal, startOfVoting, reactionToTheProposals, notificationFrequency
     *
     * @return void
     * - 422 avec un message JSON si l'entrée est invalide
     * - 400 avec un message JSON en cas d'erreur lors de la mise à jour d'une donnée.
     * - true (JSON) si la mise à jour réussit.
     */
    public function editNotification(){

        $body = file_get_contents('php://input');

        // Décoder le JSON en tableau associatif
        $body = json_decode($body, true);

        // Vérifier que toutes les données sont reçues
        if(!isset($body["newProposal"]) || !isset($body["startOfVoting"]) || !isset($body["reactionToTheProposals"]) || !isset($body["notificationFrequency"])){
            http_response_code(422);
            echo '{"Unprocessable Entity":"missing data for processing"}';
            return;
        }

        $user = SessionGuard::getUser();

        $user->USR_notify_proposal_BOOL = $body["newProposal"];
        $user->USR_notify_vote_BOOL = $body["startOfVoting"];
        $user->USR_notify_reaction_BOOL = $body["reactionToTheProposals"];
        $user->USR_notification_frequency_CH = $body["notificationFrequency"];

        try{
            $user->updateNotification();
        }catch(Exception $e){
            http_response_code(400);
            $return["Erreur"] = $e->getMessage();
            echo json_encode($return);
            return;
        }

        echo json_encode(true);
    }

    public static function role($params){
        $user = SessionGuard::getUser();
        
        echo json_encode($user->getRole($params[0]));
    }

    public static function roleProposal($params){
        $user = SessionGuard::getUser();
        
        echo json_encode($user->getRoleProposal($params[0]));
    }

    public static function show($params){
        $user = User::getById($params[0]);
        echo json_encode($user);
    }

}

?>