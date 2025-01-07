-- Supprime proprement un utilisateur passé en paramètre

DELIMITER //

CREATE OR REPLACE PROCEDURE delete_user(user_id INT) 
BEGIN 

DELETE FROM member WHERE MEM_user_NB = user_id;

UPDATE user SET 
    USR_firstname_VC = 'Supprimé',
    USR_lastname_VC = 'Supprimé',
    USR_email_VC = CONCAT('Supprimé@', user_id),
    USR_password_VC = 'Supprimé',
    USR_address_VC = 'Supprimé',
    USR_zipcode_CH = '00000',
    USR_birthdate_DATE = CURRENT_TIMESTAMP,
    USR_notify_proposal_BOOL = 0,
    USR_notify_reaction_BOOL = 0,
    USR_notify_vote_BOOL = 0,
    USR_notification_frequency_CH = 'S',
    USR_newsletter_BOOL = 0 
WHERE USR_id_NB = user_id;

DELETE FROM report WHERE RPT_user_NB = user_id;
DELETE FROM comment WHERE COM_sender_NB = user_id;
DELETE FROM comment_reaction WHERE REC_user_NB = user_id;
DELETE FROM proposal_reaction WHERE REP_user_NB = user_id;
DELETE FROM invitation WHERE INV_sender_NB = user_id OR INV_recipient_NB = user_id;

END //

DELIMITER ;