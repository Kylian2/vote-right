<?php

@require_once('modele/utilisateur.php');
@require_once('validateur/utilisateurValidateur.php');

class ControleurUtilisateur{

    public function index(){
        $utilisateur = utilisateur::getAll();
        echo json_encode($utilisateur);
    }

    public function store(){

        $body = file_get_contents('php://input');

        // Décoder le JSON en tableau associatif
        $userData = json_decode($body, true);

        //Vérifier que toutes les données sont reçus
        if(!isset($userData["email"]) || !isset($userData["motdepasse"]) || !isset($userData["nom"]) 
        || !isset($userData["prenom"]) || !isset($userData["adresse"]) || !isset($userData["codepostal"])|| !isset($userData["naissance"])){
            http_response_code(422);
            echo '{"Unprocessable Entity":"missing data for processing"}';
            return;
        }

        //Validation des données
        $validate = UtilisateurValidateur::creationDataValidateur($userData);
        if($validate !== true){
            http_response_code(422);
            echo json_encode($validate);
            return;
        }

        //mettre en place un cryptage de mot de passe
        $valeurs["email"] = $userData["email"];
        $valeurs["motdepasse"] = $userData["motdepasse"];
        $valeurs["nom"] = $userData["nom"];
        $valeurs["prenom"] = $userData["prenom"];
        $valeurs["adresse"] = $userData["adresse"];
        $valeurs["codepostal"] = $userData["codepostal"];
        $valeurs["naissance"] = $userData["naissance"];

        $utilisateur = Utilisateur::creerUtilisateur($valeurs["nom"], $valeurs["prenom"], $valeurs["email"], $valeurs["motdepasse"], $valeurs["adresse"], $valeurs["codepostal"], $valeurs["naissance"]);

        $resultat = $utilisateur->insert();

        if($resultat === true){
            echo json_encode($utilisateur);
        }else{
            echo json_encode($resultat);
        }
    }
}

?>