DROP DATABASE IF EXISTS contacts;
CREATE DATABASE IF NOT EXISTS contacts;
USE contacts;
CREATE TABLE IF NOT EXISTS client (
    cd_client INT UNSIGNED AUTO_INCREMENT,
    nm_email VARCHAR(255) UNIQUE NOT NULL,
    nm_password VARCHAR(60) NOT NULL,
    nm_client VARCHAR(255) NOT NULL,
    PRIMARY KEY (cd_client)
);
CREATE TABLE IF NOT EXISTS contact (
    cd_contact INT UNSIGNED,
    cd_client INT UNSIGNED,
    nm_contact VARCHAR(255),
    nm_email VARCHAR(255),
    nm_phone VARCHAR(20),
    nm_note TEXT,
    PRIMARY KEY (cd_client, cd_contact),
    FOREIGN KEY (cd_client) REFERENCES client(cd_client)
);

INSERT INTO client (nm_email, nm_password, nm_client)
VALUES ('example@example.com', 'hashed_password_here', 'John Doe');

INSERT INTO contact (cd_client, cd_contact, nm_contact, nm_email, nm_phone, nm_note) VALUES
(1, 1, 'Jane Smith', 'janesmith@example.com', '555-1234', 'Contact for John Doe.'),
(1, 2, 'Bob Johnson', 'bobjohnson@example.com', '555-5678', 'Contact for John Doe.'),
(1, 3, 'Alice Davis', 'alicedavis@example.com', '555-9012', 'Contact for John Doe.'),
(1, 4, 'Emily Thompson', 'emilythompson@example.com', '555-3456', 'Contact for John Doe'),
(1, 5, 'Michael Wilson', 'michaelwilson@example.com', '555-7890', 'Contact for John Doe'),
(1, 6, 'Sarah Brown', 'sarahbrown@example.com', '555-4321', 'Contact for John Doe');