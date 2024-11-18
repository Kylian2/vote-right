DELIMITER //

-- Pour un commentaire et un utilisateur donné :
--      - met dans COM_supressor_NB l’id de l’utilisateur qui a appelé la fonction 
--      - met “Résolu” dans RPT_status_VC de tous les signalements non résolus de ce commentaire
CREATE PROCEDURE deleteCommentAndResolveReports (IN idComment BIGINT, IN idUser INT)
BEGIN
    UPDATE comment
    SET COM_supressor_NB = idUser
    WHERE COM_id_NB = idComment;

    UPDATE report
    SET RPT_status_VC = 'Résolu'
    WHERE RPT_comment_NB = idComment AND RPT_status_VC != 'Résolu';
END//

DELIMITER ;