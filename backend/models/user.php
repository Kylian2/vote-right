<?php

@require_once('models/model.php');

class User extends Model{
    public int $USR_id_NB;
    public string $USR_lastname_VC;
    public string $USR_firstname_VC;
    public string $USR_email_VC;
    public string $USR_password_VC; 
    public string $USR_address_VC;
    public string $USR_zipcode_CH;
    public string $USR_birthdate_DATE;
    public string $USR_notification_frequency_CH;
    public int $USR_notify_proposal_BOOL;
    public int $USR_notify_vote_BOOL;
    public int $USR_notify_reaction_BOOL;
    public int $USR_newsletter_BOOL;

    public function setId(int $USR_id_NB) {
        $this->USR_id_NB = $USR_id_NB;
    }

    // Helpers functions
    public static function validateDate(string $date, string $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    // Interrogation de base de données
    public static function getAll() {
        $request = "SELECT * FROM user;";
        $result = connexion::pdo()->query($request);
        $result->setFetchmode(PDO::FETCH_CLASS, "user");
        $users = $result->fetchAll();
        return $users;
    }

    public static function getByEmail($email){
        $request = "SELECT * FROM user WHERE USR_email_VC = :email";
        $prepare = connexion::pdo()->prepare($request);
        $values["email"] = $email;
        $prepare->setFetchmode(PDO::FETCH_CLASS, "user");
        $prepare->execute($values);
        $result = $prepare->fetch();
        return $result;
    }

    public function insert(string $code) {
        $request = "SELECT * FROM code WHERE COD_email_VC = :email AND COD_code_NB = :code AND COD_action_VC = 'create'";
        $prepare = connexion::pdo()->prepare($request);
        $info['code'] = $code;
        $info['email'] = $this->USR_email_VC;
        $prepare->execute($info);
        $result = $prepare->fetch();
        if(!$result){
            throw new Exception('Incorrect code');
            return;
        }else{
            $request = 'DELETE FROM code WHERE COD_email_VC = :email AND COD_code_NB = :code';
            $prepare = connexion::pdo()->prepare($request);
            $prepare->execute($info);
        }

        $request = 'INSERT INTO user(USR_email_VC, USR_password_VC, USR_lastname_VC, USR_firstname_VC, USR_address_VC, USR_zipcode_CH, USR_birthdate_DATE) 
                    VALUES (:USR_email_VC, :USR_password_VC, :USR_lastname_VC, :USR_firstname_VC, :USR_address_VC, :USR_zipcode_CH, :USR_birthdate_DATE);';
        $prepare = connexion::pdo()->prepare($request);

        $values = array();

        $values["USR_email_VC"] = $this->USR_email_VC;
        $values["USR_password_VC"] = $this->USR_password_VC;
        $values["USR_lastname_VC"] = $this->USR_lastname_VC;
        $values["USR_firstname_VC"] = $this->USR_firstname_VC;
        $values["USR_address_VC"] = $this->USR_address_VC;
        $values["USR_zipcode_CH"] = $this->USR_zipcode_CH;
        $values["USR_birthdate_DATE"] = $this->USR_birthdate_DATE;

        $prepare->execute($values);
        $id = connexion::pdo()->lastInsertId();
        $this->setId($id);
        return true;
    }

    public function updateInformation() {

        $request = "UPDATE user
                    SET USR_email_VC = :USR_email_VC, USR_address_VC = :USR_address_VC, USR_zipcode_CH = :USR_zipcode_CH, USR_birthdate_DATE = :USR_birthdate_DATE
                    WHERE USR_id_NB = :USR_id_NB;";

        $prepare = connexion::pdo()->prepare($request);

        $values = array();

        $values["USR_email_VC"] = $this->USR_email_VC;
        $values["USR_address_VC"] = $this->USR_address_VC;
        $values["USR_zipcode_CH"] = $this->USR_zipcode_CH;
        $values["USR_birthdate_DATE"] = $this->USR_birthdate_DATE;
        $values["USR_id_NB"] = $this->USR_id_NB;     

        $prepare->execute($values);
        return true;
    }

    public function updatePassword() {

        $request = "UPDATE user
                    SET USR_password_VC = :USR_password_VC
                    WHERE USR_id_NB = :USR_id_NB;";

        $prepare = connexion::pdo()->prepare($request);

        $values = array();

        $values["USR_password_VC"] = $this->USR_password_VC;
        $values["USR_id_NB"] = $this->USR_id_NB;     

        $prepare->execute($values);
        return true;
    }

    public function updateNotification() {

        $request = "UPDATE user
                    SET USR_notify_proposal_BOOL = :USR_notify_proposal_BOOL, USR_notify_vote_BOOL = :USR_notify_vote_BOOL, USR_notify_reaction_BOOL = :USR_notify_reaction_BOOL, USR_notification_frequency_CH = :USR_notification_frequency_CH
                    WHERE USR_id_NB = :USR_id_NB;";

        $prepare = connexion::pdo()->prepare($request);

        $values = array();

        $values["USR_notify_proposal_BOOL"] = $this->USR_notify_proposal_BOOL;  
        $values["USR_notify_vote_BOOL"] = $this->USR_notify_vote_BOOL;  
        $values["USR_notify_reaction_BOOL"] = $this->USR_notify_reaction_BOOL;  
        $values["USR_notification_frequency_CH"] = $this->USR_notification_frequency_CH;  
        $values["USR_id_NB"] = $this->USR_id_NB;     

        $prepare->execute($values);
        return true;
    }

    public function getRole(int $community){
        $request = "SELECT MEM_role_NB, ROL_label_VC FROM members_role WHERE USR_id_NB = :user AND MEM_community_NB = :community";
        $prepare = connexion::pdo()->prepare($request);

        $values["user"] = $this->USR_id_NB;
        $values["community"] = $community;

        $prepare -> execute($values);
        $prepare->setFetchmode(PDO::FETCH_OBJ);
        $result = $prepare->fetch();
        
        return $result;
    }

    public function getRoleProposal(int $proposal){
        $request = "SELECT MEM_role_NB, ROL_label_VC FROM members_role WHERE USR_id_NB = :user AND MEM_community_NB = (SELECT PRO_community_NB FROM proposal WHERE PRO_id_NB = :proposal)";
        $prepare = connexion::pdo()->prepare($request);

        $values["user"] = $this->USR_id_NB;
        $values["proposal"] = $proposal;

        $prepare -> execute($values);
        $prepare->setFetchmode(PDO::FETCH_OBJ);
        $result = $prepare->fetch();
        
        return $result;
    }

    public static function getById(int $id){
        $request = "SELECT USR_id_NB, USR_lastname_VC, USR_firstname_VC, USR_email_VC FROM user WHERE USR_id_NB = :id";
        $prepare = connexion::pdo()->prepare($request);
        $values["id"] = $id;    
        $prepare->setFetchmode(PDO::FETCH_CLASS, "user");
        $prepare->execute($values);
        $user = $prepare->fetch();
        return $user;
    }

    public function delete(){
        $prepare = connexion::pdo()->prepare("CALL delete_user(:user)");
        $values['user'] = $this->get('USR_id_NB');
        $prepare->execute($values);

        return true;
    }


}

?>