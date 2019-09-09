CREATE DATABASE doingsdone
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
USE doingsdone;

CREATE TABLE user (
  id       int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  date     datetime NOT NULL DEFAULT NOW(),
  email    char(128) NOT NULL UNIQUE,
  name     char(128) NOT NULL,
  password varchar(128) NOT NULL
);

CREATE TABLE project (
  id        int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name      char(128) NOT NULL,
  author_id int(11) NOT NULL,
  FOREIGN KEY (author_id) REFERENCES user(id)
);

CREATE TABLE task (
  id         int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  date       datetime NOT NULL DEFAULT NOW(),
  status     tinyint(1) DEFAULT 0,
  name       char(128) NOT NULL,
  file       char(128),
  deadline   datetime,
  author_id  int(11) NOT NULL,
  project_id int(11) NOT NULL,
  FOREIGN KEY (author_id) REFERENCES user(id),
  FOREIGN KEY (project_id) REFERENCES project(id)
);

CREATE INDEX author_id ON project(author_id);
CREATE INDEX project_id ON task(project_id);
CREATE FULLTEXT INDEX search ON task(name);
