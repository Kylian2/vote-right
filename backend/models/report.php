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

    public function delete(int $moderator){
        $request = 'SELECT MEM_role_NB 
        FROM comment 
        INNER JOIN proposal ON PRO_id_NB = COM_proposal_NB
        INNER JOIN member ON MEM_community_NB = PRO_community_NB
        WHERE MEM_user_NB = :moderator AND COM_id_NB = :comment';
        $prepare = connexion::pdo()->prepare($request);
        $values['moderator'] = $moderator;
        $values['comment'] = $this->get('RPT_comment_NB');
        $prepare->execute($values);
        $role = $prepare->fetch();

        if($role[0] != ROLE_ADMIN && $role[0] != ROLE_MODERATOR){
            return false;
        }

        $request = 'CALL delete_comment_and_resolve_reports (:comment, :moderator)';
        $prepare = connexion::pdo()->prepare($request);
        $prepare->execute($values);
        return true;
    }
    
    public function close(int $moderator){
        $request = 'SELECT MEM_role_NB 
        FROM comment 
        INNER JOIN proposal ON PRO_id_NB = COM_proposal_NB
        INNER JOIN member ON MEM_community_NB = PRO_community_NB
        WHERE MEM_user_NB = :moderator AND COM_id_NB = :comment';
        $prepare = connexion::pdo()->prepare($request);
        $values['moderator'] = $moderator;
        $values['comment'] = $this->get('RPT_comment_NB');
        $prepare->execute($values);
        $role = $prepare->fetch();

        if($role[0] != ROLE_ADMIN && $role[0] != ROLE_MODERATOR){
            return false;
        }
        unset($values['moderator']);
        $request = "UPDATE report 
                    SET RPT_status_VC = 'Résolu'
                    WHERE RPT_user_NB = :user
                    AND RPT_comment_NB = :comment";
        $prepare = connexion::pdo()->prepare($request);

        $values["user"] = $this->get('RPT_user_NB');

        $prepare->execute($values);
        return true;
    }

    public static function getAllOf(int $community){
        $request = 'SELECT COM_id_NB AS RPT_comment_NB, RPT_user_NB, USR_firstname_VC AS RPT_firstname_VC, USR_lastname_VC AS RPT_lastname_VC, COM_message_VC AS RPT_message_VC, RES_label_VC AS RPT_label_VC 
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