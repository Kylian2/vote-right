DELIMITER //

-- Vérifier qu'un commentaire allant être supprimé ne le soit pas déjà
CREATE OR REPLACE TRIGGER update_comment_suppressor_change
BEFORE UPDATE ON comment
FOR EACH ROW
BEGIN
    IF NEW.COM_suppressor_NB IS NOT NULL
    AND OLD.COM_suppressor_NB IS NOT NULL
    AND NEW.COM_suppressor_NB != OLD.COM_suppressor_NB
    THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Ce commentaire a déjà été supprimé. Vous ne pouvez pas le supprimer à nouveau.';
    END IF;
END //

DELIMITER ;