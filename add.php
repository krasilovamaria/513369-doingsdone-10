<?php
require_once('config/init.php');

/* получает список проектов*/
/* если параметра нет, то NULL(показывает задачи как есть)*/
$project_id = $_GET['project_id'] ?? null;
$projects = getProjects($connect);

/* получает список задач*/
$tasks = getTasks($connect, $project_id);

/* валидация формы*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projects = getProjects($connect);
    $project_ids = array_column($projects, 'id');
    $required = ['name', 'project_id'];
    $errors = [];
    $data = [];

    /* проверяет $project и $name*/
    $rules = [
        'project_id' => function () use ($project_ids) {
            return validateCategory('project_id', $project_ids);
        },
        'name' => function () {
            return validateLength('name', 1, 20);
        },
        'name' => function() {
            return validateFilled('name');
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
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);

    /* проверяет дату, если она заполнена*/
    if(!empty($_POST['date'])) {
        $currentDate = date('Y-m-d');
        /* проверяет формат даты с помощью функции is_date_valid в helpers*/
        if(!is_date_valid($_POST['date'])) {
            $errors['date'] = 'Неверный формат даты';
        }
        /* проверяет меньше ли дата текущей даты*/
        elseif (strtotime($_POST['date']) <= $currentDate) {
            $errors['date'] = 'Дата не может быть меньше текущей';
        }
        /* если все ок записывает в переменную*/
        else {
            $data['date'] = '' . $_POST['date'] . '';
        }
    }

    /* проверяет загружен ли файл*/
    if (isset($_FILES['file']['name'])) {
        /* дает возможность загрузить файл, если нет ошибок, если есть отменяет загрузку файла*/
        if (!empty($errors)) {
            $errors['file'] = 'Файл будет отправлен только после заполнения всех обязательных полей';
        }

        $file_name = $_FILES['file']['name'];
        $uniq_name = uniqid($file_name);
        $file_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads';
        $data['file_url'] = '/uploads/' . $uniq_name;
        move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $uniq_name);
    }

    /* проверяем массив с ошибками, если он не пустой значит показываем их пользователю,
     если ошибок нет добавляем задачу в бд и делаем редирект на главную страницу*/
    if(count($errors)) {
        $page_content = include_template('add.php', ['errors' => $errors]);
    } else {
        $sql = 'INSERT INTO tasks (id, date, status, name, file, deadline, author_id, project_id)
                VALUES (?, NOW(), 0, ?, ?, ?, ?, ?)';
        $stmt = db_get_prepare_stmt($connect, $sql, $tasks);
        $res = mysqli_stmt_execute($stmt);
        if($res) {
            $task_id = mysqli_insert_id($link);

            header("Location: index.php?id=" . $task_id);
        } else {
            $page_content = include_template('add.php', ['projects' => $projects]);
        }
    }
}

/* подключение контента*/
$page_content = include_template('add_main.php', [
    'projects' => $projects,
    'content' => include_template('table_tasks.php', [
        'tasks' => $tasks
    ])
]);

$layout_content = include_template('layout.php', [
    'user' => $user_name,
    'content' => $page_content,
    'title' => 'Дела в порядке - Добавление задачи'
]);

print $layout_content;
