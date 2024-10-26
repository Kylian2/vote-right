CREATE TABLE reaction (
    REA_id_NB TINYINT AUTO_INCREMENT,
    REA_label_VC VARCHAR(25),
    CONSTRAINT PK_reaction PRIMARY KEY (REA_id_NB)
);

CREATE TABLE reason (
    RES_id_NB TINYINT AUTO_INCREMENT,
    RES_label_VC VARCHAR(100),
    CONSTRAINT PK_reason PRIMARY KEY (RES_id_NB)
);

CREATE TABLE role (
    ROL_id_NB TINYINT AUTO_INCREMENT,
    ROL_label_VC VARCHAR(25),
    CONSTRAINT PK_role PRIMARY KEY (ROL_id_NB)
);

CREATE TABLE voting_system (
    SYS_id_NB TINYINT AUTO_INCREMENT,
    SYS_label_VC VARCHAR(100),
    CONSTRAINT PK_voting_system PRIMARY KEY (SYS_id_NB)
);

CREATE TABLE user (
    USR_id_NB INT AUTO_INCREMENT,
    USR_email_VC VARCHAR(150) UNIQUE,
    USR_password_VC VARCHAR(255),
    USR_lastname_VC VARCHAR(50),
    USR_firstname_VC VARCHAR(50),
    USR_address_VC VARCHAR(200),
    USR_zipcode_CH CHAR(5),
    USR_birthdate_DATE DATE,
    USR_notification_frequency_CH CHAR(1) DEFAULT 'H',
    USR_notify_proposal_NB BOOLEAN DEFAULT 0,
    USR_notify_vote_NB BOOLEAN DEFAULT 0,
    USR_notify_reaction_NB BOOLEAN DEFAULT 0,
    CONSTRAINT PK_user PRIMARY KEY (USR_id_NB)
);

CREATE TABLE community (
    CMY_id_NB INT AUTO_INCREMENT,
    CMY_name_VC VARCHAR(150),
    CMY_image_VC VARCHAR(50),
    CMY_emoji_VC VARCHAR(5),
    CMY_description_VC TEXT,
    CMY_budget_NB FLOAT(12, 2),
    CMY_fixed_fees_NB FLOAT(12, 2),
    CMY_creator_NB INT,
    CONSTRAINT PK_community PRIMARY KEY (CMY_id_NB),
    CONSTRAINT FK_CMY_creator FOREIGN KEY (CMY_creator_NB) REFERENCES user(USR_id_NB)
);

CREATE TABLE member (
    MEM_user_NB INT,
    MEM_community_NB INT,
    MEM_role_NB TINYINT,
    CONSTRAINT PK_member PRIMARY KEY (MEM_user_NB, MEM_community_NB),
    CONSTRAINT FK_MEM_user FOREIGN KEY (MEM_user_NB) REFERENCES user(USR_id_NB),
    CONSTRAINT FK_MEM_community FOREIGN KEY (MEM_community_NB) REFERENCES community(CMY_id_NB),
    CONSTRAINT FK_MEM_role FOREIGN KEY (MEM_role_NB) REFERENCES role(ROL_id_NB)
);

CREATE TABLE invitation (
    INV_id_NB INT AUTO_INCREMENT,
    INV_code_VC VARCHAR(6),
    INV_issue_DATE DATE, 
    INV_acceptance_DATE DATE,
    INV_sender_NB INT,
    INV_recipient_NB INT,
    INV_community_NB INT, 
    CONSTRAINT PK_invitation PRIMARY KEY (INV_id_NB),
    CONSTRAINT FK_INV_sender FOREIGN KEY (INV_sender_NB) REFERENCES user(USR_id_NB),
    CONSTRAINT FK_INV_recipient FOREIGN KEY (INV_recipient_NB) REFERENCES user(USR_id_NB),
    CONSTRAINT FK_INV_community FOREIGN KEY (INV_community_NB) REFERENCES community(CMY_id_NB)
);

CREATE TABLE theme (
    THM_id_NB SMALLINT,
    THM_community_NB INT,
    THM_name_VC VARCHAR(50),
    THM_budget_NB FLOAT(12, 2),
    CONSTRAINT PK_theme PRIMARY KEY (THM_id_NB, THM_community_NB),
    CONSTRAINT FK_THM_community FOREIGN KEY (THM_community_NB) REFERENCES community(CMY_id_NB)
);

CREATE TABLE proposal (
    PRO_id_NB INT AUTO_INCREMENT,
    PRO_title_VC VARCHAR(150),
    PRO_description_TXT TEXT,
    PRO_creation_DATE DATE,
    PRO_discussion_duration_NB SMALLINT,
    PRO_request_count_NB INT,
    PRO_location_VC VARCHAR(255),
    PRO_budget_NB FLOAT(12, 2),
    PRO_status_VC VARCHAR(6),
    PRO_initiator_NB INT,
    PRO_deleter_NB INT,
    PRO_approver_NB INT,
    PRO_community_NB INT,
    PRO_theme_NB SMALLINT,
    CONSTRAINT PK_proposal PRIMARY KEY (PRO_id_NB),
    CONSTRAINT FK_PRO_initiator FOREIGN KEY (PRO_initiator_NB) REFERENCES user(USR_id_NB),
    CONSTRAINT FK_PRO_deleter FOREIGN KEY (PRO_deleter_NB) REFERENCES user(USR_id_NB),
    CONSTRAINT FK_PRO_approver FOREIGN KEY (PRO_approver_NB) REFERENCES user(USR_id_NB),
    CONSTRAINT FK_PRO_community FOREIGN KEY (PRO_community_NB) REFERENCES community(CMY_id_NB),
    CONSTRAINT FK_PRO_theme FOREIGN KEY (PRO_theme_NB) REFERENCES theme(THM_id_NB)
);

CREATE TABLE comment (
    COM_id_NB BIGINT AUTO_INCREMENT,
    COM_message_VC VARCHAR(250), 
    COM_proposal_NB INT, 
    COM_sender_NB INT, 
    COM_moderator_NB INT,
    CONSTRAINT PK_comment PRIMARY KEY (COM_id_NB),
    CONSTRAINT FK_COM_proposal FOREIGN KEY (COM_proposal_NB) REFERENCES proposal(PRO_id_NB),
    CONSTRAINT FK_COM_sender FOREIGN KEY (COM_sender_NB) REFERENCES user(USR_id_NB),
    CONSTRAINT FK_COM_moderator FOREIGN KEY (COM_moderator_NB) REFERENCES user(USR_id_NB)
);

CREATE TABLE proposal_reaction (
    REP_user_NB INT,
    REP_proposal_NB INT,
    REP_reaction_NB TINYINT,
    CONSTRAINT PK_proposal_reaction PRIMARY KEY (REP_user_NB, REP_proposal_NB),
    CONSTRAINT FK_REP_user FOREIGN KEY (REP_user_NB) REFERENCES user(USR_id_NB),
    CONSTRAINT FK_REP_proposal FOREIGN KEY (REP_proposal_NB) REFERENCES proposal(PRO_id_NB),
    CONSTRAINT FK_REP_reaction FOREIGN KEY (REP_reaction_NB) REFERENCES reaction(REA_id_NB)
);

CREATE TABLE vote (
    VOT_proposal_NB INT, 
    VOT_round_NB TINYINT,
    VOT_valid_BOOL BOOLEAN,
    VOT_assessor_NB INT,  
    VOT_type_NB TINYINT,  
    CONSTRAINT PK_vote PRIMARY KEY (VOT_proposal_NB, VOT_round_NB),
    CONSTRAINT FK_VOT_proposal FOREIGN KEY (VOT_proposal_NB) REFERENCES proposal(PRO_id_NB),
    CONSTRAINT FK_VOT_assessor FOREIGN KEY (VOT_assessor_NB) REFERENCES user(USR_id_NB),
    CONSTRAINT FK_VOT_type FOREIGN KEY (VOT_type_NB) REFERENCES voting_system(SYS_id_NB)
);

CREATE TABLE possibility (
    POS_id_NB INT AUTO_INCREMENT,
    POS_label_VC VARCHAR(100),
    POS_proposal_NB INT,
    POS_round_NB TINYINT,
    CONSTRAINT PK_possibility PRIMARY KEY (POS_id_NB),
    CONSTRAINT FK_POS_proposal FOREIGN KEY (POS_proposal_NB) REFERENCES proposal(PRO_id_NB),
    CONSTRAINT FK_POS_round FOREIGN KEY (POS_proposal_NB, POS_round_NB) REFERENCES vote(VOT_proposal_NB, VOT_round_NB)
);

CREATE TABLE vote_detail (
    DET_proposal_NB INT,
    DET_round_NB TINYINT, 
    DET_user_NB INT, 
    DET_choice_NB INT,
    CONSTRAINT PK_vote_detail PRIMARY KEY (DET_proposal_NB, DET_round_NB, DET_user_NB),
    CONSTRAINT FK_DET_vote FOREIGN KEY (DET_proposal_NB, DET_round_NB) REFERENCES vote(VOT_proposal_NB, VOT_round_NB),
    CONSTRAINT FK_DET_user FOREIGN KEY (DET_user_NB) REFERENCES user(USR_id_NB),
    CONSTRAINT FK_DET_choice FOREIGN KEY (DET_choice_NB) REFERENCES possibility(POS_id_NB)
);

CREATE TABLE comment_reaction (
    REC_comment_NB BIGINT, 
    REC_user_NB INT, 
    REC_reaction_NB TINYINT,
    CONSTRAINT PK_comment_reaction PRIMARY KEY (REC_comment_NB, REC_user_NB),
    CONSTRAINT FK_REC_comment FOREIGN KEY (REC_comment_NB) REFERENCES comment(COM_id_NB),
    CONSTRAINT FK_REC_user FOREIGN KEY (REC_user_NB) REFERENCES user(USR_id_NB),
    CONSTRAINT FK_REC_reaction FOREIGN KEY (REC_reaction_NB) REFERENCES reaction(REA_id_NB)
);

CREATE TABLE report (
    RPT_user_NB INT, 
    RPT_comment_NB BIGINT, 
    RPT_reason_NB TINYINT, 
    CONSTRAINT PK_report PRIMARY KEY (RPT_user_NB, RPT_comment_NB),
    CONSTRAINT FK_RPT_user FOREIGN KEY (RPT_user_NB) REFERENCES user(USR_id_NB),
    CONSTRAINT FK_RPT_comment FOREIGN KEY (RPT_comment_NB) REFERENCES comment(COM_id_NB),
    CONSTRAINT FK_RPT_reason FOREIGN KEY (RPT_reason_NB) REFERENCES reason(RES_id_NB)
);
