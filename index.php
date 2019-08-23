<?php
date_default_timezone_set('Europe/Moscow');

require_once('config/functions.php');
require_once('helpers.php');

/* подключение к БД и кодировка*/
$connect = mysqli_connect('localhost:3366', 'root', 'rootpass', 'doingsdone');
mysqli_set_charset($connect, 'utf8');
/* массив проектов и SQL-запрос для получения списка проектов у текущего пользователя */
$projects = [];
$sql_projects = 'SELECT id, name FROM project';
$result_projects = mysqli_query($connect, $sql_projects);
if($result_projects) {
    $projects = mysqli_fetch_all($result_projects, MYSQLI_ASSOC);
}
/* массив задач и SQL-запрос для получения списка из всех задач у текущего пользователя */
$tasks = [];
$sql_tasks = 'SELECT id, name, status, file, deadline, project_id FROM task';
$result_tasks = mysqli_query($connect, $sql_tasks);
if($result_tasks) {
    $tasks = mysqli_fetch_all($result_tasks, MYSQLI_ASSOC);
}

/* Имя пользователя */
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
