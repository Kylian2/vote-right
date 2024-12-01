DELIMITER //

-- Vérifier que le budget d'un thème ne fait pas dépasser le budget total alloué pour son groupe
CREATE OR REPLACE TRIGGER update_theme_budget_amount
BEFORE UPDATE ON theme_budget
FOR EACH ROW
BEGIN
    DECLARE sumBudgetsThemes INT;
    DECLARE maximumBudgetCommunity INT;
    
    IF OLD.BUT_amount_NB != NEW.BUT_amount_NB THEN 
        
        SELECT COALESCE(SUM(BUT_amount_NB), 0) + NEW.BUT_amount_NB INTO sumBudgetsThemes
        FROM theme_budget
        WHERE BUT_theme_NB != NEW.BUT_theme_NB
        AND BUT_community_NB = NEW.BUT_community_NB
        AND BUT_period_YEAR = NEW.BUT_period_YEAR;

        SELECT BUC_amount_NB - BUC_fixed_fees_NB INTO maximumBudgetCommunity
        FROM community_budget
        WHERE BUC_community_NB = NEW.BUT_community_NB
        AND BUC_period_YEAR = NEW.BUT_period_YEAR;

        IF sumBudgetsThemes > maximumBudgetCommunity THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Erreur : Le budget de ce thème fait dépasser le budget total alloué pour son groupe.';
        END IF;
    END IF;
END //

DELIMITER ;