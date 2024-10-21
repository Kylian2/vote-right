<?php 

class Router{
    private static $routes = array();

    /**
     * Ajoute une route à la liste
     * 
     * @param string $methode Le type de requete (GET, POST, etc).
     * @param string $uri L'uri de la route
     * @param string $controleurAction Le controleur et l'action à effectuer, ils doivent etre de la forme controleur@action
     */
    public static function addRoute(string $methode, string $uri, string $controleurAction){
        self::$routes[] = [
            'methode' => $methode,
            'uri' => trim($uri, '/'), #trim retire le '/' au début et en fin de chaine
            'controleurAction' => $controleurAction
        ];
    }

    /**
     * Défini une route GET 
     * 
     * @param string $uri L'uri de la route
     * @param string $controleurAction Le controleur et l'action à effectuer, ils doivent etre de la forme controleur@action
     */
    public static function get(string $uri, string $controleurAction){
        self::addRoute('GET', $uri, $controleurAction);
    }

    /**
     * Défini une route POST 
     * 
     * @param string $uri L'uri de la route
     * @param string $controleurAction Le controleur et l'action à effectuer, ils doivent etre de la forme controleur@action
     */
    public static function post(string $uri, string $controleurAction){
        self::addRoute('POST', $uri, $controleurAction);
    }

    /**
     * Lance une action d'un controleur
     * 
     * @param string $controleurAction Le controleur et l'action à effectuer, ils doivent etre de la forme controleur@action
     */
    protected static function callAction($controleurAction)
    {
        list($controleur, $action) = explode('@', $controleurAction); #permet de recuperer le controleur et l'action dans la chaine controleur@action

        // Inclure le fichier du contrôleur

        @require_once('controleur/' . $controleur.'.php');

        $controleurInstance = new $controleur; #Instancier permet d'utiliser la fonction method_exists

        if (!method_exists($controleurInstance, $action)) {
            throw new \Exception("La méthode $action n'a pas été trouvée dans le controleur $controleurClass");
        }

        // Appeler la méthode du contrôleur
        return $controleurInstance->$action();
    }

    /**
     * Le point central de l'api, elle joue le rôle de "dispatcheur", en analysant l'URL et la méthode HTTP de la requête pour décider quel contrôleur et quelle méthode appeler
     * 
     * @param string $requete $_SERVER['REQUEST_URI']
     * @param string $methode $_SERVER['REQUEST_METHOD']
     */
    public static function dispatch($requete, $methode){

        //On "nettoie" la requete 
        $requete = trim(parse_url($requete, PHP_URL_PATH), '/');

        foreach(self::$routes as $route){
            if($route['uri'] === $requete && $route['methode'] === strtoupper($methode)){
                self::callAction($route['controleurAction']);
                return;
            }
        }
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }

}

?>