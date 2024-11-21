<?php

class Proposal extends Model{

    public string $PRO_id_NB;
    public string $PRO_title_VC;
    public string $PRO_color_VC;
    public string $PRO_theme_VC;

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
}


?>