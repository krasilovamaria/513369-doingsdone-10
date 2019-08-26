/*Добавляет в таблицу user пользователей*/
USE doingsdone;
INSERT INTO user (email, name, password)
VALUES ('angrybirds2@gmail.com', 'Red', 'angryRed'),
       ('angrybirds@gmail.com', 'PowerfulEagle', 'angryEagle'),
       ('angrybird2@gmail.com', 'Alex', 'angryAlex');

/*Добавляет в таблицу project название проектов и автора*/
INSERT INTO project (name, author_id)
VALUES ('Входящие', 1), ('Учеба', 2), ('Работа', 3), ('Домашние дела', 1), ('Авто', 2);

/*Добавляет в таблицу task название статус выполнения,
наимeнование задачи, дату выполнения, автора задачи и id проекта */
INSERT INTO task (status, name, deadline, author_id, project_id)
VALUES (0, 'Собеседование в IT компании','2019-08-24 00:00:00', 3, 1),
       (0, 'Выполнить тестовое задание','2020-08-20 19:00:00', 3, 3),
       (1, 'Сделать задание первого раздела','2021-12-21 18:00:00', 2, 2),
       (0, 'Встреча с другом','2018-12-22 18:00:00', 1, 1),
       (0, 'Купить корм для кота', NULL, 1, 4),
       (0, 'Заказать пиццу', NULL, 1, 4);

/*получает список из всех проектов для одного пользователя*/
SELECT name FROM project WHERE author_id = 1;

/*получает список из всех задач для одного проекта*/
SELECT name FROM task WHERE project_id = 1;

/*помечает задачу как выполненную*/
UPDATE task SET status = 1 WHERE id = 3;

/*обновляет название задачи по её идентификатору*/
UPDATE task SET name = 'Купить огромную пачку корма для кота' WHERE id = 5;
