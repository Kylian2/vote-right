DELIMITER //

CREATE FUNCTION getVotesForProposal(idProposal INT, idRound TINYINT) 
RETURNS TEXT
NOT DETERMINISTIC
BEGIN
    DECLARE possibility VARCHAR(100);
    DECLARE nbVotes INT;
    DECLARE totalNbVotes INT;
    DECLARE percentNbVotes INT;
    DECLARE possibilitiesTable TEXT DEFAULT '[]';  -- Initial empty array as a string
    DECLARE fin_cursor INT DEFAULT 0;
    DECLARE result TEXT DEFAULT '';

    DECLARE possibilities CURSOR FOR 
        SELECT POS_label_VC, COUNT(*) AS nbVotes
        FROM vote_detail
        INNER JOIN possibility ON DET_choice_NB = POS_id_NB
        WHERE DET_proposal_NB = idProposal AND DET_round_NB = idRound
        GROUP BY POS_label_VC;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin_cursor = 1;

    SELECT COUNT(*) INTO totalNbVotes
    FROM vote_detail
    WHERE DET_proposal_NB = idProposal AND DET_round_NB = idRound;

    OPEN possibilities;

    makejson: LOOP
        FETCH possibilities INTO possibility, nbVotes;

        IF fin_cursor THEN
            LEAVE makejson;
        END IF;

        SET percentNbVotes = (nbVotes * 100) / totalNbVotes;

        SET result = CONCAT(
            result, 
            IF(result != '', ',', ''),
            '{"POS_label_VC": "', possibility, '",',
            '"POS_nbVotes_NB": ', CAST(nbVotes AS CHAR), ',',
            '"POS_percentNbVotes_NB": ', CAST(percentNbVotes AS CHAR), '}'
        );

    END LOOP;

    CLOSE possibilities;

    RETURN CONCAT('[', result, ']');
END //

DELIMITER ;
