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

    //permet de dire si l'utilisateur à déjà voté
    public bool $hasVoted;
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

}

?>