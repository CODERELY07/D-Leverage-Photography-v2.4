--create database
--D'Leverage

--create table
CREATE TABLE image(
    id int(11) NOT NULL AUTO_INCREMENT,
    filename varchar(100) NOT NULL,
    category varchar(100),
    PRIMARY KEY (id)
);

--Create table for login admin
CREATE TABLE admin (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
