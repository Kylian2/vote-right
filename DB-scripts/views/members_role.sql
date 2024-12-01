CREATE OR REPLACE VIEW members_role AS 
SELECT  MEM_community_NB, USR_id_NB, USR_firstname_VC, USR_lastname_VC, ROL_label_VC, MEM_role_NB
FROM user
INNER JOIN member ON MEM_user_NB = USR_id_NB
INNER JOIN role ON ROL_id_NB = MEM_role_NB
ORDER BY MEM_role_NB, MEM_community_NB, USR_firstname_VC, USR_lastname_VC, USR_id_NB;