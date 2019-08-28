<?php
require_once('config/init.php');

/* получает список проектов*/
/* если параметра нет, то NULL(показывает задачи как есть)*/
$project_id = $_GET['project_id'] ?? null;
$projects = getProjects($connect);

$task = [
    'name' => $_POST['name'] ?? null,
    'project' => $_POST['project'] ?? null,
    'date' => $_POST['date'] ?? null,
    'file' => $_POST['file'] ?? null
];

$errors = [];
$data = [];

/* получает список задач*/
$tasks = getTasks($connect, $project_id);

/* валидация формы*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projects = getProjects($connect);
    $project_ids = array_column($projects, 'id');
    $required = ['name', 'project_id'];

    /* проверяет $project и $name*/
    $rules = [
        'project_id' => function () use ($project_ids) {
            return validateCategory('project_id', $project_ids);
        },
        'name' => function () {
            return validateLength('name', 1, 20);
        },
        'date' => function () {
            return validateDate('date');
        }
    ];

    /* проверяет, что обязательный поля заполнены*/
    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    /* отфильтровывает массив от пустых значений, чтобы оставить только ошибки*/
    foreach ($_POST as $key => $value) {
        if (!isset($errors[$key] && isset($rules[$key]))) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);

    /* проверяет загружен ли файл*/
    if (isset($_FILES['file']['error']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {

        /* дает возможность загрузить файл, если нет ошибок, если есть отменяет загрузку файла*/
        if (!empty($errors)) {
            $errors['file'] = 'Файл будет отправлен только после заполнения всех обязательных полей';
        }

        $file_name = $_FILES['file']['name'];
        $uniq_name = uniqid($file_name);
        $file_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
        $data['file_url'] = '/uploads/' . $uniq_name;
        move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $uniq_name);

    } else if (isset($_FILES['file']['error']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $errors['file'] = 'Не удалось загрузить файл';
    }

    /* проверяет массив с ошибками, если он не пустой значит показывает их пользователю,
    если ошибок нет добавляем задачу в бд и делаем редирект на главную страницу*/
    getErrors($errors, $connect, $task);
}

/* подключение контента*/
$page_content = include_template('add_main.php', [
    'projects' => $projects,
    'errors'  => $errors
]);

$layout_content = include_template('layout.php', [
    'user' => $user_name,
    'content' => $page_content,
    'title' => 'Дела в порядке - Добавление задачи'
]);

print $layout_content;
