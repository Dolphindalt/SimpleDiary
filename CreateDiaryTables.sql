# CreateUserTable.sql
# Dalton Caron
# Web Science
USE dcaron;

START TRANSACTION;

DROP TABLE IF EXISTS tbl_logon_attempts;
DROP TABLE IF EXISTS tbl_dairy_entries;
DROP TABLE IF EXISTS tbl_users;

CREATE TABLE tbl_users (
    username VARCHAR(64) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(64) NOT NULL,
    middle_initial VARCHAR(1) DEFAULT NULL,
    last_name VARCHAR(64) NOT NULL,
    last_successful_logon DATETIME DEFAULT NULL,
    last_unsuccessful_logon DATETIME DEFAULT NULL,
    num_logons INT DEFAULT 0,
    PRIMARY KEY(username)
);

# This is my user.
INSERT INTO tbl_users (username, password, first_name, middle_initial, last_name) 
VALUES ("Daltondalt", "fa21e34451aa2b5365329efdc95c27a72c9f3cac2a82dc99d5b00cd13d0f817cd4645676ca20ab9bf62f5ae1037002e116636381cddcbf77cf47cdd46796cb4b", "Dalton", "P", "Caron");

CREATE TABLE tbl_logon_attempts (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(64) NOT NULL,
    attempt_datetime DATETIME NOT NULL DEFAULT NOW(),
    status CHAR(7) NOT NULL DEFAULT "FAILURE", # Can be FAILURE or SUCCESS.
    ipaddress VARCHAR(45) NOT NULL, # Maybe we want to support IPV6 (39 max) and IPV4 addresses mapped to IPV6 (45 max)? 
    user_agent TEXT NOT NULL, # There is no easy max to this.
    FOREIGN KEY(username) REFERENCES tbl_users(username),
    PRIMARY KEY(id)
);

CREATE TABLE tbl_dairy_entries (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(64) NOT NULL,
    entry_datetime DATETIME NOT NULL DEFAULT NOW(),
    entry VARCHAR(255) NOT NULL,
    FOREIGN KEY(username) REFERENCES tbl_users(username),
    PRIMARY KEY(id)
);

COMMIT;