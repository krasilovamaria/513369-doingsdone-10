<?php
require_once('config/init.php');

/* если пользователь есть в сессии, делаем редирект на index.php*/
if (!empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

/* получает список проектов*/
/* если параметра нет, то NULL(показывает задачи как есть)*/
$project_id = $_GET['project_id'] ?? null;
$projects = getProjects($connect);

$project = [
    'email' => $_POST['email'] ?? null,
    'password' => $_POST['password'] ?? null,
];
$errors = [];

/* подключение контента*/
$page_content = include_template('add_project.php', [
    'errors'  => $errors
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'projects' => $projects,
    'user' => $user_name,
    'title' => 'Дела в порядке - Добавление проекта'
]);

print $layout_content;
