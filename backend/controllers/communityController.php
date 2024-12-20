<?php

@require_once('models/community.php');
@require_once('core/sessionGuard.php');
@require_once('validators/communityValidator.php');

class CommunityController{

    /**
     * Affiche un json de la liste des communautés dont l'utilisateur fait parti
     * 
     * @return void retourne le resultat sous forme de JSON
     */
    public static function index(){
        $userId = SessionGuard::getUserId();
        $communities = Community::communitiesOf($userId);
        echo json_encode($communities);
    }

    /**
     * Affiche un json contenant les données de la communauté passée dans l'URL
     * 
     * @param $params, un tableau correspondant auc paramètres attendus dans l'URL. 
     * 
     * Compositon de $params : 
     * - Indice 0 = $id, l'identifiant de la communauté recherchée. 
     */
    public static function show(array $params){
        $community = Community::getById($params[0]);
        echo json_encode($community);
    }

    /**
     * Affiche un json de la liste des communautés dont l'utilisateur à la charge d'administration
     * 
     * @return void retourne le resultat sous forme de JSON
     */
    public static function administered(){
        $userId = SessionGuard::getUserId();
        $communities = Community::communitiesBy($userId);
        echo json_encode($communities);
    }

    /**
     * Insère dans la base de données une nouvelle communauté
     * 
     * La fonctions attends les éléments suivant : 
     * - un nom (string)
     * - une image (string correspondant au nom de l'image)
     * - un emoji (string correspondant au code ascii)
     * - une couleur (string correpondant au code HEX) au format #XXXXXX
     * - une description (string)
     * 
     * Procède à des vérifications de validité avant d'insérer
     * 
     * ex de données acceptées : 
     * 
     * {
     *   "name": "Voyage en Laponie",
     *   "image": "100001.png",
     *   "description": "Lorem ipsum dolor sit amet sen",
     *   "emoji": "1F385",
     *   "color": "#DE3D59"
     * }
     * 
     * @return void renvoie la communauté au format json si l'insertion réussi
     */
    public static function store(){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if($body === null){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing data';
            echo json_encode($return);
            return;
        }
        
        $userId = SessionGuard::getUserId();

        if(!isset($body["name"]) || !isset($body["color"]) || !isset($body["emoji"]) 
        || !isset($body["description"]) || !isset($body["image"])){
            http_response_code(422);
            echo '{"Unprocessable Entity":"missing data for processing"}';
            return;
        }

        try{
            CommunityValidator::storeDataValidator($body);
        } catch (Error $e){
            http_response_code(422);
            $return["Unprocessable Entity"] = $e->getMessage();
            echo json_encode($return);
            return;
        }

        $values["CMY_name_VC"] = $body["name"];
        $values["CMY_color_VC"] = $body["color"];
        $values["CMY_image_VC"] = $body["image"];
        $values["CMY_description_TXT"] = $body["description"];
        $values["CMY_emoji_VC"] = $body["emoji"];
        $values["CMY_creator_NB"] = $userId;

        $community = new Community($values);
        $community->insert();

        echo json_encode($community);
    }

    /**
     * Affiche un json de la liste de propositions en cours de la communauté.
     * 
     * @param $params Une liste contenant les paramètres de la requêtes
     * 
     * Compositon de $params : 
     * - Indice 0 = $id, l'identifiant de la communauté recherchée. 
     * 
     * @return void le resultat est affiché au format JSON
     */
    public static function ongoingProposals($params){
        $values["CMY_id_NB"] = $params[0];
        $community = new Community($values);
        $proposals = $community->getOngoingProposals();
        echo json_encode($proposals);
    }

    /**
     * Affiche un json de la liste de propositions terminées de la communauté (max 6).
     * 
     * @param $params Une liste contenant les paramètres de la requêtes
     * 
     * Compositon de $params : 
     * - Indice 0 = $id, l'identifiant de la communauté recherchée. 
     * 
     * @return void le resultat est affiché au format JSON
     */
    public static function finishedProposals($params){
        $values["CMY_id_NB"] = $params[0];
        $community = new Community($values);
        $proposals = $community->getFinishedProposals();
        echo json_encode($proposals);
    }   

    /**
     * Affiche un json de la liste des membres et de leur role dans la communauté.
     * 
     * @param $params Une liste contenant les paramètres de la requêtes
     * 
     * Compositon de $params : 
     * - Indice 0 = $id, l'identifiant de la communauté recherchée. 
     * 
     * @return void le resultat est affiché au format JSON
     */
    public static function members($params){
        $values["CMY_id_NB"] = $params[0];
        $community = new Community($values);
        $users = $community->getMembers();
        echo json_encode($users);
    }

        /**
     * Affiche un json de la liste des membres et de leur role dans la communauté.
     * 
     * @param $params Une liste contenant les paramètres de la requêtes
     * 
     * Compositon de $params : 
     * - Indice 0 = $id, l'identifiant de la communauté recherchée. 
     * 
     * @return void le resultat est affiché au format JSON
     */
    public static function themes($params){
        $values["CMY_id_NB"] = $params[0];
        $community = new Community($values);
        $themes = $community->getThemes();
        echo json_encode($themes);
    }

    /**
     * Indique par un boolean si l'utilisateur est membre du groupe
     * 
     * @param $params Une liste contenant les paramètres de la requêtes
     * 
     * Compositon de $params : 
     * - Indice 0 = $id, l'identifiant de la communauté à vérifier. 
     * 
     * @return void le resultat est affiché au format JSON
     */
    public static function isMember($params){
        $userId = SessionGuard::getUserId();
        $result = Community::isMember($params[0], $userId);
        echo json_encode(($result));
    }
}

?>