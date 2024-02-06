CREATE DATABASE soc_sit COLLATE utf8mb4_bin;

CREATE TABLE login (
	email varchar(100) PRIMARY KEY,
	heslo text
);

CREATE TABLE login (
	id INT UNSIGNED AUTO_INCREMENT,
	jmeno varchar(100),
	pohlavi text,
	vek TINYINT UNSIGNED,
	email varchar(100),
	heslo text,
	PRIMARY KEY (id, email)
);

CREATE TABLE obsah (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	jmeno varchar(100),
	obsah text,
	datum DATETIME
);

CREATE TABLE komentare (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	jmeno varchar(100),
	obsah text,
	datum DATETIME,
	id2 INT
);

CREATE TABLE fotky (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	jmeno varchar(100),
	cesta_fotky text,
	id2 INT
);

