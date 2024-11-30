DELIMITER //

-- Vérifier que la date du vote d'un membre se situe entre la date de début et la date de fin de ce vote
CREATE OR REPLACE TRIGGER insert_vote_detail_date
BEFORE INSERT ON vote_detail
FOR EACH ROW
BEGIN
    DECLARE vote_start_date DATE;
    DECLARE vote_end_date DATE;

    SELECT VOT_start_DATE, VOT_end_DATE INTO vote_start_date, vote_end_date
    FROM vote
    WHERE VOT_proposal_NB = NEW.DET_proposal_NB AND VOT_round_NB = NEW.DET_round_NB;

    IF NEW.DET_voted_on_DATE NOT BETWEEN vote_start_date AND vote_end_date
    THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Le vote est impossible en dehors de la période entre le début et la fin du vote.';
    END IF;
END //

DELIMITER ;