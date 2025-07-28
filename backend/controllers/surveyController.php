<?php

@require_once('models/survey.php');

class SurveyController
{

    /**
     * Retourne les sondages de l'utilisateur connecté.
     *
     * @return void
     */
    public static function index()
    {
        $user = SessionGuard::getUser();

        $surveys = Survey::getAllOf($user->USR_id_NB);
        echo json_encode($surveys);
    }
}
