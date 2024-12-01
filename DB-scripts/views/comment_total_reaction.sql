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