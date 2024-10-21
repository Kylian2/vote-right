<?php

@require_once('modele/utilisateur.php');

class ControleurUtilisateur{

    public function index(){
        $utilisateur = utilisateur::getAll();
        echo json_encode($utilisateur);
    }
}

?>