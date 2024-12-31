<?php

@require_once('models/report.php');
@require_once('models/proposal.php');

class reportController{
    
    /**
     * Affiche un json contenant les informations de chacun des signalements dans le groupe recherché
     * 
     * $params[0] contient l'identifiant du groupe dont on recherche les signalements
     * 
     * @param array $params un tableau contenant les paramètres contenus dans l'URL
     * 
     * @return void les données des signalements sont renvoyées en JSON par echo
     */
    public static function show($params){
        $allProposal = Proposal::allOfCommunity($params[0]);

        $proposalArray = array ();
        foreach ($allProposal as $proposal) {
            $proposalArray[] = get_object_vars($proposal);
        }

        $allReports = array();
        foreach ($proposalArray as $proposal) {
            $allReports = array_merge($allReports, Report::getByProposal($proposal));
        }
        echo json_encode($allReports);
    }

    public static function resolv(){
        
    }

    public static function delete(){
        
    }
}

?>