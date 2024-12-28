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
}

?>