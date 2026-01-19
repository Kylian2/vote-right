CREATE TABLE supporting (
    SUP_id_NB INT AUTO_INCREMENT PRIMARY KEY,
    SUP_community_NB INT,
    SUP_label_VC VARCHAR(255),
    SUP_description_TX TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT FK_SUP_community FOREIGN KEY (SUP_community_NB) REFERENCES community(CMY_id_NB) ON DELETE CASCADE
);

CREATE TABLE transmission (
    TRA_member_NB INT,
    TRA_supporting_NB INT,
    TRA_community_NB INT,
    TRA_file_NB VARCHAR(255),
    TRA_valided_NB TINYINT DEFAULT 0 CHECK (TRA_valided_NB IN (0, 1, 2)), -- 0: en attente, 1: validé, 2: refusé
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT FK_TRA_member FOREIGN KEY (TRA_member_NB) REFERENCES user(USR_id_NB),
    CONSTRAINT FK_TRA_supporting_id FOREIGN KEY (TRA_supporting_NB) REFERENCES supporting(SUP_id_NB),
    CONSTRAINT FK_TRA_supporting_cmy FOREIGN KEY (TRA_community_NB) REFERENCES supporting(SUP_community_NB)
);