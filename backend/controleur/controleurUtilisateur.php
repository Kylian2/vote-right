<?php

@require_once('modele/utilisateur.php');

class ControleurUtilisateur{

    public function index(){
        $utilisateur = utilisateur::getAll();
        echo json_encode($utilisateur);
    }

    public function store(){

        $body = file_get_contents('php://input');

        //Verifier l'unicité de l'email

        //mettre en place une validation des entrées et vérifier que toutes les données sont reçus
        //sinon 422 Unprocessable entity

        //mettre en place un cryptage de mot de passe
    
        // Décoder le JSON en tableau associatif
        $userData = json_decode($body, true);
        
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