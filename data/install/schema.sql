CREATE TABLE site_log (
    id INT AUTO_INCREMENT NOT NULL,
    user_ip VARCHAR(45) DEFAULT NULL,
    reference LONGTEXT DEFAULT NULL,
    resources_id INT DEFAULT NULL,
    resources_type VARCHAR(45) DEFAULT NULL,
    page_slug LONGTEXT DEFAULT NULL,
    site_id INT DEFAULT NULL,
    context LONGTEXT DEFAULT NULL,
    created DATETIME NOT NULL,
    INDEX IDX_B6466004F6BD2646 (site_id),
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
ALTER TABLE site_log ADD CONSTRAINT FK_B6466004F6BD2646 FOREIGN KEY (site_id) REFERENCES site (id) ON DELETE CASCADE;
