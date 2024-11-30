DELIMITER //

-- Vérifier qu'une proposition allant être supprimée ne le soit pas déjà
CREATE OR REPLACE TRIGGER update_proposal_deleter_change
BEFORE UPDATE ON proposal
FOR EACH ROW
BEGIN
    IF NEW.PRO_deleter_NB IS NOT NULL
    AND OLD.PRO_deleter_NB IS NOT NULL
    AND NEW.PRO_deleter_NB != OLD.PRO_deleter_NB
    THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Cette proposition a déjà été supprimée. Vous ne pouvez pas la supprimer à nouveau.';
    END IF;
END //

DELIMITER ;