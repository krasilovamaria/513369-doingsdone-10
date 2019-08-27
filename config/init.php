<?php
date_default_timezone_set('Europe/Moscow');

require_once('config/functions.php');
require_once('helpers.php');

/* подключение к БД и кодировка*/
$connect = mysqli_connect('localhost:3366', 'root', 'rootpass', 'doingsdone');
mysqli_set_charset($connect, 'utf8');

/* получает список проектов*/
/* если параметра нет, то NULL(показывает задачи как есть)*/
$project_id = $_GET['project_id'] ?? null;
$projects = getProjects($connect);

/* получает список задач*/
$tasks = getTasks($connect, $project_id);

/* имя пользователя*/
$user_name = 'Red';
