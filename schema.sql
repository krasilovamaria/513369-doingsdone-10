CREATE DATABASE doingsdone CHARACTER SET utf8 COLLATE utf8_general_ci;
USE doingsdone;

CREATE TABLE user (
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  date datetime NOT NULL COMMENT 'DEAFAULT NOW()',
  email char(128) NOT NULL UNIQUE,
  name char(128) NOT NULL,
  password varchar(128) NOT NULL
);

CREATE TABLE project (
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name char(128) NOT NULL,
  author_id int(11) NOT NULL,
  FOREIGN KEY (author_id) REFERENCES user(id)
);

CREATE TABLE task (
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  date datetime NOT NULL COMMENT 'DEFAULT NOW()',
  status tinyint(1) DEFAULT NULL,
  name char(128) NOT NULL,
  file char(128) DEFAULT NULL,
  deadline datetime DEFAULT NULL,
  author_id INTEGER NOT NULL,
  project_id INTEGER NOT NULL,
  FOREIGN KEY (author_id) REFERENCES user(id),
  FOREIGN KEY (project_id) REFERENCES project(id)
);
