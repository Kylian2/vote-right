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
     * Affiche un json de la liste des propositions et leur thème d'une communauté
     * 
     * @param $params Une liste contenant les paramètres de la requêtes
     * 
     * Compositon de $params : 
     * - Indice 0 = $id, l'identifiant de la communauté recherchée. 
     * 
     * @return void le resultat est affiché au format JSON
     */
    public static function allOfCommunity($params){
        $CMY_id_NB = $params[0];
        $proposals = Proposal::allOfCommunity($CMY_id_NB);
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

    /**
     * Affiche les reactions sur la proposition dont l'identifiant est passé en paramètre et si l'utilisateur connecté a déjà réagit.
     * 
     * @param array $params un tableau composé des paramètre de l'url. $params[0] contient l'identifiant de la proposition.
     * 
     * @return void les données sont affichés en JSON
     */
    public static function reactions(array $params){
        $values["PRO_id_NB"] = $params[0];
        $proposal = new Proposal($values);
        $user = SessionGuard::getUserId();
        $reactions = $proposal->getReactions($user);
        echo json_encode($reactions);
    }

    /**
     * Permet de reagir à une proposition
     * 
     * @param array $params les paramètres de l'url ($params[0] contient l'indentifiant de la proposition);
     * 
     * @return bool true si l'utilisateur a pu réagir, false sinon
     */
    public static function react($params){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if(!isset($body['reaction']) || !is_numeric($body['reaction'])){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing or incorrect data';
            echo json_encode($return);
            return;
        }

        $userId = SessionGuard::getUserId();

        $result = Proposal::react($params[0], $body['reaction'], $userId);

        echo json_encode($result);
    }

    /**
     * Affiche le nombre de demande formelle de la proposition et si l'utilisateur a déjà réagit
     * 
     * @param int $params les paramètres de l'url, $params[0] la proposition
     * 
     * @return void Le resultat est affiché via un JSON
     */
    public static function getRequest($params){
        $userId = SessionGuard::getUserId();
        $result = Proposal::getRequest($params[0], $userId);
        echo json_encode($result);
    }

    /**
     * Fait une demande formelle de la part de l'utilisateur connecté sur la proposition en paramètre
     * 
     * @param int $params les paramètres de l'url, $params[0] la proposition
     * 
     * @return void Le resultat est affiché via un JSON (true si ok, false sinon)
     */
    public static function postRequest($params){
        $userId = SessionGuard::getUserId();
        $result = Proposal::postRequest($params[0], $userId);
        echo json_encode($result);
    }

    /**
     * Indique par un boolean si l'utilisateur est membre de la communauté associé à la proposition
     * 
     * @param $params Une liste contenant les paramètres de la requêtes
     * 
     * Compositon de $params : 
     * - Indice 0 = $id, l'identifiant de la proposition à vérifier. 
     * 
     * @return void le resultat est affiché au format JSON
     */
    public static function isMember($params){
        $userId = SessionGuard::getUserId();
        $result = Proposal::isMember($params[0], $userId);
        echo json_encode(($result));
    }
}
?>