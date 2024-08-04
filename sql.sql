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
--create Contact data
CREATE TABLE contactData (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phonenumber VARCHAR(20) NOT NULL, 
    shootdate DATETIME NOT NULL, 
    location VARCHAR(255) NOT NULL,
    service VARCHAR(100) NOT NULL,
    session VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    UNIQUE (email)
);
--create album
CREATE TABLE album(
	id INT PRIMARY KEY AUTO_INCREMENT,
    album_name VARCHAR(100) NOT NULL,
    album_link VARCHAR(100) NOT NULL,
    album_img VARCHAR(100) NOT NULL,
    album_category VARCHAR(100) NOT NULL
)