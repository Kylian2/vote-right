<?php

class User{
    public int $UTI_id_NB;
    public string $UTI_nom_VC;
    public string $UTI_prenom_VC;
    public string $UTI_email_VC;
    public ?string $UTI_motdepasse_VC; //Peut-être null (les faux utilisateurs ont un mot de passe null par défaut);
    public string $UTI_adresse_VC;
    public string $UTI_codepostal_CH;
    public string $UTI_naissance_DATE;
    public string $UTI_notiffrequence_CH;
    public int $UTI_notifproposition_NB;
    public int $UTI_notifvote_NB;
    public int $UTI_notifreaction_NB;

    function __construct(int $id = NULL, string $nom = NULL, string $prenom = NULL, string $email = NULL, string $mdp = NULL, string $adresse = NULL, string $codepostal = NULL, string $naissance = NULL, string $notiffrequence = NULL, int $notifproposition = NULL, int $notifvote = NULL, int $notifreaction = NULL){
        
        /* Classique */
        /* Récupéré avec la base de données */
        if(!is_null($id)){
            $this->UTI_id_NB = $id;
            $this->UTI_nom_VC = $nom;
            $this->UTI_prenom_VC = $prenom;
            $this->UTI_email_VC = $email;
            $this->UTI_motdepasse_VC = $mdp;
            $this->UTI_adresse_VC = $adresse;
            $this->UTI_codepostal_CH = $codepostal;
            $this->UTI_naissance_DATE = $naissance;
            $this->UTI_notiffrequence_CH = $notiffrequence ? $notiffrequence : 'H';
            $this->UTI_notifproposition_NB = $notifproposition ? $notiffrequence : 0;
            $this->UTI_notifvote_NB = $notifvote ? $notiffrequence : 0;
            $this->UTI_notifreaction_NB = $notifreaction ? $notiffrequence : 0;
        }
    }

    public static function createUser(string $nom, string $prenom, string $email, string $mdp, string $adresse, string $codepostal, string $naissance){
        return new User(-1, $prenom, $nom, $email, $mdp, $adresse, $codepostal, $naissance, NULL, NULL, NULL);
    }

    public function setId(int $id){
        $this->UTI_id_NB = $id;
    }

    //Helpers functions 

    public static function validateDate(string $date, string $format = 'Y-m-d') { 
        $d = DateTime::createFromFormat($format, $date); 
        return $d && $d->format($format) === $date; 
    } 

    //Interrogation de base de données

    public static function getAll(){
        $request = "SELECT * FROM utilisateur;";
        $result = connexion::pdo()->query($request);
        $result->setFetchmode(PDO::FETCH_CLASS, "user");
        $user = $result->fetchAll();
        return $user;
    }

    public function insert(){

        $request = 'INSERT INTO utilisateur(UTI_email_VC, UTI_motdepasse_VC, UTI_nom_VC, UTI_prenom_VC, UTI_adresse_VC, UTI_codepostal_CH, UTI_naissance_DATE) 
                    VALUES (:email, :motdepasse, :nom, :prenom, :adresse, :codepostal, :naissance);';
        $prepare = connexion::pdo()->prepare($request);

        $values = array();


        $values["email"] = $this->UTI_email_VC;
        $values["motdepasse"] = $this->UTI_motdepasse_VC;
        $values["nom"] = $this->UTI_nom_VC;
        $values["prenom"] = $this->UTI_prenom_VC;
        $values["adresse"] = $this->UTI_adresse_VC;
        $values["codepostal"] = $this->UTI_codepostal_CH;
        $values["naissance"] = $this->UTI_naissance_DATE;

        try{
            $prepare->execute($values);
            $id = connexion::pdo()->lastInsertId();
            $this->setId($id);
            return true;
        } catch (PDOException $e) {
            return "Error: : " . $e->getMessage();
        }

    }

}

?>