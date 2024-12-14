<?php 

@require_once('models/model.php');

class Invitation extends Model{

    public int $INV_id_VC;
    public string $INV_code_NB;
    public DateTime $INV_issue_DATE;
    public DateTime $INV_acceptance_DATE;
    public int $INV_sender_NB;
    public string $INV_recipient_NB;
    public int $INV_community_NB;

    public string $INV_sender_firstname_VC;
    public string $INV_sender_lastname_VC;

    public static function getById(string $id){
        $request = 'SELECT INV_sender_NB, INV_recipient_NB, INV_community_NB, USR_firstname_VC AS INV_sender_firstname_VC, USR_lastname_VC AS INV_sender_lastname_VC
                    FROM invitation I 
                    INNER JOIN user U ON I.INV_recipient_NB = U.USR_id_NB
                    WHERE INV_id_VC = :id';
        $prepare = connexion::pdo()->prepare($request);
        $values['id'] = $id;
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "invitation");
        $invitation = $prepare->fetch();

        return $invitation;
    }
}

?>