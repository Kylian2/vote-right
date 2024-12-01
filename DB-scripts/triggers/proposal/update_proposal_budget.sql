DELIMITER //

-- Vérifier que le budget d'une proposition approuvée ne fait pas dépasser le budget total alloué pour son thème
CREATE OR REPLACE TRIGGER update_proposal_budget
BEFORE UPDATE ON proposal
FOR EACH ROW
BEGIN
    DECLARE sumBudgetsProposals INT;
    DECLARE maximumBudgetTheme INT;

    IF NEW.PRO_approver_NB IS NOT NULL
    AND NEW.PRO_budget_NB IS NOT NULL
    AND (OLD.PRO_budget_NB IS NULL OR NEW.PRO_budget_NB > OLD.PRO_budget_NB) THEN

        SELECT COALESCE(SUM(PRO_budget_NB), 0) + NEW.PRO_budget_NB INTO sumBudgetsProposals
        FROM proposal
        WHERE PRO_id_NB != NEW.PRO_id_NB
        AND PRO_theme_NB = NEW.PRO_theme_NB
        AND PRO_community_NB = NEW.PRO_community_NB
        AND PRO_period_YEAR = NEW.PRO_period_YEAR;

        SELECT BUT_amount_NB INTO maximumBudgetTheme
        FROM theme_budget
        WHERE BUT_community_NB = NEW.PRO_community_NB
        AND BUT_theme_NB = NEW.PRO_theme_NB
        AND BUT_period_YEAR = NEW.PRO_period_YEAR;

        IF sumBudgetsProposals > maximumBudgetTheme THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Erreur : Le budget de cette proposition fait dépasser le budget total alloué pour son thème.';
        END IF;
    END IF;
END //

DELIMITER ;