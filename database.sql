DROP DATABASE IF EXISTS contacts;
CREATE DATABASE IF NOT EXISTS contacts;
USE contacts;

CREATE TABLE IF NOT EXISTS client (
    cd_client INT UNSIGNED AUTO_INCREMENT,
    nm_email VARCHAR(255) UNIQUE NOT NULL,
    nm_password VARCHAR(60) NOT NULL,
    nm_client VARCHAR(100) NOT NULL,
    PRIMARY KEY (cd_client)
);

CREATE TABLE IF NOT EXISTS contact (
    cd_contact INT UNSIGNED,
    cd_client INT UNSIGNED,
    nm_contact VARCHAR(50),
    nm_email VARCHAR(255),
    nm_phone VARCHAR(20),
    nm_note TEXT,
    PRIMARY KEY (cd_client, cd_contact),
    FOREIGN KEY (cd_client) REFERENCES client(cd_client) ON DELETE CASCADE
);

DELIMITER $$
CREATE PROCEDURE IF NOT EXISTS insert_contact(
    IN cdClient INT, 
    IN nmContact VARCHAR(50), 
    IN nmEmail VARCHAR(255), 
    IN nmPhone VARCHAR(20), 
    IN nmNote TEXT
)
BEGIN
    DECLARE cdContact INT;
    SELECT COALESCE(MAX(cd_contact), 0) + 1 INTO cdContact
    FROM contact
    WHERE cd_client = cdClient;
    
    INSERT INTO contact (cd_client, cd_contact, nm_contact, nm_email, nm_phone, nm_note)
    VALUES (cdClient, cdContact, nmContact, nmEmail, nmPhone, nmNote);

    SELECT cdContact AS cd_contact;
END $$
DELIMITER ;;