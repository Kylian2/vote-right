<?php 

@require_once('models/model.php');

class Report extends Model{

    public int $RPT_user_NB;
    public int $RPT_comment_NB;
    public int $RPT_reason_NB; 
    public string $RPT_status_VC; 
    public DATE $RPT_creation_DATE;

    public string $RPT_firstname_VC; 
    public string $RPT_lastname_VC; 
    public string $RPT_message_VC; 
    public string $RPT_label_VC; 
    public string $RPT_commentId_NB; 
    
    public static function resolveById(array $id){
        $request = 'UPDATE report 
                    SET RPT_status_VC = "Résolu"
                    WHERE RPT_user_NB = :userId
                    AND RPT_comment_NB = :commentId';
        $prepare = connexion::pdo()->prepare($request);

        $values = array(
            "userId" => $id['RPT_user_NB'],
            "commentId" => $id['RPT_comment_NB'],
        );
        $prepare->execute($values);
    }

    public static function getByProposal($proposal){
        $request = 'SELECT COM_id_NB AS RPT_commentId_NB, RPT_user_NB, USR_firstname_VC AS RPT_firstname_VC, USR_lastname_VC AS RPT_lastname_VC, COM_message_VC AS RPT_message_VC, RES_label_VC AS RPT_label_VC 
                    FROM comment C
                    INNER JOIN report R1 ON C.COM_id_NB = R1.RPT_comment_NB
                    INNER JOIN reason R2 ON R1.RPT_reason_NB = R2.RES_id_NB
                    INNER JOIN user U ON C.COM_sender_NB = U.USR_id_NB
                    WHERE COM_proposal_NB = :proposalId
                    AND RPT_status_VC = "En attente"';
        $prepare = connexion::pdo()->prepare($request);

        $values["proposalId"] = $proposal['PRO_id_NB'];
        $prepare->execute($values);
        $reports = $prepare->fetchAll();
        return $reports;
    }
}

?>