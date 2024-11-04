
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