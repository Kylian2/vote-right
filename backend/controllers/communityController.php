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
     * Affiche un json de la liste des communautés dans lequels l'utilisateur à un role de gestion 
     * (admin, modérateur, décideur, assesseur)
     * 
     * @return void retourne le resultat sous forme de JSON
     */
    public static function managed(){
        $userId = SessionGuard::getUserId();
        $communities = Community::communitiesManagedBy($userId);
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
     * Ajoute un nouveau membre dans une communauté
     * 
     * @param $params, un tableau correspondant aux paramètres attendus dans l'URL. 
     * 
     * Composition de $params : 
     * - $params[0] = $id, l'identifiant de la communauté dans laquelle on insère. 
     * 
     * La fonction attend l'élément suivant : 
     * - un numéro d'utilisateur (int)
     * 
     * ex de données acceptées : 
     * 
     * {
     *   "newMemberId" : 98
     * }
     * 
     * @return void renvoie un message qui confirme que le nouveau membre à bien été inséré dans la communauté
     */
    public static function insertMember(array $params){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if($body === null){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing data';
            echo json_encode($return);
            return;
        }

        if(!isset($body["newMemberId"]) || !isset($params[0])){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing data for processing';
            echo json_encode($return);
            return;
        }

        $values["CMY_id_NB"] = $params[0];
        $values["CMY_member_NB"] = $body["newMemberId"];

        Community::addMember($values);
        echo "Ajout d'un nouveau membre effectuée avec succès";
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
     * Affiche un json de la liste de propositions adoptées de la communauté. Par défaut tout les propositions de toutes les années sont 
     * renvoyées. Il est possible de spécifier une année via les paramètres GET de l'url.
     * 
     * @param $params Une liste contenant les paramètres de la requêtes
     * 
     * Compositon de $params : 
     * - Indice 0 = $id, l'identifiant de la communauté recherchée. 
     * 
     * @return void le resultat est affiché au format JSON
     */
    public static function adoptedProposals($params){
        $values["CMY_id_NB"] = $params[0];
        $community = new Community($values);
        $period = isset($_GET["period"]) ? $_GET["period"] : null;
        $proposals = $community->getAdoptedProposals($period);
        echo json_encode($proposals);
    }   

    /**
     * Affiche un json de la liste de propositions dont les votes sont terminées, et dont le statut est toujours 'en cours'.
     * 
     * @param $params Une liste contenant les paramètres de la requêtes
     * 
     * Compositon de $params : 
     * - Indice 0 = $id, l'identifiant de la communauté recherchée. 
     * 
     * @return void le resultat est affiché au format JSON
     */
    public static function votedProposals($params){
        $values["CMY_id_NB"] = $params[0];
        $community = new Community($values);
        $proposals = $community->getVotedProposals();
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
        echo json_encode($result);
    }

    public static function budget($params){
        $values["CMY_id_NB"] = $params[0];
        $community = new Community($values);
        if(!isset($_GET['period']) || !is_numeric($_GET['period'])){
            http_response_code(422);
            echo '{"Unprocessable Entity":"Period is not specified"}';
            return;
        }
        $themes = $community->getBudget($_GET['period']);
        echo json_encode($themes);
    }

    public static function setBudget($params){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if(!isset($_GET['period']) || !is_numeric($_GET['period'])){
            http_response_code(422);
            echo '{"Unprocessable Entity":"Period is not specified"}';
            return;
        }

        foreach($body as $key => $value){
            if(!is_numeric($key) || !is_numeric($value)){
                http_response_code(422);
                echo '{"Unprocessable Entity":"Invalid element in body"}';
                return;
            }
        }

        $values["CMY_id_NB"] = $params[0];
        $community = new Community($values);
        $community->setBudget($body, $_GET['period']);

        echo json_encode(true);
    }
}

?>