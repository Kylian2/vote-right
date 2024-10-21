<?php

class Utilisateur{
    public int $UTI_id_NB;
    public string $UTI_nom_VC;
    public string $UTI_prenom_VC;
    public string $UTI_email_VC;
    public string $UTI_adresse_VC;
    public string $UTI_codepostal_CH;
    public string $UTI_naissance_DATE;
    public string $UTI_notiffrequence_CH;
    public int $UTI_notifproposition_NB;
    public int $UTI_notifvote_NB;
    public int $UTI_notifreaction_NB;

    function __construct(int $id = NULL, string $nom = NULL, string $prenom = NULL, string $email = NULL, string $adresse = NULL, string $codepostal = NULL, string $naissance = NULL, string $notiffrequence = NULL, int $notifproposition = NULL, int $notifvote = NULL, int $notifreaction = NULL){
        if(!is_null($id)){
            $this->UTI_id_NB = $id;
            $this->UTI_nom_VC = $nom;
            $this->UTI_prenom_VC = $prenom;
            $this->UTI_email_VC = $email;
            $this->UTI_adresse_VC = $adresse;
            $this->UTI_codepostal_CH = $codepostal;
            $this->UTI_naissance_DATE = $naissance;
            $this->UTI_notiffrequence_CH = $notiffrequence;
            $this->UTI_notifproposition_NB = $notifproposition;
            $this->UTI_notifvote_NB = $notifvote;
            $this->UTI_notifreaction_NB = $notifreaction;
        }
    }

    public static function getAll(){
        $requete = "SELECT * FROM utilisateur;";
        $resultat = connexion::pdo()->query($requete);
        $resultat->setFetchmode(PDO::FETCH_CLASS, "utilisateur");
        $utilisateur = $resultat->fetchAll();
        return $utilisateur;
    }

}

?>