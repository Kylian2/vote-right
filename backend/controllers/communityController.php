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
     * @return void renvoie au format json la communauté si l'insertions réussie
     */
    public static function store(){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

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
    
}

?>