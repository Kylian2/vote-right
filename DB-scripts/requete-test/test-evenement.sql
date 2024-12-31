SELECT VOT_proposal_NB, VOT_round_NB, VOT_end_DATE, VOT_start_DATE, VOT_type_NB
    FROM vote v
    INNER JOIN voting_system ON SYS_id_NB = VOT_type_NB
    WHERE VOT_end_DATE < CURRENT_DATE() AND VOT_valid_BOOL = TRUE AND SYS_nb_rounds_NB != VOT_round_NB 
    AND VOT_round_NB = (SELECT MAX(VOT_round_NB) FROM vote v2 WHERE v.VOT_proposal_NB = v2.VOT_proposal_NB);
    
SELECT COUNT(*) > 0 
        FROM (
            SELECT POS_id_NB, POS_round_NB, COUNT(*) AS nb_votes
            FROM possibility
            INNER JOIN vote_detail ON DET_choice_NB = POS_id_NB
            WHERE POS_proposal_NB = 49 AND POS_round_NB = 1
            GROUP BY POS_id_NB, POS_round_NB
            HAVING COUNT(*) > (
                SELECT COUNT(*) / 2 
                FROM vote_detail 
                WHERE DET_proposal_NB = 49 AND DET_round_NB = 1
            )
        ) AS majority;
        
SELECT * FROM proposal WHERE PRO_id_NB = 49;

SELECT * FROM vote WHERE VOT_proposal_NB = 49;

SELECT * FROM `possibility` WHERE POS_proposal_NB = 49;

SELECT POS_id_NB, POS_label_VC, POS_round_NB, COUNT(*) AS nb_votes
            FROM possibility
            INNER JOIN vote_detail ON DET_choice_NB = POS_id_NB
            WHERE POS_proposal_NB = 49 AND POS_round_NB = 1
            GROUP BY POS_id_NB, POS_round_NB;