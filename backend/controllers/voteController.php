<?php

@require_once('models/vote.php');

class voteController{

    /**
     * Récupère la liste des systèmes de vote
     * 
     * @return void Les resultats sont renvoyées en JSON.
     */
    public static function systems(){
        echo json_encode(Vote::voteSystem());
    }
}