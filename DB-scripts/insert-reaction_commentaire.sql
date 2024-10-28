INSERT INTO comment_reaction (REC_comment_NB, REC_user_NB, REC_reaction_NB) VALUES
(12, NULL, 2),
(27, NULL, 1),
(21, NULL, 3),
(14, NULL, 1),
(22, NULL, 1),
(37, NULL, 2),
(20, NULL, 2),
(27, NULL, 3),
(29, NULL, 4),
(37, NULL, 2),
(28, NULL, 2),
(50, NULL, 2),
(33, NULL, 2),
(4, NULL, 1),
(8, NULL, 1),
(36, NULL, 2),
(16, NULL, 2),
(3, NULL, 2),
(1, NULL, 1),
(35, NULL, 3),
(31, NULL, 4),
(12, NULL, 3),
(1, NULL, 1),
(22, NULL, 1),
(16, NULL, 3),
(33, NULL, 3),
(20, NULL, 4),
(33, NULL, 2),
(21, NULL, 1),
(7, NULL, 2),
(25, NULL, 2),
(38, NULL, 2),
(1, NULL, 2),
(43, NULL, 1),
(7, NULL, 1),
(42, NULL, 3),
(4, NULL, 1),
(22, NULL, 3),
(45, NULL, 1),
(4, NULL, 3),
(43, NULL, 2),
(1, NULL, 2),
(5, NULL, 4),
(39, NULL, 2),
(24, NULL, 1),
(4, NULL, 1),
(29, NULL, 3),
(17, NULL, 2),
(28, NULL, 1),
(39, NULL, 1);

select MEM_user_NB, ROL_label_VC
from member M 
inner join role R ON R.ROL_id_NB = M.MEM_role_NB
inner join community C ON M.MEM_community_NB = C.CMY_id_NB
inner join proposal P ON C.CMY_id_NB = P.PRO_community_NB
where PRO_id_NB = 37;

select CMY_id_NB, PRO_id_NB
from community C
inner join member M ON M.MEM_community_NB = C.CMY_id_NB
inner join proposal P ON C.CMY_id_NB = P.PRO_community_NB
where MEM_role_NB = 2;