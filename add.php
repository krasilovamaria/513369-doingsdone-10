<?php
require_once('config/init.php');

/* если есть данные о user из сессии открывает доступ к главной странице,
если нет делает редирект на гостевую страницу*/
if (empty($_SESSION['user_id'])) {
    print include_template('guest.php', [
        'title' => 'Дела в порядке - Гостевая страница'
    ]);
    exit;
}

/* получает список проектов*/
/* если параметра нет, то NULL(показывает задачи как есть)*/
$project_id = $_GET['project_id'] ?? null;
$projects = getProjects($connect, $user_id);
/* получает список задач*/
$tasks = getTasks($connect, $user_id, $project_id);

$task = [
    'name' => $_POST['name'] ?? null,
    'project' => $_POST['project'] ?? null,
    'date' => empty($_POST['date']) ? null : $_POST['date'],
    'file' => $_POST['file'] ?? null
];

$errors = [];
/* валидация формы*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_ids = array_column($projects, 'id');
    $required = ['name', 'project'];

    /* проверяет $project и $name*/
    $rules = [
        'project' => function () use ($project_ids) {
            return validateCategory('project', $project_ids);
        },
        'name' => function () {
            return validateLength('name', 1, 100);
        },
        'date' => function () {
            return validateDate('date');
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

    /* проверяет загружен ли файл*/
    if (isset($_FILES['file']['error']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {

        /* дает возможность загрузить файл, если нет ошибок, если есть отменяет загрузку файла*/
        if (!empty($errors)) {
            $errors['file'] = 'Файл будет отправлен только после заполнения всех обязательных полей';
        } else {
            $file_name = uniqid() . $_FILES['file']['name'];
            $tmp_name = $_FILES['file']['tmp_name'];
            $file_path = __DIR__ . '/uploads/';
            $file_url = '/uploads' . $file_name;
            $task['file'] = $file_name;
            move_uploaded_file($tmp_name, $file_path . $file_name);
        }
    } else if (isset($_FILES['file']['error']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $errors['file'] = 'Не удалось загрузить файл';
    }

    /* проверяет массив с ошибками, если он не пустой значит показывает их пользователю,
    если ошибок нет добавляем задачу в бд и делает редирект на главную страницу*/
    saveTaskAndRedirect($errors, $connect, $user_id, $task);
}

/* подключение контента*/
$page_content = include_template('add_main.php', [
    'projects' => $projects,
    'errors'  => $errors
]);

$layout_content = include_template('layout.php', [
    'projects' => $projects,
    'user' => $user_name,
    'content' => $page_content,
    'title' => 'Дела в порядке - Добавление задачи'
]);

print $layout_content;
