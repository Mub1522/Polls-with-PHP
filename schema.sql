-- Schema
CREATE SCHEMA `polls_php` DEFAULT CHARACTER SET utf8mb4 ;

-- Tables
CREATE TABLE `polls`(
    id INT NOT NULL AUTO_INCREMENT,
    ref VARCHAR(100) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE `options`(
    id INT NOT NULL AUTO_INCREMENT,
    poll_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    votes int NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY (poll_id) REFERENCES polls(id)
);

-- Inserts

