<?php 

@require_once('models/model.php');

class Comment extends Model{

    public int $COM_id_NB;
    public string $COM_creation_DATE;
    public string $COM_message_VC;
    public string $COM_sender_fname_VC;
    public string $COM_sender_lname_VC;

    /**
     * Récupère la liste des commentaires d'une proposition, avec le nom et le prénom de l'envoyeur. 
     * 
     * @param int $proposal L'identifiant de la proposition
     * 
     * @return array Une liste de commentaire
     */
    public static function getCommentsOfProposal(int $proposal){
        $request = "SELECT COM_id_NB, COM_creation_DATE, COM_message_VC, USR_firstname_VC AS COM_sender_fname_VC, USR_lastname_VC AS COM_sender_lname_VC
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

}

?>