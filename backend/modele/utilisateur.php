<?php

class Utilisateur{
    public int $UTI_id_NB;
    public string $UTI_nom_VC;
    public string $UTI_prenom_VC;
    public string $UTI_email_VC;
    public string $UTI_motdepasse_VC;
    public string $UTI_adresse_VC;
    public string $UTI_codepostal_CH;
    public string $UTI_naissance_DATE;
    public string $UTI_notiffrequence_CH;
    public int $UTI_notifproposition_NB;
    public int $UTI_notifvote_NB;
    public int $UTI_notifreaction_NB;

    function __construct(int $id = NULL, string $nom = NULL, string $prenom = NULL, string $email = NULL, string $mdp = NULL, string $adresse = NULL, string $codepostal = NULL, string $naissance = NULL, string $notiffrequence = NULL, int $notifproposition = NULL, int $notifvote = NULL, int $notifreaction = NULL){
        
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
            $this->UTI_notiffrequence_CH = $notiffrequence;
            $this->UTI_notifproposition_NB = $notifproposition;
            $this->UTI_notifvote_NB = $notifvote;
            $this->UTI_notifreaction_NB = $notifreaction;
        }

        /* Classique */
        $this->UTI_nom_VC = $nom;
        $this->UTI_prenom_VC = $prenom;
        $this->UTI_email_VC = $email;
        $this->UTI_motdepasse_VC = $mdp;
        $this->UTI_adresse_VC = $adresse;
        $this->UTI_codepostal_CH = $codepostal;
        $this->UTI_naissance_DATE = $naissance;
        $this->UTI_notiffrequence_CH = 'H';
        $this->UTI_notifproposition_NB = 0;
        $this->UTI_notifvote_NB = 0;
        $this->UTI_notifreaction_NB = 0;
    }

    public static function creerUtilisateur(string $nom, string $prenom, string $email, string $mdp, string $adresse, string $codepostal, string $naissance){
        return new Utilisateur(NULL, $prenom, $nom, $email, $mdp, $adresse, $codepostal, $naissance, NULL, NULL, NULL);
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
        $requete = "SELECT * FROM utilisateur;";
        $resultat = connexion::pdo()->query($requete);
        $resultat->setFetchmode(PDO::FETCH_CLASS, "utilisateur");
        $utilisateur = $resultat->fetchAll();
        return $utilisateur;
    }

    public function insert(){

        $requete = 'INSERT INTO utilisateur(UTI_email_VC, UTI_motdepasse_VC, UTI_nom_VC, UTI_prenom_VC, UTI_adresse_VC, UTI_codepostal_CH, UTI_naissance_DATE) 
                    VALUES (:email, :motdepasse, :nom, :prenom, :adresse, :codepostal, :naissance);';
        $prepare = connexion::pdo()->prepare($requete);

        $valeurs = array();


        $valeurs["email"] = $this->UTI_email_VC;
        $valeurs["motdepasse"] = $this->UTI_motdepasse_VC;
        $valeurs["nom"] = $this->UTI_nom_VC;
        $valeurs["prenom"] = $this->UTI_prenom_VC;
        $valeurs["adresse"] = $this->UTI_adresse_VC;
        $valeurs["codepostal"] = $this->UTI_codepostal_CH;
        $valeurs["naissance"] = $this->UTI_naissance_DATE;

        try{
            $prepare->execute($valeurs);
            $id = connexion::pdo()->lastInsertId();
            $this->setId($id);
            return true;
        } catch (PDOException $e) {
            return "Erreur : " . $e->getMessage();
        }

    }

}

?>