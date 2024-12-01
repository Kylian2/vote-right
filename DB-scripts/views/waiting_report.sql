CREATE OR REPLACE VIEW waiting_report AS
SELECT RES_label_VC, COM_message_VC, USR_firstname_VC, USR_lastname_VC, RPT_creation_DATE , COM_proposal_NB, PRO_community_NB
FROM report
INNER JOIN comment ON COM_id_NB = RPT_comment_NB
INNER JOIN reason ON RPT_reason_NB = RES_id_NB
INNER JOIN user ON COM_sender_NB = USR_id_NB
INNER JOIN proposal ON COM_proposal_NB = PRO_id_NB
WHERE RPT_status_VC = 'En attente' AND COM_suppressor_NB IS NULL;