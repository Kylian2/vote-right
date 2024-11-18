DELIMITER //

-- Vérifier que la date du vote d'un membre ne change pas
CREATE TRIGGER updateVoteDetailDateChangeCheck
BEFORE UPDATE ON vote_detail
FOR EACH ROW
BEGIN
    IF NEW.DET_voted_on_DATE != OLD.DET_voted_on_DATE 
    THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Vous ne pouvez pas changer la date de vote d''un membre.';
    END IF;
END //

DELIMITER ;