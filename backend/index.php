<?php
    header('Content-Type: application/json'); // pour préciser que le contenu renvoyé est du json

    $allowed_origins = [
        'http://localhost:3000',
        'https://voteright.fr',
        'https://www.voteright.fr',
    ];

    // Récupérer l'origine de la requête
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

    // Vérifiez si l'origine est dans la liste des origines autorisées
    if (in_array($origin, $allowed_origins)) {
        header("Access-Control-Allow-Origin: $origin");
    }

    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    /* Entetes en cas de requete de type OPTIONS (le client vérifies les paramètres du serveur avant de faire la requete) */
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        if (in_array($origin, $allowed_origins)) {
            header("Access-Control-Allow-Origin: $origin");
        }
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        http_response_code(204);
        exit();
    }

    ini_set('session.cookie_secure', 1);


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