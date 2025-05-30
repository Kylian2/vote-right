
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
SELECT CMY_name_VC, PRO_title_VC, COM_message_VC, COUNT(REC_reaction_NB)
FROM community
INNER JOIN proposal ON CMY_id_NB = PRO_community_NB
INNER JOIN comment ON COM_proposal_NB = PRO_id_NB
LEFT JOIN comment_reaction ON REC_comment_NB = COM_id_NB
GROUP BY CMY_name_VC, PRO_title_VC, COM_message_VC;

--Le nombre de reaction par propositions
SELECT PRO_id_NB, PRO_title_VC, COUNT(*)
FROM community
INNER JOIN proposal ON CMY_id_NB = PRO_community_NB
INNER JOIN theme ON THM_community_NB = PRO_community_NB AND THM_id_NB = PRO_theme_NB
INNER JOIN proposal_reaction ON PRO_id_NB = REP_proposal_NB
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
SELECT mem_user_nb, USR_lastname_VC, COUNT(MEM_community_NB)
FROM user 
RIGHT JOIN member ON USR_id_NB = MEM_user_NB
GROUP BY mem_user_nb, USR_lastname_VC;

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

-- Affiche les membres d'un groupe avec leur id et leur rôle
SELECT MEM_user_NB, ROL_label_VC
FROM member M
INNER JOIN role R ON M.MEM_role_NB = R.ROL_id_NB
WHERE MEM_community_NB = 1;

-- Affiche les membres qui n'ont pas été invités alors qu'ils font parti d'un groupe
SELECT M.MEM_user_NB
FROM member M
LEFT JOIN user U ON M.MEM_user_NB = U.USR_id_NB
LEFT JOIN invitation I ON U.USR_id_NB = I.INV_recipient_NB 
    			AND I.INV_community_NB = M.MEM_community_NB
WHERE I.INV_recipient_NB IS NULL 
AND M.MEM_community_NB = 1;

-- Affiche les commentaires d'une proposition avec les informations importantes
SELECT COM_id_NB, COM_sender_NB, COM_message_VC, COM_creation_DATE, PRO_community_NB
FROM comment C
INNER JOIN proposal P ON C.COM_proposal_NB = P.PRO_id_NB
AND P.PRO_id_NB = 2;

-- Affiche les roles de chaque membres au sein des différentes groupes
CREATE OR REPLACE VIEW members_role AS 
SELECT  MEM_community_NB, USR_id_NB, USR_firstname_VC, USR_lastname_VC, ROL_label_VC, MEM_role_NB
FROM user
INNER JOIN member ON MEM_user_NB = USR_id_NB
INNER JOIN role ON ROL_id_NB = MEM_role_NB
ORDER BY MEM_role_NB, MEM_community_NB, USR_firstname_VC, USR_lastname_VC, USR_id_NB;

-- CETTE REQUETE 
SELECT * 
FROM proposal
INNER JOIN vote ON VOT_proposal_NB = PRO_id_NB
INNER JOIN voting_system ON VOT_type_NB = SYS_id_NB
WHERE VOT_assessor_NB IS NOT NULL AND pro_status_VC = 'En cours';

--remplir les demandes formelles 

INSERT INTO formal_request (FOR_user_NB, FOR_proposal_NB)
SELECT m.MEM_user_NB, p.PRO_id_NB
FROM proposal p
JOIN member m ON m.MEM_community_NB = p.PRO_community_NB
WHERE RAND() < 0.4 AND MEM_user_NB NOT IN (SELECT FOR_user_NB FROM formal_request WHERE FOR_proposal_NB = p.PRO_id_NB);
