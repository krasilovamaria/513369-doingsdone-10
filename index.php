<?php
date_default_timezone_set('Europe/Moscow');

require_once('config/functions.php');
require_once('helpers.php');

/* подключение к БД и кодировка*/
$connect = mysqli_connect('localhost:3366', 'root', 'rootpass', 'doingsdone');
mysqli_set_charset($connect, 'utf8');

/* получаем список проектов и задач*/
$projects = getProjects($connect);
$tasks = getTasks($connect);

/* имя пользователя */
$user_name = 'Red';
$page_content = include_template('main.php', [
    'projects' => $projects,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);
$layout_content = include_template('layout.php', [
    'user' => $user_name,
    'content' => $page_content,
    'title' => 'Дела в порядке - Главная страница'
]);

print($layout_content);
