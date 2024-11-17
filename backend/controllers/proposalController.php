<?php

@require_once("models/proposal.php");

class ProposalController{

    public static function ongoing(){
        $proposals = Proposal::getOngoing();
        echo json_encode($proposals);
    }

    public static function finished(){
        $proposals = Proposal::getFinished();
        echo json_encode($proposals);
    }

}
?>