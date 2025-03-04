DELIMITER //

-- Vérifier que l'utilisateur supprimant un commentaire est modérateur ou administrateur du groupe
CREATE OR REPLACE TRIGGER update_comment_suppressor_role
BEFORE UPDATE ON comment
FOR EACH ROW
BEGIN
    IF NEW.COM_suppressor_NB IS NOT NULL
    AND OLD.COM_suppressor_NB IS NULL
    AND NEW.COM_suppressor_NB NOT IN (
        SELECT MEM_user_NB
        FROM proposal
        INNER JOIN member ON MEM_community_NB = PRO_community_NB
        INNER JOIN role ON ROL_id_NB = MEM_role_NB
        WHERE PRO_id_NB = NEW.COM_proposal_NB 
        AND ROL_label_VC IN ('Modérateur', 'Administrateur'))
    THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Seuls les modérateurs ou administrateurs peuvent supprimer un commentaire.';
    END IF;
END //

DELIMITER ;