<?php

@require_once('models/model.php');

class Survey extends Model
{

    public int $SUR_id_NB;
    public string $SUR_title_VC;
    public string $SUR_description_TXT;
    public string $SUR_start_DATE;
    public string $SUR_end_DATE;
    public string $SUR_color_VC;
    public bool $SUR_anonymous_BOOL;
    public int $SUR_reminder_email_NB;
    public int $SUR_result_email_NB;
    public int $SUR_user_NB;
    public User $SUR_user_USER;

    public int $SUR_participant_count_NB;
    public int $SUR_answer_count_NB;

    /**
     * Retourne tous les sondages d'un utilisateur. Les sondages récuperés sont ceux créés par l'utilisateur ou ceux auxquels il est déclaré comme participant.
     * 
     * @param int $userId L'identifiant de l'utilisateur.
     * @return Survey[] Un tableau d'objets Survey.
     */
    public static function getAllOf(int $userId)
    {
        $request = "SELECT SUR_id_NB, SUR_title_VC, SUR_description_TXT, SUR_start_DATE, SUR_end_DATE, SUR_color_VC, SUR_user_NB, COUNT(DISTINCT sp.SPA_user_NB) AS SUR_participant_count_NB, COUNT(DISTINCT sa.SAN_user_NB) AS SUR_answer_count_NB, s.created_at, s.updated_at 
                    FROM survey s
                    INNER JOIN survey_participant sp ON s.SUR_id_NB = sp.SPA_survey_NB
                    INNER JOIN survey_answer sa ON s.SUR_id_NB = sa.SAN_survey_NB
                    WHERE SUR_id_NB IN (SELECT SUR_id_NB FROM survey s INNER JOIN survey_participant sp ON s.SUR_id_NB = sp.SPA_survey_NB WHERE s.SUR_user_NB = :user OR sp.SPA_user_NB = :user)
                    GROUP BY SUR_id_NB
                    ORDER BY created_at DESC";
        $prepare = connexion::pdo()->prepare($request);
        $prepare->bindParam(':user', $userId, PDO::PARAM_INT);
        $prepare->execute();
        $result = $prepare->fetchAll(PDO::FETCH_CLASS, 'Survey');

        $result_by_user = [];
        foreach ($result as $survey) {
            if (!isset($result_by_user[$survey->SUR_user_NB])) {
                $result_by_user[$survey->SUR_user_NB] = [];
            }
            $result_by_user[$survey->SUR_user_NB][] = $survey;
        }

        $request = "SELECT USR_id_NB, USR_firstname_VC, USR_lastname_VC FROM user WHERE USR_id_NB = :user";
        $prepare = connexion::pdo()->prepare($request);

        foreach ($result_by_user as $userId => $surveys) {
            $prepare->bindParam(':user', $userId, PDO::PARAM_INT);
            $prepare->execute();
            $user = $prepare->fetchAll(PDO::FETCH_CLASS, 'User')[0];

            foreach ($surveys as $survey) {
                $survey->SUR_user_USER = $user;
            }
        }

        return $result;
    }

    public static function getUserSurveysData(int $userId)
    {
        $request = "SELECT COUNT(DISTINCT SUR_id_NB) as survey_count, COUNT(DISTINCT SAN_user_NB, SAN_survey_NB, SAN_question_NB) as survey_answers_count
                    FROM survey s
                    INNER JOIN survey_answer sa ON s.SUR_id_NB = sa.SAN_survey_NB
                    WHERE SUR_user_NB = :user";
        $prepare = connexion::pdo()->prepare($request);
        $prepare->bindParam(':user', $userId, PDO::PARAM_INT);
        $prepare->execute();
        $result = $prepare->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}
