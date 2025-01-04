<?php

@require_once('models/community.php');
@require_once('models/user.php');
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

        $role = isset($_GET["role"]) ? $_GET["role"] : null;
        if($role === 'decider'){
            $communities = Community::communitiesDecidedBy($userId);
            echo json_encode($communities);
            return;
        }

        $communities = Community::communitiesManagedBy($userId);
        echo json_encode($communities);
    }

    /**
     * Insère une nouvelle communauté dans la base de données.
     *
     * La méthode attend un corps de requête JSON contenant les éléments suivants :
     * - `name` (string) : Nom de la communauté.
     * - `image` (string) : Nom du fichier image.
     * - `emoji` (string) : Code ASCII de l'emoji.
     * - `color` (string) : Code HEX de la couleur au format `#XXXXXX`.
     * - `description` (string) : Description de la communauté.
     *
     * Exemple de données acceptées :
     * {
     *   "name": "Voyage en Laponie",
     *   "image": "100001.png",
     *   "description": "Lorem ipsum dolor sit amet sen",
     *   "emoji": "1F385",
     *   "color": "#DE3D59"
     * }
     *
     * Procède aux étapes suivantes :
     * - Vérifie la présence et la validité des données dans le corps de la requête.
     * - Valide les données via `CommunityValidator::storeDataValidator`.
     * - Insère les données dans la base de données.
     * - Renvoie la communauté nouvellement créée au format JSON.
     *
     * @return void
     * - 422 avec un message JSON si des données sont manquantes ou invalides.
     * - La communauté au format JSON si l'insertion réussit.
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

    /**
     * Récupère les budgets d'une communauté pour une période donnée.
     *
     * @param array $params Contient l'identifiant de la communauté ($params[0]).
     *
     * @return void
     * - 422 avec un message JSON si la période (`period`) n'est pas spécifiée ou invalide.
     * - Les budgets (JSON) par thème pour la période spécifiée si succès.
     */
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

    /**
     * Définit les budgets des thèmes pour une communauté et une période.
     *
     * @param array $params Contient l'identifiant de la communauté ($params[0]).
     *
     * @return void
     * - 422 avec un message JSON si la période n'est pas spécifiée ou si le corps de la requête contient des données invalides.
     * - true (JSON) si les budgets sont définis avec succès.
     */
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

    /**
     * Définit les membres d'une communauté.
     *
     * @param array $params Contient l'identifiant de la communauté ($params[0]).
     *
     * @return void
     * - 422 avec un message JSON si le corps de la requête est manquant.
     * - 401 avec un message JSON en cas d'erreur lors de la mise à jour des membres.
     * - true (JSON) si les membres sont définis avec succès.
     */
    public static function setMembers(array $params){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if(!isset($body)){
            http_response_code(422);
            echo '{"Unprocessable Entity":"body is missing"}';
            return;
        }

        $values["CMY_id_NB"] = $params[0];
        $community = new Community($values);
        try{
            $community->setMembers($body);
            echo json_encode(true);
        }catch(Exception $e){
            http_response_code(401);
            echo json_encode(array('Error' => $e->getMessage()));
        }
    }

    /**
     * Exclut un membre d'une communauté.
     *
     * @param array $params Contient :
     *  - L'identifiant de la communauté ($params[0]).
     *  - L'identifiant du membre à exclure ($params[1]).
     *
     * @return void
     * - 422 avec un message JSON si l'identifiant du membre est invalide.
     * - 401 avec un message JSON en cas d'erreur lors de l'exclusion.
     * - true (JSON) si l'exclusion réussit.
     */
    public static function exclude(array $params){
        $member = $params[1];

        if(!is_numeric($member)){
            http_response_code(422);
            echo '{"Unprocessable Entity":"Invalid data"}';
            return;
        }

        $values["CMY_id_NB"] = $params[0];
        $community = new Community($values);
        try{
            $community->exclude($member);
            echo json_encode(true);
        }catch(Exception $e){
            http_response_code(401);
            echo json_encode(array('Error' => $e->getMessage()));
        }
    }

    /**
     * Récupère les périodes associées à une communauté.
     *
     * @param array $params Contient l'identifiant de la communauté ($params[0]).
     *
     * @return void
     * - Les périodes disponibles (JSON) pour la communauté si succès.
     */
    public static function periods(array $params){
        $values["CMY_id_NB"] = $params[0];
        $community = new Community($values);
        $periods = $community->getPeriods($params[0]);
        echo json_encode($periods);
    }

    /**
     * Récupere la liste des propositions dont les votes sont finis et validés. Les propositions récupérées
     * sont mise dans un format permettant leur utilisation au sein de l'application Java.
     * 
     * @param array $params l'identifiant de la communauté ($params[0])
     * 
     * @return void
     * - Les propositions au format JSON
     */
    public static function formattedProposals(array $params){

        if(!isset($_GET['period'])){
            http_response_code(422);
            echo '{"Unprocessable Entity":"undefined period"}';
            return;
        }

        $values["CMY_id_NB"] = $params[0];
        $community = new Community($values);
        $proposals = $community->getFormattedProposals($_GET['period']);
        echo json_encode($proposals);
    }
}

?>