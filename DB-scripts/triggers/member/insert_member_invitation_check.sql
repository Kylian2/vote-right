DELIMITER //

-- Vérifier que le nouveau membre d'un groupe ait reçu une invitation pour rejoindre celui-ci
CREATE OR REPLACE TRIGGER insert_member_invitation_check
BEFORE INSERT ON member
FOR EACH ROW
BEGIN
    IF NEW.MEM_role_NB != (SELECT ROL_id_NB
                           FROM role
                           WHERE ROL_label_VC = 'Administrateur')
    AND NEW.MEM_user_NB NOT IN (SELECT INV_recipient_NB
                                FROM invitation
                                WHERE INV_community_NB = NEW.MEM_community_NB)
    THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Seuls les membres aillant reçus une invitation peuvent rejoindre le groupe.';
    END IF;
END //

DELIMITER ;