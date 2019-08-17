CREATE DATABASE doingsdone

CREATE TABLE user
(
  id        INTEGER NOT NULL AUTO_INCREMENT,
  date      DATETIME NOT NULL,
  email     CHAR(50) NOT NULL,
  name      CHAR(50) NOT NULL,
  password  VARCHAR(50) NOT NULL,
)

CREATE TABLE project
(
  id        INTEGER NOT NULL AUTO_INCREMENT,
  name      CHAR(50) NOT NULL,
  author_id INTEGER NOT NULL FOREIGN KEY REFERENCES user(id),
)

CREATE TABLE task
(
  id          INTEGER NOT NULL AUTO_INCREMENT,
  date        DATETIME,
  status      INTEGER,
  name        CHAR(50) NOT NULL,
  file        INTEGER,
  validity    DATETIME,
  author_id   INTEGER NOT NULL FOREIGN KEY REFERENCES user(id),
  project_id  INTEGER NOT NULL FOREIGN KEY REFERENCES project(id),
)
