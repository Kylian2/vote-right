<?php
    header('Content-Type: application/json'); // pour préciser que le contenu renvoyé est du json

    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Origin: http://178.128.171.85:3000");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    /* Entetes en cas de requete de type OPTIONS (le client vérifies les paramètres du serveur avant de faire la requete) */
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header("Access-Control-Allow-Origin: http://localhost:3000");
        header("Access-Control-Allow-Origin: http://178.128.171.85:3000");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        http_response_code(204);
        exit();
    }

    require_once 'route/routes.php'; 
    
    require_once 'config/connexion.php'; 
    connexion::connect();

    // Dispatcher la requête en fonction de l'URI et de la méthode HTTP
    if($_SERVER['REQUEST_URI'] != '/'){
        session_start();
        Router::dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    }else{
        echo '{"Bienvenue": "chez VoteRight"}';
    }
?>