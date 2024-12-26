<?php 

@require_once('models/model.php');

class Theme extends Model{

    public int $THM_id_NB;
    public int $THM_community_NB;
    public string $THM_name_VC;
    public string $THM_budget_VC;

    /**
     * Ajoute un thème à la communauté. Le thème est inseré avec un budget initiale de 0€. 
     * Il est inséré pour chaque periode présente dans les budgets. 
     * 
     * @return bool `true` si l'insertions c'est bien déroulée.
     */
    public function insert(){
        //Récupération de l'indentifiant du thème
        $request = "SELECT MAX(THM_id_NB) FROM theme WHERE THM_community_NB = :community";
        $values['community'] = $this->get('THM_community_NB');
        $prepare = connexion::pdo()->prepare($request);
        $prepare->execute($values);
        $id = $prepare->fetch();
        $id = $id[0]+1;

        //Récupération de la liste des periodes
        $request = "SELECT DISTINCT BUC_period_YEAR FROM community_budget WHERE BUC_community_NB = :community";
        $prepare = connexion::pdo()->prepare($request);
        $prepare->execute($values);
        $periods = $prepare->fetchAll();

        $request = "INSERT INTO theme(THM_id_NB, THM_name_VC, THM_community_NB) VALUES (:id, :name, :community)";
        $prepare = connexion::pdo()->prepare($request);
        $values['id'] = $id;
        $values['name'] = $this->get('THM_name_VC');
        $prepare->execute($values);

        $this->set('THM_id_NB', $id);

        //Insertions du thèmes dans les budgets de chaque periode (initialisé à 0€).
        foreach($periods as $period){
            $request = "INSERT INTO theme_budget(BUT_theme_NB, BUT_community_NB, BUT_period_YEAR, BUT_amount_NB)
                     VALUES (:id, :community, :period, 0)";
            $prepare = connexion::pdo()->prepare($request);
            unset($values['name']);
            $values['period'] = $period[0];
            $prepare->execute($values);
        }
        return true;
    }

}