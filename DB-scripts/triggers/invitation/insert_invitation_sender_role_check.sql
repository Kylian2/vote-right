DELIMITER //

-- Vérifier que l'utilisateur invitant un internaute dans un groupe est administrateur de celui-ci
CREATE OR REPLACE TRIGGER insert_invitation_sender_role_check
BEFORE INSERT ON invitation
FOR EACH ROW
BEGIN
    IF NEW.INV_sender_NB NOT IN (
        SELECT MEM_user_NB
        FROM member
        INNER JOIN role ON ROL_id_NB = MEM_role_NB
        WHERE MEM_community_NB = NEW.INV_community_NB AND ROL_label_VC = 'Administrateur'
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Seuls les administrateurs peuvent inviter un membre.';
    END IF;
END //

DELIMITER ;