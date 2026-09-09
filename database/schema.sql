CREATE TABLE image(
    id int(11) NOT NULL AUTO_INCREMENT,
    filename varchar(100) NOT NULL,
    category varchar(100),
    PRIMARY KEY (id)
);


CREATE TABLE admin (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

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

ALTER TABLE contactData
ADD COLUMN status VARCHAR(10) NOT NULL DEFAULT 'unread';

CREATE TABLE album(
	id INT PRIMARY KEY AUTO_INCREMENT,
    album_name VARCHAR(100) NOT NULL,
    album_link VARCHAR(100) NOT NULL,
    album_img VARCHAR(100) NOT NULL,
    album_category VARCHAR(100) NOT NULL
);

CREATE TABLE album_img (
    id INT PRIMARY KEY AUTO_INCREMENT,
    album_id INT NOT NULL,
    img VARCHAR(100) NOT NULL,
    FOREIGN KEY (album_id) REFERENCES album(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
)