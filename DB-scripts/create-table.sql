CREATE TABLE motif (
    MOT_id_NB TINYINT AUTO_INCREMENT,
    MOT_libelle_VC VARCHAR(25),
    CONSTRAINT PK_motif PRIMARY KEY (MOT_id_NB)
);

CREATE TABLE role (
    ROL_id_NB TINYINT AUTO_INCREMENT,
    ROL_libelle_VC VARCHAR(25),
    CONSTRAINT PK_role PRIMARY KEY (ROL_id_NB)
);

CREATE TABLE liste (
    LIS_id_NB INT AUTO_INCREMENT,
    LIS_possibilite_VC VARCHAR(25),
    CONSTRAINT PK_liste PRIMARY KEY (LIS_id_NB)
);

CREATE TABLE suffrage (
    SUF_id_NB TINYINT AUTO_INCREMENT,
    SUF_libelle_VC VARCHAR(25),
    CONSTRAINT PK_suffrage PRIMARY KEY (SUF_id_NB)
);

CREATE TABLE utilisateur (
    UTI_id_NB INT AUTO_INCREMENT,
    UTI_email_VC VARCHAR(150),
    UTI_motdepasse_VC VARCHAR(255),
    UTI_nom_VC VARCHAR(50),
    UTI_prenom_VC VARCHAR(50),
    UTI_adresse_VC VARCHAR(200),
    UTI_codepostal_CH CHAR(6),
    UTI_naissance_DATE DATE,
    UTI_notiffrequence_CH CHAR(1) DEFAULT 'H',
    UTI_notifproposition_NB BOOLEAN DEFAULT 0,
    UTI_notifvote_NB BOOLEAN DEFAULT 0,
    UTI_notifreaction_NB BOOLEAN DEFAULT 0,
    CONSTRAINT PK_utilisateur PRIMARY KEY (UTI_id_NB)
);

CREATE TABLE groupe(
    GRO_id_NB INT AUTO_INCREMENT,
    GRO_nom_VC VARCHAR(150),
    GRO_image_VC VARCHAR(50),
    GRO_emoji_VC VARCHAR(5),
    GRO_description_VC TEXT,
    GRO_budget_NB FLOAT(12, 2),
    GRO_fraisfixes_NB FLOAT(12, 2),
    GRO_createur_NB INT,
    CONSTRAINT PK_groupe PRIMARY KEY (GRO_id_NB),
    CONSTRAINT FK_GRO_createur FOREIGN KEY (GRO_createur_NB) REFERENCES utilisateur(UTI_id_NB)
);

CREATE TABLE membre(
    MEM_utilisateur_NB INT,
    MEM_groupe_NB INT,
    MEM_role_NB TINYINT,
    CONSTRAINT PK_membre PRIMARY KEY (MEM_utilisateur_NB, MEM_groupe_NB),
    CONSTRAINT FK_MEM_utilisateur FOREIGN KEY (MEM_utilisateur_NB) REFERENCES utilisateur(UTI_id_NB),
    CONSTRAINT FK_MEM_groupe FOREIGN KEY (MEM_groupe_NB) REFERENCES groupe(GRO_id_NB),
    CONSTRAINT FK_MEM_role FOREIGN KEY (MEM_role_NB) REFERENCES role(ROL_id_NB)
);

CREATE TABLE invitation(
    INV_id_NB INT AUTO_INCREMENT,
    INV_code_VC VARCHAR(6),
    INV_emission_DATE DATE, 
    INV_acceptation_DATE DATE,
    INV_envoyeur_NB INT,
    INV_destinataire_NB INT,
    INV_groupe_NB INT, 
    CONSTRAINT PK_invitation PRIMARY KEY (INV_id_NB),
    CONSTRAINT FK_INV_envoyeur FOREIGN KEY (INV_envoyeur_NB) REFERENCES utilisateur(UTI_id_NB),
    CONSTRAINT FK_INV_destinataire FOREIGN KEY (INV_destinataire_NB) REFERENCES utilisateur(UTI_id_NB),
    CONSTRAINT FK_INV_groupe FOREIGN KEY (INV_groupe_NB) REFERENCES groupe(GRO_id_NB)
);

CREATE TABLE theme(
    THE_id_NB SMALLINT,
    THE_groupe_NB INT,
    THE_nom_VC VARCHAR(50),
    THE_budget_NB FLOAT(12, 2),
    CONSTRAINT PK_theme PRIMARY KEY (THE_id_NB, THE_groupe_NB),
    CONSTRAINT FK_THE_groupe FOREIGN KEY (THE_groupe_NB) REFERENCES groupe(GRO_id_NB)
);

CREATE TABLE proposition (
    PRO_id_NB INT AUTO_INCREMENT,
    PRO_titre_VC VARCHAR(150),
    PRO_description_TXT TEXT,
    PRO_creation_DATE DATE,
    PRO_dureediscussion_NB SMALLINT,
    PRO_nbdemande_NB INT,
    PRO_localisation_VC VARCHAR(255),
    PRO_budget_NB FLOAT(12, 2),
    PRO_statut_VC VARCHAR(6),
    PRO_initiateur_NB INT,
    PRO_suppresseur_NB INT,
    PRO_approuveur_NB INT,
    PRO_groupe_NB INT,
    PRO_theme_NB SMALLINT,
    CONSTRAINT PK_proposition PRIMARY KEY (PRO_id_NB),
    CONSTRAINT FK_PRO_initiateur FOREIGN KEY (PRO_initiateur_NB) REFERENCES utilisateur(UTI_id_NB),
    CONSTRAINT FK_PRO_suppresseur FOREIGN KEY (PRO_suppresseur_NB) REFERENCES utilisateur(UTI_id_NB),
    CONSTRAINT FK_PRO_approuveur FOREIGN KEY (PRO_approuveur_NB) REFERENCES utilisateur(UTI_id_NB),
    CONSTRAINT FK_PRO_groupe FOREIGN KEY (PRO_groupe_NB) REFERENCES groupe(GRO_id_NB),
    CONSTRAINT FK_PRO_theme FOREIGN KEY (PRO_theme_NB) REFERENCES theme(THE_id_NB)
);

CREATE TABLE commentaire(
    COM_id_NB BIGINT AUTO_INCREMENT,
    COM_message_VC VARCHAR(250), 
    COM_proposition_NB INT, 
    COM_envoyeur_NB INT, 
    COM_moderateur_NB INT,
    CONSTRAINT PK_commentaire PRIMARY KEY (COM_id_NB),
    CONSTRAINT FK_COM_proposition FOREIGN KEY (COM_proposition_NB) REFERENCES proposition(PRO_id_NB),
    CONSTRAINT FK_COM_envoyeur FOREIGN KEY (COM_envoyeur_NB) REFERENCES utilisateur(UTI_id_NB),
    CONSTRAINT FK_COM_moderateur FOREIGN KEY (COM_moderateur_NB) REFERENCES utilisateur(UTI_id_NB)
);

CREATE TABLE reaction_proposition(
    REP_utilisateur_NB INT,
    REP_proposition_NB INT,
    REP_reaction_VC VARCHAR(10),
    CONSTRAINT PK_reaction_proposition PRIMARY KEY (REP_utilisateur_NB, REP_proposition_NB),
    CONSTRAINT FK_REP_utilisateur FOREIGN KEY (REP_utilisateur_NB) REFERENCES utilisateur(UTI_id_NB),
    CONSTRAINT FK_REP_proposition FOREIGN KEY (REP_proposition_NB) REFERENCES proposition(PRO_id_NB)
);

CREATE TABLE vote(
    VOT_proposition_NB INT, 
    VOT_tour_NB TINYINT,
    VOT_valide_BOOL BOOLEAN,
    VOT_assesseur_NB INT, 
    VOT_liste_NB INT,
    VOT_type_NB TINYINT,  
    CONSTRAINT PK_vote PRIMARY KEY (VOT_proposition_NB, VOT_tour_NB),
    CONSTRAINT FK_VOT_proposition FOREIGN KEY (VOT_proposition_NB) REFERENCES proposition(PRO_id_NB),
    CONSTRAINT FK_VOT_assesseur FOREIGN KEY (VOT_assesseur_NB) REFERENCES utilisateur(UTI_id_NB),
    CONSTRAINT FK_VOT_liste FOREIGN KEY (VOT_liste_NB) REFERENCES liste(LIS_id_NB),
    CONSTRAINT FK_VOT_type FOREIGN KEY (VOT_type_NB) REFERENCES suffrage(SUF_id_NB)
);

CREATE TABLE detail_vote(
    DET_proposition_NB INT,
    DET_tour_NB TINYINT, 
    DET_utilisateur_NB INT, 
    DET_choix_VC VARCHAR(255),
    CONSTRAINT PK_detail_vote PRIMARY KEY (DET_proposition_NB, DET_tour_NB, DET_utilisateur_NB),
    CONSTRAINT FK_DET_vote FOREIGN KEY (DET_proposition_NB, DET_tour_NB) REFERENCES vote(VOT_proposition_NB, VOT_tour_NB),
    CONSTRAINT FK_DET_utilisateur FOREIGN KEY (DET_utilisateur_NB) REFERENCES utilisateur(UTI_id_NB)
);

CREATE TABLE reaction_commentaire(
    REC_commentaire_NB BIGINT, 
    REC_utilisateur_NB INT, 
    REC_reaction_VC VARCHAR(10),
    CONSTRAINT PK_reaction_commentaire PRIMARY KEY (REC_commentaire_NB, REC_utilisateur_NB),
    CONSTRAINT FK_REC_commentaire FOREIGN KEY (REC_commentaire_NB) REFERENCES commentaire(COM_id_NB),
    CONSTRAINT FK_REC_utilisateur FOREIGN KEY (REC_utilisateur_NB) REFERENCES utilisateur(UTI_id_NB)
);

CREATE TABLE signalement(
    SIG_utilisateur_NB INT, 
    SIG_commentaire_NB BIGINT, 
    SIG_motif_NB TINYINT, 
    CONSTRAINT PK_signalement PRIMARY KEY (SIG_utilisateur_NB, SIG_commentaire_NB, SIG_motif_NB),
    CONSTRAINT FK_SIG_utilisateur FOREIGN KEY (SIG_utilisateur_NB) REFERENCES utilisateur(UTI_id_NB),
    CONSTRAINT FK_SIG_commentaire FOREIGN KEY (SIG_commentaire_NB) REFERENCES commentaire(COM_id_NB),
    CONSTRAINT FK_SIG_motif FOREIGN KEY (SIG_motif_NB) REFERENCES motif(MOT_id_NB)
);