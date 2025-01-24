DELIMITER //

CREATE OR REPLACE EVENT new_year
ON SCHEDULE EVERY 1 MINUTE
STARTS CURRENT_TIMESTAMP
DO
BEGIN
    -- Première requête INSERT pour la table community_budget
    INSERT INTO community_budget (BUC_community_NB, BUC_period_YEAR, BUC_amount_NB, BUC_fixed_fees_NB) 
    SELECT DISTINCT 
        cb.BUC_community_NB, YEAR(NOW()) + 1, BUC_amount_NB, BUC_fixed_fees_NB  
    FROM community_budget cb
    WHERE BUC_community_NB NOT IN (
        SELECT BUC_community_NB 
        FROM community_budget 
        WHERE BUC_period_YEAR = YEAR(NOW()) + 1
    )
    AND BUC_period_YEAR = (
        SELECT MAX(BUC_period_YEAR) 
        FROM community_budget 
        WHERE BUC_community_NB = cb.BUC_community_NB
    );

    -- Deuxième requête INSERT pour la table theme_budget
    INSERT INTO theme_budget (BUT_community_NB, BUT_theme_NB, BUT_period_YEAR, BUT_amount_NB) 
    SELECT tb.BUT_community_NB, BUT_theme_NB, YEAR(NOW()) + 1, 0
    FROM theme_budget tb
    WHERE BUT_period_YEAR = YEAR(NOW());
END //

DELIMITER ;
