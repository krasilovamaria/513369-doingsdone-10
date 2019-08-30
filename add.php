<?php
require_once('config/init.php');

/* получает список проектов*/
/* если параметра нет, то NULL(показывает задачи как есть)*/
$project_id = $_GET['project_id'] ?? null;
$projects = getProjects($connect);
/* получает список задач*/
$tasks = getTasks($connect, $project_id);

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

    // проверяем поле Название на валидность
    if (isset($_POST['name']) && trim($_POST['name']) !== '') {
        // вырезаем по краям Названия пробелы
        $name = trim($_POST['name']);

        // пользователь указал Название, выполняем проверку на длину
        // вычисляем длину строки, принимая во внимание кодировку,
        // функция strlen вернет неверную длину строки для кириллических символов
        if (mb_strlen($name, 'UTF-8') > 20) {
            $errors['name'] = 'Название не должно превышать 20 символов.';
        } else {
            // Название валидно
            $task['name'] = $name;
        }
    } else {
        // пользователь не ввел Названия вообще
        $errors['name'] = 'Заполните это поле.';
    }

    // проверяем поле Проект
    if (isset($_POST['project']) && trim($_POST['project']) !== '') {
        // приводим строку к типу integer, т.к. мы ожидаем получить id проекта, а не его название
        $project = intval($_POST['project']);

        // проверяем входит ли данный проект в список допустимых для данного пользователя
        if (array_search($project, $project_ids) === false) {
            // не входит в список допустимых
            $errors['project'] = 'Указан несуществующий проект.';
        } else {
            // Проект валиден
            $task['project'] = $project;
        }
    } else {
        // поле Проект оказалось невыбранным
        $errors['project'] = 'Заполните это поле.';
    }

    /* проверяет загружен ли файл*/
    if (isset($_FILES['file']['error']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {

        /* дает возможность загрузить файл, если нет ошибок, если есть отменяет загрузку файла*/
        if (!empty($errors)) {
            $errors['file'] = 'Файл будет отправлен только после заполнения всех обязательных полей';
        } else {
            $file_name = $_FILES['file']['name'];
            $uniq_name = uniqid() . $file_name;
            $file_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
            $task['file'] = '/uploads/' . $uniq_name;
            move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $uniq_name);
        }

    } else if (isset($_FILES['file']['error']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $errors['file'] = 'Не удалось загрузить файл';
    }

    /* проверяет массив с ошибками, если он не пустой значит показывает их пользователю,
    если ошибок нет добавляем задачу в бд и делаем редирект на главную страницу*/
    saveTaskAndRedirect($errors, $connect, $task);
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
