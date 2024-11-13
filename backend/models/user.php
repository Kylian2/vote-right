<?php

@require_once('models/model.php');

class User extends Model{
    public int $USR_id_NB;
    public string $USR_lastname_VC;
    public string $USR_firstname_VC;
    public string $USR_email_VC;
    public ?string $USR_password_VC; // Peut-être null (les faux utilisateurs ont un mot de passe null par défaut)
    public string $USR_address_VC;
    public string $USR_zipcode_CH;
    public string $USR_birthdate_DATE;
    public string $USR_notification_frequency_CH;
    public int $USR_notify_proposal_BOOL;
    public int $USR_notify_vote_BOOL;
    public int $USR_notify_reaction_BOOL;

    function __construct(int $USR_id_NB = NULL, string $USR_lastname_VC = NULL, string $USR_firstname_VC = NULL, string $USR_email_VC = NULL, string $USR_password_VC = NULL, string $USR_address_VC = NULL, string $USR_zipcode_CH = NULL, string $USR_birthdate_DATE = NULL, string $USR_notification_frequency_CH = NULL, int $USR_notify_proposal_BOOL = NULL, int $USR_notify_vote_BOOL = NULL, int $USR_notify_reaction_BOOL = NULL) {

        /* Classique */
        /* Récupéré avec la base de données */
        if (!is_null($USR_id_NB)) {
            $this->USR_id_NB = $USR_id_NB;
            $this->USR_lastname_VC = $USR_lastname_VC;
            $this->USR_firstname_VC = $USR_firstname_VC;
            $this->USR_email_VC = $USR_email_VC;
            $this->USR_password_VC = $USR_password_VC;
            $this->USR_address_VC = $USR_address_VC;
            $this->USR_zipcode_CH = $USR_zipcode_CH;
            $this->USR_birthdate_DATE = $USR_birthdate_DATE;
            $this->USR_notification_frequency_CH = $USR_notification_frequency_CH ? $USR_notification_frequency_CH : 'H';
            $this->USR_notify_proposal_BOOL = $USR_notify_proposal_BOOL? $USR_notify_proposal_BOOL : 0;
            $this->USR_notify_vote_BOOL = $USR_notify_vote_BOOL ? $USR_notify_vote_BOOL : 0;
            $this->USR_notify_reaction_BOOL = $USR_notify_reaction_BOOL ? $USR_notify_reaction_BOOL : 0;
        }
    }

    public static function createUser(string $USR_lastname_VC, string $USR_firstname_VC, string $USR_email_VC, string $USR_password_VC, string $USR_address_VC, string $USR_zipcode_CH, string $USR_birthdate_DATE) {
        return new User(-1, $USR_lastname_VC, $USR_firstname_VC, $USR_email_VC, $USR_password_VC, $USR_address_VC, $USR_zipcode_CH, $USR_birthdate_DATE, NULL, NULL, NULL);
    }

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

    public function insert() {
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

}

?>