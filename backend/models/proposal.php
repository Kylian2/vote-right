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
}


?>