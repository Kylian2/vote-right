DELIMITER //

-- Vérifier que le budget d'une proposition approuvée ne fait pas dépasser le budget total alloué pour son thème
CREATE OR REPLACE TRIGGER update_proposal_budget_check
BEFORE UPDATE ON proposal
FOR EACH ROW
BEGIN
    IF NEW.PRO_approver_NB IS NOT NULL
    AND NEW.PRO_budget_NB IS NOT NULL
    AND (OLD.PRO_budget_NB IS NULL OR NEW.PRO_budget_NB > OLD.PRO_budget_NB)
    AND NEW.PRO_budget_NB + (SELECT COALESCE(SUM(PRO_budget_NB), 0)
                             FROM proposal
                             WHERE PRO_id_NB != NEW.PRO_id_NB
                             AND PRO_theme_NB = NEW.PRO_theme_NB
                             AND PRO_community_NB = NEW.PRO_community_NB) > (SELECT THM_budget_NB
                                                                             FROM theme 
                                                                             WHERE THM_id_NB = NEW.PRO_theme_NB)
    THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Le budget de cette proposition fait dépasser le budget total alloué pour son thème.';
    END IF;
END //

DELIMITER ;