<?php

@require_once("models/model.php");

define("POUR", 1);
define("OUI", 2);

class Vote extends Model{

    public int $VOT_proposal_NB;
    public int $VOT_round_NB;
    public bool $VOT_valid_BOOL;
    public string $VOT_start_DATE;
    public string $VOT_end_DATE;
    public int $VOT_duration_NB;
    public int $VOT_discussion_duration_NB;
    public int $VOT_type_NB;
    public string $VOT_type_VC;
    public array $VOT_possibilities_TAB;
    public int $VOT_nb_rounds_NB;
    
    /**
     * Insère un nouveau vote dans la base de données.
     *
     * Processus :
     * 1. Calcule le numéro de tour (`VOT_round_NB`) pour la proposition en cours.
     *    - Si le tour est supérieur à 1, retourne `false` (seul un tour est autorisé).
     * 2. Met à jour la durée de discussion de la proposition dans la table `proposal`.
     * 3. Insère le vote dans la table `vote` avec les informations suivantes :
     *    - Proposition, numéro de tour, date de début et de fin, type de vote.
     * 4. Détermine les possibilités de vote en fonction du type (`POUR/CONTRE` ou `OUI/NON` par défaut).
     * 5. Insère chaque possibilité dans la table `possibility`.
     *
     * @return bool
     * - `true` si l'insertion réussit.
     * - `false` si un tour de vote supérieur à 1 est détecté.
     */
    public function insert(){
        $request = 'SELECT IFNULL(MAX(VOT_round_NB), 0) + 1 FROM vote WHERE VOT_proposal_NB = :proposal';
        $prepare = connexion::pdo()->prepare($request);
        $values['proposal'] = $this->get('VOT_proposal_NB');
        $prepare->execute($values);
        $round = $prepare->fetch();

        if($round[0] > 1){
            return false;
        }

        $this->set('VOT_round_NB', $round[0]);

        $request = 'UPDATE proposal SET PRO_discussion_duration_NB = :discussion WHERE PRO_id_NB = :proposal';
        $prepare = connexion::pdo()->prepare($request);
        $values['discussion'] = $this->get('VOT_discussion_duration_NB');
        $prepare->execute($values);

        $request = 'INSERT INTO vote (VOT_proposal_NB, VOT_round_NB, VOT_start_DATE, VOT_end_DATE, VOT_type_NB)
                    VALUES (
                        :proposal,
                        :round,
                        DATE_ADD((SELECT PRO_creation_DATE FROM proposal WHERE PRO_id_NB = :proposal), INTERVAL :discussion DAY),
                        DATE_ADD(
                            DATE_ADD((SELECT PRO_creation_DATE FROM proposal WHERE PRO_id_NB = :proposal), INTERVAL :discussion DAY),
                            INTERVAL :duration DAY
                        ),
                        :system
                    )';
        $prepare = connexion::pdo()->prepare($request);
        $values['duration'] = $this->get('VOT_duration_NB');
        $values['system'] = $this->get('VOT_type_NB');
        $values['round'] = $this->get('VOT_round_NB');
        $prepare->execute($values);
        
        $possibilities = $this->get('VOT_possibilities_TAB');

        if($this->get('VOT_type_NB') == POUR){
            $possibilities = ['POUR', 'CONTRE'];
        }

        if($this->get('VOT_type_NB') == OUI){
            $possibilities = ['OUI', 'NON'];
        }

        $request = 'INSERT INTO possibility(POS_label_VC, POS_proposal_NB, POS_round_NB) VALUES (:possibility, :proposal, :round)';
        $prepare = connexion::pdo()->prepare($request);
        unset($values['system']);
        unset($values['duration']);
        unset($values['discussion']);
        
        foreach ($possibilities as $possibility){
            $values['possibility'] = $possibility;
            $prepare->execute($values);
        }
        return true;
    }

    /**
     * Modifie un vote existant si le vote n'a pas encore commencé.
     *
     * Processus :
     * 1. Vérifie s'il existe un vote futur pour la proposition (`VOT_start_DATE > CURRENT_DATE()`).
     *    - Si aucun vote n'est trouvé, retourne `false`.
     * 2. Supprime les anciennes possibilités associées à la proposition.
     * 3. Met à jour la durée de discussion dans la table `proposal`.
     * 4. Met à jour les détails du vote dans la table `vote` (dates de début/fin et type de vote).
     * 5. Détermine les nouvelles possibilités de vote en fonction du type (`POUR/CONTRE` ou `OUI/NON` par défaut).
     * 6. Insère les nouvelles possibilités dans la table `possibility`.
     * 
     * @return bool
     * - `true` si la modification est effectuée avec succès.
     * - `false` si aucun vote futur n'est trouvé pour la proposition.
     */
    public function edit(){
        $request = 'SELECT COUNT(*) FROM vote WHERE VOT_proposal_NB = :proposal AND VOT_start_DATE > CURRENT_DATE()';
        $prepare = connexion::pdo()->prepare($request);
        $values['proposal'] = $this->get('VOT_proposal_NB');
        $prepare->execute($values);
        $round = $prepare->fetch();
        
        if(!$round){
            return false;
        }

        $request = 'DELETE FROM possibility WHERE POS_proposal_NB = :proposal';
        $prepare = connexion::pdo()->prepare($request);
        $prepare->execute($values);

        $request = 'UPDATE proposal SET PRO_discussion_duration_NB = :discussion WHERE PRO_id_NB = :proposal';
        $prepare = connexion::pdo()->prepare($request);
        $values['discussion'] = $this->get('VOT_discussion_duration_NB');
        $prepare->execute($values);

        $request = 'UPDATE vote SET VOT_start_DATE = DATE_ADD((SELECT PRO_creation_DATE FROM proposal WHERE PRO_id_NB = :proposal), INTERVAL :discussion DAY),
                                    VOT_end_DATE = DATE_ADD( DATE_ADD((SELECT PRO_creation_DATE FROM proposal WHERE PRO_id_NB = :proposal), INTERVAL :discussion DAY), INTERVAL :duration DAY),
                                    VOT_type_NB = :system 
                    WHERE VOT_proposal_NB = :proposal AND VOT_round_NB = 1 AND VOT_start_DATE > CURRENT_DATE();';
        $prepare = connexion::pdo()->prepare($request);
        $values['duration'] = $this->get('VOT_duration_NB');
        $values['system'] = $this->get('VOT_type_NB');
        $prepare->execute($values);
        
        $possibilities = $this->get('VOT_possibilities_TAB');

        if($this->get('VOT_type_NB') == POUR){
            $possibilities = ['POUR', 'CONTRE'];
        }

        if($this->get('VOT_type_NB') == OUI){
            $possibilities = ['OUI', 'NON'];
        }

        $request = 'INSERT INTO possibility(POS_label_VC, POS_proposal_NB, POS_round_NB) VALUES (:possibility, :proposal, 1)';
        $prepare = connexion::pdo()->prepare($request);
        unset($values['system']);
        unset($values['duration']);
        unset($values['discussion']);
        
        foreach ($possibilities as $possibility){
            $values['possibility'] = $possibility;
            $prepare->execute($values);
        }
        return true;
    }

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

    /**
     * Recupere la liste des types de vote
     */
    public static function voteSystem(){
        $request = "SELECT * FROM voting_system";
        $result = connexion::pdo()->query($request);
        $result->setFetchmode(PDO::FETCH_OBJ);
        $systems = $result->fetchAll();
        return $systems;
    }
}

?>