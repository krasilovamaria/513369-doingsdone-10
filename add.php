<?php
require_once('config/init.php');

/* валидация формы*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task = $_POST;

    $required = ['name', 'project_id'];
    $errors = [];
    $rules = [
        'project_id' => function () use ($project_id) {
            return validateCategory('project_id', $project_id);
        },
        'name' => function () {
            return validateCategory('name', 1, 20);
        }
    ];

    /* отфильтровывем массив от пустых значений, чтобы оставить только ошибки*/
    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);

    foreach ($required as $key) {
        if(empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    if(isset($_FILES['file']['name'])) {
        if(!empty($errors)) {
			$errors['file'] = "Файл отправляетя после заполнения всех обязательных полей";
        }

        $file_name = $_FILES['file']['name'];
        $uniq_name = iniqid($file_name);
        $file_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads';
        $data['file_url'] = '/uploads' . $uniq_name;
        move_uploaded_file($_FILES['file']['tmp_name'], $file_path . $uniq_name);
    }

    if (count($errors)) {
		$page_content = include_template('add.php', ['errors' => $errors]);
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
