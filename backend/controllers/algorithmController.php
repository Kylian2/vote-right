<?php

class algorithmController{
    
    /**
     * REQUETE À API ALGORITHM
     * Lance l'algorithme de recommandation pour la communauté passé via l'url pour la periode donnée.
     * 
     * $_GET['period'] : la periode de traitement
     * 
     * @param array $params : $params[0] l'id de la communauté
     */
    public static function proposal(array $params){

        if(!isset($_GET['period']) || !is_numeric($_GET['period'])){
            http_response_code(400);
            echo json_encode(array('error' => 'period is incorrect or not found'));
            return;
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $_ENV['ALGO_API'].'/proposals?community='.$params[0].'&period='.$_GET['period'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'X-API-KEY: '.$_ENV['KEY']
        ),
        ));

        $response = curl_exec($curl);

        if(curl_errno($curl)){
            http_response_code(500);
            echo 'Erreur cURL :'.curl_error($curl);
        }
        curl_close($curl);
        http_response_code(curl_getinfo($curl, CURLINFO_HTTP_CODE));
        echo $response;
    }

    /**
     * Permet de tester la connexion à l'api algorithms
     */
    public static function testApi(){
        //paramètre à ajuster en fonction de l'environnement
        $host = 'host.docker.internal:3334';
        $port = 3334;
        $timeout = 2; // 2 secondes
        
        $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if ($connection) {
            echo "Connexion réussie à $host:$port";
            fclose($connection);
        } else {
            echo "Erreur de connexion à $host:$port - $errstr ($errno)";
        }
    }

}