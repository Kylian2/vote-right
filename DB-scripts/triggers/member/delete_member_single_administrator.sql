DELIMITER //

-- Vérifier qu'un administrateur ne se rétrogade pas s'il est le seul administrateur du groupe
CREATE OR REPLACE TRIGGER delete_member_single_administrator
BEFORE DELETE ON member
FOR EACH ROW
BEGIN
    IF OLD.MEM_role_NB = (SELECT ROL_id_NB
                          FROM role
                          WHERE ROL_label_VC = 'Administrateur')
    AND (SELECT COUNT(*)
         FROM member
         INNER JOIN role ON MEM_role_NB = ROL_id_NB
         WHERE ROL_label_VC = 'Administrateur' AND MEM_community_NB = OLD.MEM_community_NB AND MEM_user_NB != OLD.MEM_user_NB) = 0
    THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Veuillez nommer au moins un administrateur avant de quitter le groupe.';
    END IF;
END //

DELIMITER ;