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
    public string $INV_recipient_email_VC;

    public static function generateId(int $length = 10) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $randomIndex = random_int(0, $charactersLength - 1);
            $randomString .= $characters[$randomIndex];
        }
    
        return $randomString;
    }

    public function insert(){
        $request = "INSERT INTO invitation(INV_id_VC, INV_code_NB, INV_issue_DATE, INV_sender_NB, INV_recipient_NB, INV_community_NB)
                    VALUES (:id, :code, DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY), :sender, (SELECT USR_id_NB FROM user WHERE USR_email_VC = :email), :community)";  
        $prepare = connexion::pdo()->prepare($request);
        $values['id'] = self::generateId();
        $this->set('INV_id_VC', $values['id']);
        $values['code'] = random_int(100000, 999999);
        $this->set('INV_code_NB', $values['code']);
        $values['sender'] = $this->get('INV_sender_NB');
        $values['email'] = $this->get('INV_recipient_email_VC');
        $values['community'] = $this->get('INV_community_NB');
        try{
            $prepare->execute($values);
            return true;
        }catch(PDOException $e){
            if($e->errorInfo[1] === 1048){
                return "Unknown user";
            }
            if($e->errorInfo[1] === 1062){
                return "Already used id";
            }
            echo json_encode($e);
            return false;
        }
    }

    public static function getById(string $id){
        $request = 'SELECT INV_id_VC, INV_recipient_NB, INV_community_NB, USR_firstname_VC AS INV_sender_firstname_VC, USR_lastname_VC AS INV_sender_lastname_VC, DATEDIFF(CURRENT_DATE, INV_issue_DATE) AS INV_joursDepuisInvitation_NB
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
                    FROM invitation  
                    WHERE INV_id_VC = :id';
        $prepare = connexion::pdo()->prepare($request);

        $value['id'] = $id;
        $prepare->execute($value);
        $code = $prepare->fetch();

        return $code[0];
    }

    public static function accept(string $invitation){
        $request = 'UPDATE invitation SET INV_acceptance_DATE = CURRENT_DATE() WHERE INV_id_VC = :invitationId';
        $prepare = connexion::pdo()->prepare($request);

        $values = array(
            "invitationId" => $invitation,
        );
        $prepare->execute($values);
    }

    public static function reject(string $invitation){
        $request = 'DELETE FROM invitation WHERE INV_id_VC = :invitationId';
        $prepare = connexion::pdo()->prepare($request);

        $values = array(
            "invitationId" => $invitation,
        );
        $prepare->execute($values);
    }
}

?>