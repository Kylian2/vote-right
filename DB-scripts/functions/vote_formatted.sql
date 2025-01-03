DELIMITER //

CREATE OR REPLACE FUNCTION vote_formatted(proposal INT) 
RETURNS TEXT
NOT DETERMINISTIC
BEGIN
    DECLARE fin_cursor INT DEFAULT 0;
    DECLARE positive_list TEXT DEFAULT '[';
    DECLARE negative_list TEXT DEFAULT '[';
    DECLARE first_positive INT DEFAULT 1;
    DECLARE first_negative INT DEFAULT 1;
    DECLARE system INT;
    DECLARE user_id INT;
    DECLARE label VARCHAR(10);

    DECLARE informations CURSOR FOR 
    SELECT DET_user_NB, POS_label_VC
    FROM vote_detail
    INNER JOIN possibility ON DET_proposal_NB = POS_proposal_NB AND POS_round_NB = DET_round_NB
    INNER JOIN vote ON VOT_proposal_NB = DET_proposal_NB AND VOT_round_NB = DET_round_NB
    WHERE (VOT_type_NB = 1 OR VOT_type_NB = 2) AND VOT_round_NB = 1 AND VOT_proposal_NB = proposal
    ORDER BY DET_user_NB;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin_cursor = 1;

    SELECT VOT_type_NB INTO system 
    FROM vote 
    WHERE VOT_round_NB = 1 AND VOT_proposal_NB = proposal
    LIMIT 1;

    OPEN informations;

    fetchinformations: LOOP
        FETCH informations INTO user_id, label;

        IF fin_cursor THEN 
            LEAVE fetchinformations;
        END IF;

        IF label = 'POUR' OR label = 'OUI' THEN
            IF first_positive = 0 THEN
                SET positive_list = CONCAT(positive_list, ', ');
            END IF;
            SET positive_list = CONCAT(positive_list, user_id);
            SET first_positive = 0;
        ELSEIF label = 'CONTRE' OR label = 'NON' THEN
            IF first_negative = 0 THEN
                SET negative_list = CONCAT(negative_list, ', ');
            END IF;
            SET negative_list = CONCAT(negative_list, user_id);
            SET first_negative = 0;
        END IF;
    END LOOP;

    CLOSE informations;

    SET positive_list = CONCAT(positive_list, ']');
    SET negative_list = CONCAT(negative_list, ']');

    RETURN CONCAT(
        '{',
        '"system": ', system, ', ',
        '"positive": ', positive_list, ', ',
        '"negative": ', negative_list,
        '}'
    );
END //

DELIMITER ;
