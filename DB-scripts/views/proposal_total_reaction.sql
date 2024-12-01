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