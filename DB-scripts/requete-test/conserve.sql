CREATE OR REPLACE VIEW waiting_report AS
SELECT RES_label_VC, COM_message_VC, USR_firstname_VC, USR_lastname_VC, RPT_creation_DATE , COM_proposal_NB, PRO_community_NB
FROM report
INNER JOIN comment ON COM_id_NB = RPT_comment_NB
INNER JOIN reason ON RPT_reason_NB = RES_id_NB
INNER JOIN user ON COM_sender_NB = USR_id_NB
INNER JOIN proposal ON COM_proposal_NB = PRO_id_NB
WHERE RPT_status_VC = 'En attente' AND COM_suppressor_NB IS NULL;

CREATE OR REPLACE VIEW proposal_total_reaction AS 
SELECT pro_id_nb, pro_title_vc,
	SUM(CASE WHEN rep_reaction_nb = 3 THEN 1 ELSE 0 END) AS 'nblove',
	SUM(CASE WHEN rep_reaction_nb = 1 THEN 1 ELSE 0 END) AS 'nblike',
    SUM(CASE WHEN rep_reaction_nb = 2 THEN 1 ELSE 0 END) AS 'nbdislike',
    SUM(CASE WHEN rep_reaction_nb = 4 THEN 1 ELSE 0 END) AS 'nbhate',
    COUNT(rea_id_nb) as 'nbtotal', 
    pro_community_nb, pro_creation_date, pro_status_vc
FROM proposal 
LEFT JOIN proposal_reaction ON rep_proposal_nb = pro_id_nb
LEFT JOIN reaction ON rea_id_nb = rep_reaction_nb
WHERE pro_deleter_nb IS NULL
GROUP BY pro_id_nb, pro_title_vc
ORDER BY nblove DESC, nblike DESC, nbdislike DESC, nbhate DESC, nbtotal DESC;

CREATE OR REPLACE VIEW comment_total_reaction AS
SELECT pro_id_nb, pro_title_vc, com_id_nb, com_message_vc, usr_id_nb, usr_firstname_vc, pro_community_nb,
	SUM(CASE WHEN rec_reaction_nb = 3 THEN 1 ELSE 0 END) AS 'nblove',
	SUM(CASE WHEN rec_reaction_nb = 1 THEN 1 ELSE 0 END) AS 'nblike',
    SUM(CASE WHEN rec_reaction_nb = 2 THEN 1 ELSE 0 END) AS 'nbdislike',
    SUM(CASE WHEN rec_reaction_nb = 4 THEN 1 ELSE 0 END) AS 'nbhate',
    COUNT(rec_reaction_nb) as 'nbtotal'
FROM proposal 
INNER JOIN comment ON com_proposal_nb = pro_id_nb
LEFT JOIN comment_reaction ON rec_comment_nb = com_id_nb
LEFT JOIN reaction ON rea_id_nb = rec_reaction_nb
INNER JOIN user ON com_sender_nb = usr_id_nb
WHERE com_suppressor_nb IS NULL AND pro_status_vc = 'En cours' AND pro_deleter_nb IS NULL
GROUP BY pro_id_nb, pro_title_vc, com_id_nb, com_message_vc, usr_id_nb , usr_firstname_vc
ORDER BY nblove DESC, nblike DESC, nbdislike DESC, nbhate DESC, nbtotal DESC;

--Affiche le nombre de personne aimant une proposition, et le nombre de commentaire sur cette proposition
SELECT pro_id_nb, pro_title_vc, pro_community_nb,
MAX(nblove) + MAX(nblike) as 'totallike', 
MAX(nbhate) + MAX(nbdislike) as 'totaldislike',
COUNT(com_id_nb) as 'nbcomment'
FROM proposal_total_reaction
LEFT JOIN comment ON pro_id_nb = com_proposal_nb
WHERE pro_community_nb = 13 
AND pro_status_vc = 'En cours'
GROUP BY pro_id_nb, pro_title_vc, pro_community_nb;

-- Les vote qui débutent ou commencent prochainement (peut etre les triés)
SELECT pro_id_nb, pro_title_vc, vot_start_date, vot_end_date, vot_round_nb
FROM proposal
INNER JOIN vote ON pro_id_nb = vot_proposal_nb
WHERE 
((CURDATE() <= vot_start_date AND vot_start_date <= CURDATE() + INTERVAL 7 DAY)
OR 
(CURDATE() <= vot_end_date AND vot_end_date <= CURDATE() + INTERVAL 7 DAY))
AND pro_deleter_nb IS NULL 
AND pro_status_vc = 'En cours'
ORDER BY vot_start_date, vot_end_date;

-- Affiche les budgets de chacun des thèmes du groupe et le budget consommé pour ce thème
-- A METTRE A JOUR AVEC LES ANNEES
CREATE OR REPLACE VIEW community_budget AS 
SELECT CMY_id_NB, THM_id_NB, THM_name_VC, THM_budget_NB, 
COALESCE(SUM(CASE WHEN PRO_status_VC = 'Validée' THEN PRO_budget_NB ELSE 0 END), 0) AS CMY_used_budget_NB
FROM community
INNER JOIN theme ON CMY_id_NB = THM_community_NB
LEFT JOIN proposal ON PRO_community_NB = THM_community_NB AND PRO_theme_NB = THM_id_nb
GROUP BY CMY_id_NB, THM_id_NB, THM_name_VC, THM_budget_NB
ORDER BY CMY_id_NB, THM_id_NB;

CREATE OR REPLACE VIEW members_role AS 
SELECT  MEM_community_NB, USR_id_NB, USR_firstname_VC, USR_lastname_VC, ROL_label_VC, MEM_role_NB
FROM user
INNER JOIN member ON MEM_user_NB = USR_id_NB
INNER JOIN role ON ROL_id_NB = MEM_role_NB
ORDER BY MEM_role_NB, MEM_community_NB, USR_firstname_VC, USR_lastname_VC, USR_id_NB;