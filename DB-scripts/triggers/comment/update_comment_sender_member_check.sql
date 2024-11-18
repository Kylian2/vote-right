DELIMITER //

-- Vérifier que l'expéditeur du commentaire soit toujours le même utilisateur
CREATE TRIGGER updateCommentSenderChangeCheck
BEFORE UPDATE ON comment
FOR EACH ROW
BEGIN
    IF NEW.COM_sender_NB != OLD.COM_sender_NB
    THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Vous ne pouvez pas changer l''expéditeur du commentaire.';
    END IF;
END //

DELIMITER ;