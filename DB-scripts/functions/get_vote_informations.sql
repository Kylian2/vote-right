DELIMITER //

CREATE OR REPLACE FUNCTION get_vote_informations(proposal INT) 
RETURNS TEXT
NOT DETERMINISTIC
BEGIN
    DECLARE proposal_NB INT;
    DECLARE round_NB TINYINT;
    DECLARE valid_BOOL BOOLEAN;
    DECLARE start_DATE DATETIME;
    DECLARE end_DATE DATETIME;
    DECLARE type_VC VARCHAR(100);
    DECLARE type_NB TINYINT;
    DECLARE one_vote TEXT;
    DECLARE list_vote TEXT DEFAULT '[';

    DECLARE fin_cursor INT DEFAULT 0;
    DECLARE first INT DEFAULT 1;

    DECLARE informations CURSOR FOR 
    SELECT VOT_proposal_NB, VOT_round_NB, VOT_valid_BOOL, VOT_start_DATE, VOT_end_DATE,SYS_label_VC AS VOT_type_VC, VOT_type_NB
    FROM vote
    INNER JOIN voting_system ON VOT_type_NB = SYS_id_NB
    WHERE VOT_proposal_NB = proposal
    ORDER BY VOT_round_NB;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin_cursor = 1;

    OPEN informations;

    fetchinformations: LOOP
        FETCH informations INTO proposal_NB, round_NB, valid_BOOL, start_DATE, end_DATE, type_VC, type_NB;

        IF fin_cursor THEN 
            LEAVE fetchinformations;
        END IF;

        SET one_vote = CONCAT(
            '{',
            '"VOT_proposal_NB": ', proposal_NB, ',',
            '"VOT_round_NB": ', round_NB, ',',
            '"VOT_valid_BOOL": "', COALESCE(valid_BOOL, 'null'), '",',
            '"VOT_start_DATE": "', start_DATE, '",',
            '"VOT_end_DATE": "', end_DATE, '",',
            '"VOT_type_NB": ', type_NB, ',',
            '"VOT_type_VC": "', type_VC, '",',
            '"VOT_possibilities_TAB":', get_possibilities(proposal_NB, round_NB),
            '}'
        );

        IF first = 1 THEN
            SET list_vote = CONCAT(list_vote, one_vote);
            SET first = 0;
        ELSE
            SET list_vote = CONCAT(list_vote, ',', one_vote);
        END IF;
    END LOOP;

    CLOSE informations;

    SET list_vote = CONCAT(list_vote, ']');

    RETURN list_vote;

END //

DELIMITER ;