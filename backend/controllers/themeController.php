<?php 

@require_once('models/theme.php');
@require_once('models/community.php');

class ThemeController{

    /**
     * insère un thème dans la communauté passée en paramètre. Ajoute également 
     * le thème aux budgets des thèmes de chaque année. 
     * 
     * @param array $params Les paramètres passés via l'URL. [0] correspond à l'identifiant de la communauté. 
     *
     * @return void Un JSON du thème inséré. 
     */
    public static function store($params){

        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if(!isset($body['name'])){
            http_response_code(422);
            echo '{"Unprocessable Entity":"name is missing"}';
            return;
        }

        if(!isset($body['budget']) || !is_numeric($body['budget'])){
            http_response_code(422);
            echo '{"Unprocessable Entity":"budget is invalid or missing"}';
            return;
        }

        $values['THM_community_NB'] = $params[0];
        $values['THM_name_VC'] = $body['name'];
        $theme = new Theme($values);

        $theme->insert();
        echo json_encode(($theme));

        //Ajout du budget pour l'année en cours.
        $communityValues['CMY_id_NB'] = $theme->get('THM_community_NB');
        $community = new Community($communityValues);
        $budgets[$theme->get('THM_id_NB')] = $body['budget'];
        $community->setBudget($budgets, date('Y'));
    }
    
}

?>