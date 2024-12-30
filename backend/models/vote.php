<?php

@require_once("models/model.php");

class Vote extends Model{

    public int $VOT_proposal_NB;
    public int $VOT_round_NB;
    public bool $VOT_valid_BOOL;
    public string $VOT_start_DATE;
    public string $VOT_end_DATE;
    public int $VOT_type_NB;
    public string $VOT_type_VC;
    public array $VOT_possibilities_TAB;
    public int $VOT_nb_rounds_NB;

    /**
     * Récupère les informations sur les votes d'une propositions (pour chaque tour). 
     * Cette fonction ne recupère pas les résultats. 
     * Indique si l'utilisateur en paramètre à voté. 
     * 
     * @param int $proposal la proposition
     * @param int $user l'utilisateur
     * 
     * @return array un tableau d'objet représentant un vote, avec un attribut boolean hasVoted
     */
    public static function getVoteOf(int $proposal, int $user){
        $request = "SELECT get_vote_informations(:proposal)";
        $prepare = connexion::pdo()->prepare($request);
        $values['proposal'] = $proposal;
        $prepare->execute($values);
        $result = $prepare->fetch();
        $votes = json_decode($result[0]);

        for($i = 0; $i<count($votes); $i++){
            $vote = new Vote($votes[$i]);
            $request = "SELECT COUNT(*) as hasVoted FROM vote_detail WHERE DET_proposal_NB = :proposal AND DET_round_NB = :round AND DET_user_NB = :user";
            $prepare = connexion::pdo()->prepare($request);
            $values['user'] = $user;
            $values['round'] = $vote->get('VOT_round_NB');
            $prepare->execute($values);
            $result = $prepare->fetch();
            $hasVoted = $result[0];
            $votes[$i]->hasVoted = $hasVoted;
        }

        return $votes;
    }

    /**
     * Récupère les résultats des votes d'une proposition, en fonction du tour.
     * 
     * @param int $proposal la proposition
     * @param int $round le tour de vote
     * 
     * @return object un objet contenant les resultats du vote
     */
    public static function getResult(int $proposal, int $round){
        $request = "SELECT get_votes_for_proposal(:proposal, :round)";
        $prepare = connexion::pdo()->prepare($request);
        $values['proposal'] = $proposal;
        $values['round'] = $round;
        $prepare->execute($values);
        $result = $prepare->fetch();
        return json_decode($result[0]);
    }

    public static function save(int $proposal, int $round, int $user, int $choice){
        $request = "INSERT INTO vote_detail(DET_proposal_NB, DET_round_NB, DET_user_NB, DET_choice_NB) VALUES (:proposal, :round, :user, :choice)";
        $prepare = connexion::pdo()->prepare($request);
        $values['proposal'] = $proposal;
        $values['round'] = $round;
        $values['user'] = $user;
        $values['choice'] = $choice;
        $prepare->execute($values);
        return true;
    }

    public static function validateVote(int $proposal, int $round, int $user, bool $valid){
        //Vérification faite aussi en base de données avec le trigger
        $request = "SELECT MEM_role_NB FROM member WHERE MEM_user_NB = :user AND MEM_community_NB = (SELECT PRO_community_NB FROM proposal WHERE PRO_id_NB = :proposal)";
        $prepare = connexion::pdo()->prepare($request);
        $values['user'] = $user;
        $values['proposal'] = $proposal;
        $prepare->execute($values);
        $role = $prepare->fetch();
        if($role[0] != ROLE_ASSESSOR){
            return false;
        }
        
        $request = 'UPDATE vote
                    SET VOT_assessor_NB = :user, VOT_valid_BOOL = :valid
                    WHERE VOT_proposal_NB = :proposal AND VOT_round_NB = :round;';
        $prepare = connexion::pdo()->prepare($request);
        $values['round'] = $round;
        $values['valid'] = $valid;
        $prepare->execute($values);
        return true;
    }

}

?>