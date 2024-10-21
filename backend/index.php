<?php
    header('Content-Type: application/json'); // pour préciser que le contenu renvoyé est du json

    require_once 'controleur/controleurUtilisateur.php'; // Inclure le contrôleur

    require_once 'route/routes.php'; 
    
    require_once 'config/connexion.php'; 
    connexion::connect();

    // Dispatcher la requête en fonction de l'URI et de la méthode HTTP
    if($_SERVER['REQUEST_URI'] != '/'){
        Router::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    }else{
        echo '{"Bienvenue": "chez VoteRight"}';
    }
?>