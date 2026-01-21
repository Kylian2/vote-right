<?php

@require_once('models/model.php');

class Supporting extends Model
{
    public int $SUP_id_NB;
    public int $SUP_community_NB;
    public string $SUP_label_VC;
    public string $SUP_description_TXT;
    public string $created_at;
    public string $updated_at;

    /**
     * Récupère tous les justificatifs demandés pour une communauté
     * @param int $id L'identifiant de la communauté
     * @return array La liste des justificatifs
     */
    public static function getAllOf(int $id)
    {
        $request = "SELECT * FROM supporting WHERE SUP_community_NB = :id";
        $prepare = connexion::pdo()->prepare($request);
        $prepare->execute(['id' => $id]);
        return $prepare->fetchAll(PDO::FETCH_CLASS, 'supporting');
    }

    /**
     * Insère un nouveau justificatif dans la base de données
     * @return bool True si l'insertion a réussi, false sinon
     */
    public function insert()
    {
        $request = "INSERT INTO supporting (SUP_community_NB, SUP_label_VC, SUP_description_TXT) 
                    VALUES (:community, :label, :description)";
        $prepare = connexion::pdo()->prepare($request);
        return $prepare->execute([
            'community' => $this->SUP_community_NB,
            'label' => $this->SUP_label_VC,
            'description' => $this->SUP_description_TXT
        ]);
    }

    /**
     * Supprime un justificatif de la base de données
     * @return bool True si la suppression a réussi, false sinon
     */
    public static function delete(int $id)
    {
        $request = "DELETE FROM supporting WHERE SUP_id_NB = :id";
        $prepare = connexion::pdo()->prepare($request);
        return $prepare->execute(['id' => $id]);
    }
}
