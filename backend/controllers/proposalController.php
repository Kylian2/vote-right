<?php

@require_once("models/proposal.php");
@require_once("models/comment.php");
@require_once("controllers/notificationController.php");
@require_once("models/vote.php");
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

        notificationController::notifyNewProposal($proposal);

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

    /**
     * Récupère les informations concernant les votes d'une proposition.
     * Indique si l'utilisateur a déjà voté. 
     * 
     * @param $params Une liste contenant les paramètres de la requêtes
     * 
     * Compositon de $params : 
     * - Indice 0 = $id, l'identifiant de la proposition
     * 
     * @return void le resultat est affiché au format JSON
     */
    public static function voteInfos($params){
        $userId = SessionGuard::getUserId();
        $votes = Vote::getVoteOf($params[0], $userId);
        echo json_encode($votes);
    }

    /**
     * Récupère les résultats d'un vote d'une proposition
     * 
     * @param $params Une liste contenant les paramètres de la requêtes
     * 
     * Compositon de $params : 
     * - Indice 0 = $id, l'identifiant de la proposition
     * - Indice 1 = $round, le tour du vote
     * 
     * @return void le resultat est affiché au format JSON
     */
    public static function voteResult($params){
        $result = Vote::getResult($params[0], $params[1]);
        echo json_encode($result);
    }

    /**
     * Enregistre le vote d'un utilisateur
     * 
     * @param $params Une liste contenant les paramètres de la requêtes
     * 
     * Compositon de $params : 
     * - Indice 0 = $id, l'identifiant de la proposition
     * - Indice 1 = $round, le tour du vote
     * 
     * Body : 
     * - choice : int le numero de la possibilité choisie par l'utilisateur
     * 
     * @return void le resultat est affiché au format JSON
     */
    public static function saveVote($params){

        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if(!isset($body['choice']) || !is_numeric($body['choice'])){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing or incorrect data';
            echo json_encode($return);
            return;
        }

        $proposal = $params[0];
        $round = $params[1];

        $userId = SessionGuard::getUserId();

        $votes = Vote::getVoteOf($proposal, $userId);
        $vote = $votes[$round-1]; //récupère le tour (en comptant l'index partant de 0)

        if(boolval($vote->hasVoted)){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'You have already voted';
            echo json_encode($return);
            return;
        }

        $end = new DateTime($vote->VOT_end_DATE);
        $today = new DateTime(date("Y-m-d H:i:s"));
        if($today > $end){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Votetime is finished';
            echo json_encode($return);
            return;
        }

        try{
            $choice = $body['choice'];
            Vote::save($proposal, $round, $userId, $choice);
            echo json_encode(true);
            return;
        } catch(PDOException $e){

            if($e->errorInfo[0] == 45000){
                http_response_code(401);
                $return["Error"] = $e->errorInfo[2];
                echo json_encode($return);
                return;
            }

            if($e->errorInfo[1] === 1452){
                http_response_code(422);
                $return["Error"] = 'Invalid possibility';
                echo json_encode($return);
                return;
            }

            http_response_code(422);
            $return["Error"] = $e;
            echo json_encode($return);
            return;
        }

    }

    /**
     * Valide ou refuse un vote
     * 
     * @param $params Une liste contenant les paramètres de la requêtes
     * 
     * Compositon de $params : 
     * - Indice 0 = $id, l'identifiant de la proposition
     * - Indice 1 = $round, le tour du vote
     * 
     * Body : 
     * - valid : `bool` `true` si le vote est validé, `false` sinon
     * 
     * @return void le resultat est affiché au format JSON
     */
    public static function validateVote($params){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if(!isset($body['valid']) || !is_bool($body['valid'])){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Invalid data';
            echo json_encode($return);
            return;
        }

        $return = Vote::validateVote($params[0], $params[1], SessionGuard::getUserId(), $body['valid']);

        if(!$return){
            http_response_code(403);
        }
        json_encode($return);
    }

    /**
     * Met à jour les informations d'une proposition (ex. : budget).
     *
     * @param array $params Contient l'identifiant de la proposition ($params[0]).
     *
     * @return void
     * - 422 avec un message JSON si l'entrée est invalide
     * - 400 avec un message JSON en cas d'erreur lors de la mise à jour d'une donnée.
     * - true (JSON) si la mise à jour réussit.
     */

    public static function patch(array $params){
        $values["PRO_id_NB"] = $params[0];
        $proposal = new Proposal($values);

        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if(isset($body['budget'])){
            if(!is_numeric($body['budget']) || $body['budget'] < 0){
                http_response_code(422);
                $return["Unprocessable Entity"] = 'Budget is not valid';
                echo json_encode($return);
                return;
            }
            try{
                $proposal->setBudget($body['budget']);
            }catch(PDOException $e){
                http_response_code(400);
                $return["Error"] = $e->errorInfo[2];
                echo json_encode($return);
                return;
            }
        }
        echo json_encode(true);
    }

    /**
     * Supprime une proposition.
     *
     * @param array $params Contient l'identifiant de la proposition ($params[0]).
     *
     * @return void
     * - 403 avec un message JSON si l'utilisateur n'est pas autorisé.
     * - true (JSON) si la suppression réussit.
     */
    public static function delete(array $params){
        $values["PRO_id_NB"] = $params[0];
        $proposal = new Proposal($values);
        $userId = SessionGuard::getUserId();
        $result = $proposal->delete($userId);
        if(!$result){
            http_response_code(403);
            echo json_encode($result);
            return;
        }
        echo json_encode(true);
    }

    /**
     * Gère l'approbation ou le rejet d'une proposition.
     *
     * @param array $params Contient l'identifiant de la proposition ($params[0]).
     *
     * @return void
     * - 422 avec un message JSON si `approve` est invalide.
     * - 403 avec un message JSON en cas d'erreur d'autorisation ou de budget dépassé.
     * - 400 avec un message JSON si le budget dépasse les limites allouées.
     * - true (JSON) si l'opération réussit.
     * - false (JSON) en cas d'échec.
     */
    public static function approve(array $params){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if(!isset($body['approve']) || !is_bool($body['approve'])){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Invalid data';
            echo json_encode($return);
            return;
        }

        $values["PRO_id_NB"] = $params[0];
        $proposal = new Proposal($values);
        $userId = SessionGuard::getUserId();
        $result = $proposal->approve($userId, $body['approve']);
        if($result !== true){
            if(!$result){
                http_response_code(403);
                echo json_encode(false);
                return;
            }
            if($result->errorInfo[2] === "Erreur : Le budget de cette proposition fait dépasser le budget total alloué pour son thème."){
                http_response_code(400);
                echo json_encode(false);
                return;
            }
            if($result->errorInfo[2] === "Erreur : Le budget de cette proposition fait dépasser le budget total alloué pour son thème."){
                http_response_code(403);
                echo json_encode(false);
                return;
            }
            echo json_encode(false);
            return;
        }
        echo json_encode(true);
    }

    /**
     * Affiche renvoie un boolean indiquant si l'utilisateur peut gerer cette proposition
     * (admin, modérateur, décideur, assesseur)
     * 
     * @param array $params les paramètres de l'url
     * 
     * @return void retourne le resultat sous forme de JSON
     */
    public static function managed($params){
        $userId = SessionGuard::getUserId();
        $result = Proposal::canManage($params[0], $userId);
        echo json_encode($result);
    }

}
?>