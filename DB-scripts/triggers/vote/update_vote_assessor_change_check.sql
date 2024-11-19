DELIMITER //

-- Vérifier qu'un vote allant être validé ne le soit pas déjà
CREATE OR REPLACE TRIGGER update_vote_assessor_change_check
BEFORE UPDATE ON vote
FOR EACH ROW
BEGIN
    IF NEW.VOT_assessor_NB IS NOT NULL
    AND OLD.VOT_assessor_NB IS NOT NULL
    AND NEW.VOT_assessor_NB != OLD.VOT_assessor_NB
    THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Ce vote a déjà été validé. Vous ne pouvez pas le valider à nouveau.';
    END IF;
END //

DELIMITER ;