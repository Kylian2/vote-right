
--Affiche les noms des groupes et leur propositions (avec les thèmes associés)

SELECT CMY_name_VC, PRO_title_VC, THM_name_VC
FROM community
INNER JOIN proposal ON CMY_id_NB = PRO_community_NB
INNER JOIN theme ON THM_id_NB = PRO_theme_NB AND THM_community_NB = PRO_community_NB;

--Affiche les commentaires de chaque proposition
SELECT CMY_name_VC, PRO_title_VC, COM_message_VC
FROM community
INNER JOIN proposal ON CMY_id_NB = PRO_community_NB
INNER JOIN comment ON COM_proposal_NB = PRO_id_NB

--Affiche le nombre de reaction à chaque commentaire
SELECT CMY_name_VC, PRO_title_VC, COM_message_VC, COUNT(*)
FROM community
INNER JOIN proposal ON CMY_id_NB = PRO_community_NB
INNER JOIN comment ON COM_proposal_NB = PRO_id_NB
INNER JOIN comment_reaction ON REC_comment_NB = COM_id_NB
GROUP BY CMY_name_VC, PRO_title_VC, COM_message_VC;

--Le nombre de reaction par propositions
SELECT PRO_id_NB, PRO_title_VC, COUNT(*)
FROM community
INNER JOIN proposal ON CMY_id_NB = PRO_community_NB
INNER JOIN theme ON THM_community_NB = PRO_community_NB AND THM_id_NB = PRO_theme_NB
GROUP BY PRO_id_NB, PRO_title_VC;

--Le nombre de membre dans chaque groupe
SELECT CMY_id_NB, CMY_name_VC, COUNT(*)
FROM member 
INNER JOIN community ON CMY_id_NB = MEM_community_NB
GROUP BY CMY_id_NB, CMY_name_VC

--Le nombre de personne ayant tel ou tel role pour chaque groupe
SELECT CMY_id_NB, CMY_name_VC, ROL_label_VC, COUNT(*)
FROM member 
INNER JOIN community ON CMY_id_NB = MEM_community_NB
INNER JOIN role ON ROL_id_NB = MEM_role_NB
GROUP BY CMY_id_NB, CMY_name_VC, ROL_label_VC;q

--Le nombre de groupe auxquels chaque utilisateur fait parti
SELECT USR_id_NB, USR_lastname_VC, COUNT(MEM_community_NB)
FROM member 
RIGHT JOIN user ON USR_id_NB = MEM_community_NB
GROUP BY USR_id_NB, USR_lastname_VC;

--Affiche le nombre total de réaction pour chaque proposition ainsi que le nombre de j'aime, j'aime pas, adore et deteste (avec le groupe et la date pour les traitements)
-- SUSCEPTIBLE VUE

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

--Affiche le nombre total de réaction pour chaque commentaire d'un groupe ainsi que le nombre de j'aime, j'aime pas, adore et deteste. On affiche la proposition 
--ratachée et l'utilisateur qui l'a envoyé.

CREATE OR REPLACE VIEW comment_total_reaction AS
SELECT pro_id_nb, pro_title_vc, com_id_nb, com_message_vc, usr_id_nb, usr_firstname_vc, pro_community_nb,
	SUM(CASE WHEN rep_reaction_nb = 3 THEN 1 ELSE 0 END) AS 'nblove',
	SUM(CASE WHEN rep_reaction_nb = 1 THEN 1 ELSE 0 END) AS 'nblike',
    SUM(CASE WHEN rep_reaction_nb = 2 THEN 1 ELSE 0 END) AS 'nbdislike',
    SUM(CASE WHEN rep_reaction_nb = 4 THEN 1 ELSE 0 END) AS 'nbhate',
    COUNT(*) as 'nbtotal'
FROM proposal 
INNER JOIN proposal_reaction ON rep_proposal_nb = pro_id_nb
INNER JOIN comment ON com_proposal_nb = pro_id_nb
INNER JOIN reaction ON rea_id_nb = rep_reaction_nb
INNER JOIN user ON com_sender_nb = usr_id_nb
WHERE com_suppressor_nb IS NULL AND pro_status_vc = 'En cours' AND pro_deleter_nb IS NULL
GROUP BY pro_id_nb, pro_title_vc, com_id_nb, com_message_vc, usr_id_nb , usr_firstname_vc
ORDER BY nblove DESC, nblike DESC, nbdislike DESC, nbhate DESC, nbtotal DESC;

--Affiche les propositions publiée cette semaine

--Affiche le nombre de personne aimant une proposition, et le nombre de commentaire sur cette proposition
-- utilisée pour la fonction newsletter_polemic_proposal

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
AND pro_status_vc = 'En cours';