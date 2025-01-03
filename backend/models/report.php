<?php 

@require_once('models/model.php');

class Report extends Model{

    public int $RPT_user_NB;
    public int $RPT_comment_NB;
    public int $RPT_reason_NB; 
    public string $RPT_status_VC; 

    public string $RPT_firstname_VC; 
    public string $RPT_lastname_VC; 
    public string $RPT_message_VC; 
    public string $RPT_label_VC; 
    public string $RPT_commentId_NB; 

    public static function deleteById(array $id){
        $request = 'CALL delete_comment_and_resolve_reports (:commentId, :userId)';
        $prepare = connexion::pdo()->prepare($request);

        $values = array(
            "userId" => $id['RPT_user_NB'],
            "commentId" => $id['RPT_comment_NB'],
        );
        $prepare->execute($values);
    }
    
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

    public static function getAllOf(int $community){
        $request = 'SELECT COM_id_NB AS RPT_comment_NB, USR_id_NB as RPT_user_NB, USR_firstname_VC AS RPT_firstname_VC, USR_lastname_VC AS RPT_lastname_VC, COM_message_VC AS RPT_message_VC, RES_label_VC AS RPT_label_VC 
                    FROM waiting_report
                    WHERE PRO_community_NB = :community';
        $prepare = connexion::pdo()->prepare($request);

        $values["community"] = $community;
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "report");
        $reports = $prepare->fetchAll();
        return $reports;
    }
}

?>