<?php
require_once('config/init.php');

/* если пользователь есть в сессии, делаем редирект на index.php*/
if (empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

/* получает список проектов*/
/* если параметра нет, то NULL(показывает задачи как есть)*/
$project_id = $_GET['project_id'] ?? null;
$projects = getProjects($connect);

$project = [
    'name' => $_POST['name'] ?? null
];
$errors = [];
/* валидация формы*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_ids = array_column($projects, 'id');
    $required = ['name'];

    /* проверяет $project и $name*/
    $rules = [
        'name' => function () {
            return validateLength('name', 1, 100);
        }
    ];

    /* проверяет, что обязательные поля заполнены*/
    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    /* отфильтровывает массив от пустых значений, чтобы оставить только ошибки*/
    foreach ($_POST as $key => $value) {
        if (!isset($errors[$key]) && isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);

    /* проверяет массив с ошибками, если он не пустой значит показывает их пользователю,
    если ошибок нет добавляем задачу в бд и делаем редирект на главную страницу*/
    saveProjectAndRedirect($errors, $connect, $project);
}

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
