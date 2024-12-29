<?php 

@require_once('models/model.php');

class Invitation extends Model{

    public string $INV_id_VC;
    public int $INV_code_NB;
    public DateTime $INV_issue_DATE;
    public DateTime $INV_acceptance_DATE;
    public int $INV_sender_NB;
    public string $INV_recipient_NB;
    public int $INV_community_NB;

    public int $INV_joursDepuisInvitation_NB;
    public string $INV_sender_firstname_VC;
    public string $INV_sender_lastname_VC;

    public static function getById(string $id){
        $request = 'SELECT INV_recipient_NB, INV_community_NB, USR_firstname_VC AS INV_sender_firstname_VC, USR_lastname_VC AS INV_sender_lastname_VC, DATEDIFF(CURRENT_DATE, INV_issue_DATE) AS INV_joursDepuisInvitation_NB
                    FROM invitation I 
                    INNER JOIN user U ON I.INV_sender_NB = U.USR_id_NB
                    WHERE INV_id_VC = :id
                    AND INV_acceptance_DATE IS NULL';
        $prepare = connexion::pdo()->prepare($request);

        $value['id'] = $id;
        $prepare->execute($value);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "invitation");
        $invitation = $prepare->fetch();

        return $invitation;
    }

    public static function getCodeById(string $id){
        $request = 'SELECT INV_code_NB
                    FROM invitation I 
                    WHERE INV_id_VC = :id';
        $prepare = connexion::pdo()->prepare($request);

        $value['id'] = $id;
        $prepare->execute($value);
        $code = $prepare->fetch();

        return $code;
    }

    public static function accept(array $params){
        $currentDate = date('Y-m-d');
        $request = 'UPDATE invitation 
                SET INV_acceptance_DATE = :currentDate 
                WHERE INV_id_VC = :invitationId';
        $prepare = connexion::pdo()->prepare($request);

        $values = array(
            "currentDate" => $currentDate,
            "invitationId" => $params["INV_id_VC"],
        );
        $prepare->execute($values);
    }

    public static function reject(array $params){
        $currentDate = date('Y-m-d');
        $request = 'DELETE 
                FROM invitation
                WHERE INV_id_VC = :invitationId';
        $prepare = connexion::pdo()->prepare($request);

        $values = array(
            "invitationId" => $params["INV_id_VC"],
        );
        $prepare->execute($values);
    }
}

?>