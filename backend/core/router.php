<?php 

class Router{
    private static $routes = array();

    /**
     * Ajoute une route à la liste
     * 
     * @param string $method Le type de request (GET, POST, etc).
     * @param string $uri L'uri de la route
     * @param string $actionController Le controller et l'action à effectuer, ils doivent etre de la forme controller@action
     */
    public static function addRoute(string $method, string $uri, string $actionController){
        self::$routes[] = [
            'method' => $method,
            'uri' => trim($uri, '/'), #trim retire le '/' au début et en fin de chaine
            'actionController' => $actionController
        ];
    }

    /**
     * Défini une route GET 
     * 
     * @param string $uri L'uri de la route
     * @param string $actionController Le controller et l'action à effectuer, ils doivent etre de la forme controller@action
     */
    public static function get(string $uri, string $actionController){
        self::addRoute('GET', $uri, $actionController);
    }

    /**
     * Défini une route POST 
     * 
     * @param string $uri L'uri de la route
     * @param string $actionController Le controller et l'action à effectuer, ils doivent etre de la forme controller@action
     */
    public static function post(string $uri, string $actionController){
        self::addRoute('POST', $uri, $actionController);
    }

    /**
     * Lance une action d'un controller
     * 
     * @param string $actionController Le controller et l'action à effectuer, ils doivent etre de la forme controller@action
     */
    protected static function callAction($actionController)
    {
        list($controller, $action) = explode('@', $actionController); #permet de recuperer le controller et l'action dans la chaine controller@action

        // Inclure le fichier du contrôleur

        @require_once('controllers/' . $controller.'.php');

        $controllerInstance = new $controller; #Instancier permet d'utiliser la fonction method_exists

        if (!method_exists($controllerInstance, $action)) {
            throw new \Exception("$action method has not been found in $controllerClass");
        }

        // Appeler la méthode du contrôleur
        return $controllerInstance->$action();
    }

    /**
     * Le point central de l'api, elle joue le rôle de "dispatcheur", en analysant l'URL et la méthode HTTP de la requête pour décider quel contrôleur et quelle méthode appeler
     * 
     * @param string $request $_SERVER['REQUEST_URI']
     * @param string $method $_SERVER['REQUEST_METHOD']
     */
    public static function dispatch($request, $method){

        //On "nettoie" la request 
        $request = trim(parse_url($request, PHP_URL_PATH), '/');

        foreach(self::$routes as $route){
            if($route['uri'] === $request && $route['method'] === strtoupper($method)){
                self::callAction($route['actionController']);
                return;
            }
        }
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }

}

?>