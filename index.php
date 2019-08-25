<?php
date_default_timezone_set('Europe/Moscow');

require_once('config/functions.php');
require_once('helpers.php');

/* подключение к БД и кодировка*/
$connect = mysqli_connect('localhost:3366', 'root', 'rootpass', 'doingsdone');
mysqli_set_charset($connect, 'utf8');

/* получает список проектов и задач*/
$projects = getProjects($connect);
$tasks = getTasks($connect);

/* ссылки на задачи*/
if(isset($_GET['project_id'])) {
    $project_id = intval($_GET['project_id']);
    $sql = 'SELECT id, name, status, file, deadline, project_id FROM task WHERE projct_id = ' . $project_id;
    $result = mysqli_query($connect, $sql);
    if($result) {
        $row = mysqli_fetch_assoc($result);
    }
    $result = mysqli_query($connect, $sql);
    header("Location: /index.php");
}

/* имя пользователя*/
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
