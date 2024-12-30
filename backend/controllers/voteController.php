<?php

@require_once('models/vote.php');
@require_once('validators/voteValidator.php');

class voteController{

    /**
     * Récupère la liste des systèmes de vote
     * 
     * @return void Les resultats sont renvoyées en JSON.
     */
    public static function systems(){
        echo json_encode(Vote::voteSystem());
    }
    /**
     * Crée un nouveau vote pour une proposition.
     *
     * @param array $params Contient l'identifiant de la proposition ($params[0]).
     * 
     * Processus :
     * 1. Valide les données envoyées dans le corps de la requête via `VoteValidator::validateVoteData`.
     * 2. Crée un objet `Vote` avec les informations suivantes :
     *    - `VOT_proposal_NB` : Identifiant de la proposition.
     *    - `VOT_duration_NB` : Durée du vote.
     *    - `VOT_discussion_duration_NB` : Durée de la discussion.
     *    - `VOT_type_NB` : Système de vote.
     *    - `VOT_possibilities_TAB` : Options de vote.
     * 3. Insère le vote dans la base de données via la méthode `insert`.
     * 4. Gère les erreurs et retourne les codes et messages appropriés.
     * 
     * @return void
     * - 422 avec un message JSON si des données sont manquantes ou invalides.
     * - 400 avec un message JSON si un vote existe déjà pour la proposition.
     * - 403 avec un message JSON si l'utilisateur n'a pas les droits sur la proposition.
     * - true (JSON) si le vote est créé avec succès.
     */
    public static function store(array $params){
        $body = file_get_contents('php://input');
        $body = json_decode($body, true);

        if(!isset($body)){
            http_response_code(422);
            $return["Unprocessable Entity"] = 'Missing data';
            echo json_encode($return);
            return;
        }
        try{
            VoteValidator::validateVoteData($body);
        } catch (Error $e){
            http_response_code(422);
            $return["Unprocessable Entity"] = $e->getMessage();
            echo json_encode($return);
            return;
        }

        $values['VOT_proposal_NB'] = $params[0];
        $values['VOT_duration_NB'] = $body['voteDuration'];
        $values['VOT_discussion_duration_NB'] = $body['discussionDuration'];
        $values['VOT_type_NB'] = $body['system'];
        $values['VOT_possibilities_TAB'] = $body['possibilities'];

        $vote = new Vote($values);
        try{
            $result = $vote->insert();
            if($result === false){
                http_response_code(400);
                $return["Error"] = 'Vote already exist';
                echo json_encode($return);
                return;
            }
            echo json_encode(true);
        }catch (PDOException $e){
            http_response_code(403);
            $return["Error"] = 'You have no rights on this proposal';
            echo json_encode(false);
            return;
        }
    }
}