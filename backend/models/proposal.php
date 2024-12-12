<?php

class Proposal extends Model{

    public string $PRO_id_NB;
    public string $PRO_title_VC;
    public string $PRO_description_TXT;
    public string $PRO_color_VC;
    public string $PRO_period_YEAR;
    public float $PRO_budget_NB;
    public ?int $PRO_discussion_duration_NB; //nullable
    public string $PRO_creation_DATE;
    public string $PRO_status_VC;

    //Le numero du thème (relativement à la commaunauté) et/ou le nom du thème
    public string $PRO_theme_VC;
    public string $PRO_theme_NB;

    public ?string $PRO_location_VC; //nullable
    public int $PRO_community_NB;
    public int $PRO_initiator_NB;

    public static function getOngoing() {
        $request = "SELECT PRO_id_NB, PRO_title_VC, CMY_color_VC as PRO_color_VC, THM_name_VC as PRO_theme_VC
                    FROM proposal
                    INNER JOIN community ON pro_community_nb = cmy_id_nb
                    INNER JOIN theme ON pro_community_nb = thm_community_nb AND pro_theme_nb = thm_id_nb
                    WHERE pro_community_nb IN (SELECT mem_community_nb FROM member WHERE mem_user_nb = :user)
                    AND pro_deleter_nb IS NULL
                    AND (pro_status_vc = 'En cours' OR pro_status_vc = 'En attente');";

        $prepare = connexion::pdo()->prepare($request);

        $values = array();

        $values["user"] = SessionGuard::getUserId();

        $prepare->execute($values);

        $prepare->setFetchmode(PDO::FETCH_CLASS, "proposal");
        $proposals = $prepare->fetchAll();
        return $proposals;
    }

    public static function getFinished() {
        $request = "SELECT PRO_id_NB, PRO_title_VC, CMY_color_VC as PRO_color_VC, THM_name_VC as PRO_theme_VC
                    FROM proposal
                    INNER JOIN community ON pro_community_nb = cmy_id_nb
                    INNER JOIN theme ON pro_community_nb = thm_community_nb AND pro_theme_nb = thm_id_nb
                    WHERE pro_community_nb IN (SELECT mem_community_nb FROM member WHERE mem_user_nb = :user)
                    AND pro_deleter_nb IS NULL
                    AND (pro_status_vc = 'Validée' OR pro_status_vc = 'Rejetée');";

        $prepare = connexion::pdo()->prepare($request);

        $values = array();

        $values["user"] = SessionGuard::getUserId();

        $prepare->execute($values);

        $prepare->setFetchmode(PDO::FETCH_CLASS, "proposal");
        $proposals = $prepare->fetchAll();
        return $proposals;
    }

    public function insert(){

        $request = 'INSERT INTO proposal(PRO_title_VC, PRO_description_TXT, PRO_location_VC, PRO_initiator_NB, PRO_community_NB, PRO_theme_NB, PRO_period_YEAR)
                    VALUES (:title, :description, :location, :initiator, :community, :theme, :year)';
        $prepare = connexion::pdo()->prepare($request);

        $values = array(
            "title" => $this->PRO_title_VC,
            "description" => $this->PRO_description_TXT,
            "location" => $this->PRO_location_VC,
            "initiator" => $this->PRO_initiator_NB,
            "community" => $this->PRO_community_NB,
            "theme" => $this->PRO_theme_NB,
            "year" => $this->PRO_period_YEAR
        );

        $prepare->execute($values);

        $communityId = connexion::pdo()->lastInsertId();
        $this->set('PRO_id_NB', $communityId);
        return true;
    }

    public static function getById(int $id){
        $request = "SELECT PRO_id_NB, PRO_title_VC, PRO_description_TXT, PRO_budget_NB, PRO_period_YEAR, PRO_discussion_duration_NB,PRO_location_VC, PRO_creation_DATE, PRO_status_VC, PRO_initiator_NB, THM_name_VC AS PRO_theme_VC, PRO_community_NB
                    FROM proposal
                    INNER JOIN theme ON THM_id_NB = PRO_theme_NB AND THM_community_NB = PRO_community_NB
                    WHERE PRO_id_NB = :id";
        $prepare = connexion::pdo()->prepare($request);
        $values["id"] = $id;
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "proposal");
        $proposal = $prepare->fetch();
        return $proposal;
    }

    /**
     * Affiche le nombre de reactions pour la proposition et indique si l'utilisateur passé en paramètre à réagit à cette proposition
     * 
     * @param int $user l'identifiant de l'utilisateur
     * 
     * @return array un tableaux contenant les informations (nblove: int, nblike: int, nbdislike: int, nbhate: int, hasReacted: bool)
     */
    public function getReactions(int $user){
        $request = "SELECT nblove, nblike, nbdislike, nbhate FROM proposal_total_reaction WHERE PRO_id_NB = :proposal";
        $prepare = connexion::pdo()->prepare($request);
        $values["proposal"] = $this->PRO_id_NB;
        $prepare->execute($values);
        $reactions = $prepare->fetch();

        unset($reactions[0]);
        unset($reactions[1]);
        unset($reactions[2]);
        unset($reactions[3]);

        $reactions['nblove'] = (int) $reactions['nblove'];
        $reactions['nblike'] = (int) $reactions['nblike'];
        $reactions['nbdislike'] = (int) $reactions['nbdislike'];
        $reactions['nbhate'] = (int) $reactions['nbhate'];

        $request = "SELECT CASE WHEN COUNT(*) != 0 THEN REP_reaction_NB ELSE 0 END as hasReacted FROM proposal_reaction WHERE REP_user_NB = :user AND REP_proposal_NB = :proposal";
        $prepare = connexion::pdo()->prepare($request);
        $values["user"] = $user;
        $prepare->execute($values);
        $hasReacted = $prepare->fetch();

        $reactions["hasReacted"] = $hasReacted[0];

        return $reactions;
    }

    /**
     * Permet de reagir à une proposition
     * 
     * @param int $proposal l'identifiant de la proposition
     * @param int $reaction la reaction (son identifiant dans la base)
     * @param int $user l'identifiant de l'utilisateur qui réagit
     * 
     */
    public static function react(int $proposal, int $reaction, int $user){
        $request = "INSERT INTO proposal_reaction(REP_user_NB, REP_proposal_NB, REP_reaction_NB) VALUES (:user, :proposal, :reaction)";
        $prepare = connexion::pdo()->prepare($request);
        $values["user"] = $user;
        $values["reaction"] = $reaction;
        $values["proposal"] = $proposal;

        try{
            $prepare->execute($values);            
        }catch (PDOException $e){
            //Généralement une erreur PDOException 23000 issue d'un doublons de clé primaire signifiant que l'utilisateur à déjà réagit au message.
            return false;
        }

        return true;
    }
}


?>