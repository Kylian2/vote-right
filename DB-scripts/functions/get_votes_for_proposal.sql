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
        FROM Detail_Vote
        INNER JOIN Possibility ON DET_choice_NB = POS_id_NB
        WHERE DET_proposal_NB = idProposal AND DET_round_NB = idRound
        GROUP BY POS_label_VC;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET fin_cursor = 1;

    -- Get total number of votes
    SELECT COUNT(*) INTO totalNbVotes
    FROM Detail_Vote
    WHERE DET_proposal_NB = idProposal AND DET_round_NB = idRound;

    OPEN possibilities;

    makejson: LOOP
        FETCH possibilities INTO possibility, nbVotes;

        IF fin_cursor THEN
            LEAVE makejson; -- Exit loop if no more rows are found
        END IF;

        -- Calculate the percentage of votes for this possibility
        SET percentNbVotes = (nbVotes * 100) / totalNbVotes;

        -- Build a JSON-like object as a string manually
        SET result = CONCAT(
            result, 
            IF(result != '', ',', ''),  -- Add a comma if this is not the first element
            '{"POS_label_VC": "', possibility, '",',
            '"POS_nbVotes_NB": ', CAST(nbVotes AS CHAR), ',',
            '"POS_percentNbVotes_NB": ', CAST(percentNbVotes AS CHAR), '}'
        );

    END LOOP;

    CLOSE possibilities;

    -- Return the full JSON array-like string
    RETURN CONCAT('[', result, ']');
END //

DELIMITER ;
