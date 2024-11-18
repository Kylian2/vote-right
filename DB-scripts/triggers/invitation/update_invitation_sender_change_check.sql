DELIMITER //

-- Vérifier que l'expéditeur de l'invitation ne change pas
CREATE TRIGGER updateInvitationSenderChangeCheck
BEFORE UPDATE ON invitation
FOR EACH ROW
BEGIN
    IF NEW.INV_sender_NB != OLD.INV_sender_NB
    THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Vous ne pouvez pas changer l''expéditeur de l''invitation.';
    END IF;
END //

DELIMITER ;