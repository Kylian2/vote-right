<?php

use Dotenv\Validator;

@require_once("models/proposal.php");
@require_once("models/comment.php");
@require_once("validators/proposalValidator.php");

class ProposalController{

    /**
     * Insère dans la base de données une nouvelle proposition
     * 
     * La fonctions attends les éléments suivant : 
     * - un titre (string)
     * - une description (string)
     * - une localisation (string) facultatif
     * - une communauté (integer)
     * - un theme (integer)
     * 
     * Procède à des vérifications de validité avant d'insérer
     * 
     * ex de données acceptées : 
     * 
     * {
     *   "title": "Augmenter les performances du serveur",
     *   "description": "Cette proposition vise à optimiser les performances du serveur afin d'améliorer la rapidité, la fiabilité et l'efficacité des services qu'il prend en charge. Les objectifs incluent la réduction des temps de latence, l'augmentation de la capacité de traitement des demandes simultanées, et l'amélioration de la stabilité du système.",
     *   "community": 26,
     *   "theme": 1
     *  }
     * 
     * @return bool renvoie true si l'insertion réussie
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

        $initiator = SessionGuard::getUserId();

        try{
            ProposalValidator::storeDataValidator($body);
        }catch(Error $e){
            http_response_code(422);
            $return["Unprocessable Entity"] = $e->getMessage();
            echo json_encode($return);
            return;
        }

        $values = array(
            "PRO_title_VC" => $body["title"],
            "PRO_description_TXT" => $body["description"],
            "PRO_location_VC" => isset($body["location"]) ? $body["location"] : null,
            "PRO_community_NB" => $body["community"],
            "PRO_theme_NB" => $body["theme"],
            "PRO_initiator_NB" => $initiator,
            "PRO_period_YEAR" => $body["year"],
        );

        $proposal = new Proposal($values);
        $result = $proposal->insert();

        echo json_encode($result);
    }

    /**
     * Affiche un json de la liste des propositions en cours liées aux communautés de l'utilisateur connecté
     * 
     * @return void les données sont renvoyées en JSON par echo
     */
    public static function ongoing(){
        $proposals = Proposal::getOngoing();
        echo json_encode($proposals);
    }

    /**
     * Affiche un json de 6 des propositions terminées liées aux communautés de l'utilisateur connecté
     * 
     * @return void les données sont renvoyées en JSON par echo
     */
    public static function finished(){
        $proposals = Proposal::getFinished();
        echo json_encode($proposals);
    }

    /**
     * Affiche un json contenant les informations de la proposition dont l'identifiant est passé via l'url
     * 
     * $params[0] contient l'identifiant de la proposition à afficher
     * 
     * @param array $params un tableau contenant les paramètres contenus dans l'URL
     * 
     * @return void les données sont renvoyées en JSON par echo
     */
    public static function show(array $params){
        $proposal = Proposal::getById($params[0]);
        echo json_encode($proposal);
    }

    /**
     * Affiche la liste des commentaires d'une proposition, avec l'utilisateur qui a envoyé le message
     * $params[0] contient l'identifiant de la proposition dont les commentaires seront récupérés
     * 
     * @param array $params un tableau contenant les paramètres contenus dans l'URL
     * 
     * @return void les données sont renvoyées en JSON par echo
     */
    public static function comments(array $params){
        $comment = Comment::getCommentsOfProposal($params[0]);
        echo json_encode($comment);
    }

    public static function reactions(array $params){
        $values["PRO_id_NB"] = $params[0];
        $proposal = new Proposal($values);
        $user = SessionGuard::getUserId();
        $reactions = $proposal->getReactions($user);
        echo json_encode($reactions);
    }

}
?>