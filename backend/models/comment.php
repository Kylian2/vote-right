<?php 

@require_once('models/model.php');

class Comment extends Model{

    public int $COM_id_NB;
    public string $COM_creation_DATE;
    public string $COM_message_VC;
    public int $COM_proposal_NB;

    //Les infos de l'utilisateur qui envoie le commentaire
    public string $COM_sender_fname_VC;
    public string $COM_sender_lname_VC;
    public string $COM_sender_NB;

    /**
     * Récupère la liste des commentaires d'une proposition, avec le nom et le prénom de l'envoyeur. 
     * 
     * @param int $proposal L'identifiant de la proposition
     * 
     * @return array Une liste de commentaire
     */
    public static function getCommentsOfProposal(int $proposal){
        $request = "SELECT COM_id_NB, COM_creation_DATE, COM_message_VC, COM_sender_NB, USR_firstname_VC AS COM_sender_fname_VC, USR_lastname_VC AS COM_sender_lname_VC
                    FROM comment 
                    INNER JOIN user ON COM_sender_NB = USR_id_NB
                    WHERE COM_proposal_NB = :proposal AND COM_suppressor_NB IS NULL
                    ORDER BY COM_creation_DATE;";
        $prepare = connexion::pdo()->prepare($request);
        $values['proposal'] = $proposal;
        $prepare->execute($values);
        $prepare->setFetchmode(PDO::FETCH_CLASS, "comment");
        $comments = $prepare->fetchAll();
        return $comments;
    }

    /**
     * Insere un commentaire dans la base de données
     * 
     * @return bool true si l'insertion s'est bien déroulée. 
     */
    public function insert(){
        $request = "INSERT INTO comment(COM_message_VC, COM_proposal_NB, COM_sender_NB) VALUES (:message, :proposal, :sender)";
        $prepare = connexion::pdo()->prepare($request);

        $values = array(
            "message" => $this->COM_message_VC,
            "proposal" => $this->COM_proposal_NB,
            "sender" => $this->COM_sender_NB,
        );

        $prepare->execute($values);
        $id = connexion::pdo()->lastInsertId();
        $this->set("COM_id_NB", $id);

        return true;
    }


}

?>