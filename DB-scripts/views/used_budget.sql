CREATE OR REPLACE VIEW used_budget AS
SELECT BUT_period_YEAR, CMY_id_NB, THM_id_NB, THM_name_VC, BUT_amount_NB, 
COALESCE(SUM(
    CASE 
    WHEN PRO_status_VC = 'Validée' AND BUT_period_YEAR = PRO_period_YEAR
    THEN PRO_budget_NB ELSE 0 END), 0) AS BUT_used_budget_NB
FROM community
INNER JOIN theme ON CMY_id_NB = THM_community_NB
INNER JOIN theme_budget ON THM_id_NB = BUT_theme_NB AND THM_community_NB = BUT_community_NB
LEFT JOIN proposal ON PRO_community_NB = THM_community_NB AND PRO_theme_NB = THM_id_nb
GROUP BY CMY_id_NB, BUT_period_YEAR, THM_id_NB, THM_name_VC
ORDER BY CMY_id_NB, BUT_period_YEAR DESC, THM_id_NB, THM_name_VC;