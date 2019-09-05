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

/* проверяет существование куки, если кука существует,
то показывает выполненные задачи или нет*/
$show_task = 'show_task';

if (isset($_COOKIE['show_task'])) {
    $show_complete_tasks = intval($_COOKIE['show_task']);
}
/* устанавливает куку*/
setcookie("show", $show_complete_tasks);

/* показывает выполненные задачи, после нажатия на чекбокс*/
if (isset($_GET['id'])) {
    $task_id = intval($_GET['id']);
    $sql = "SELECT * FROM task WHERE id = '$task_id'";
    $result = mysqli_query($connect, $sql);
    $task_complete = mysqli_fetch_assoc($result);

    /* инвертирует статус задачи выполнена -> не выполнена, не выполнена -> выполнена*/
    if($task_complete['status'] === 0) {
        $status = 1;
    } else {
        $status = 0;
    }

    /* обновляет task в бд*/
    $sql_update = "UPDATE task SET status = '$status' WHERE id ='$task_id'";
    $res = mysqli_query($connect, $sql_update);
}

/* получает список проектов*/
/* если параметра нет, то NULL(показывает задачи как есть)*/
$project_id = $_GET['project_id'] ?? null;
$projects = getProjects($connect);

/* получает список задач*/
$tasks = getTasks($connect, $project_id);

/* если параметра запроса не существует, то 404*/
if ($project_id === '') {
    print404Page($user_name, $projects, $show_complete_tasks);
}
$tasks = getTasks($connect, $project_id);
/* если по id проекта не нашлось ни одной записи, то 404*/
if (count($tasks) === 0) {
    print404Page($user_name, $projects, $show_complete_tasks);
}



/* подключение контента через include_template*/
$page_content = include_template('main.php', [
    'projects' => $projects,
    'show_complete_tasks' => $show_complete_tasks,
    'content' => include_template('table_tasks.php', [
        'show_complete_tasks' => $show_complete_tasks,
        'tasks' => $tasks
    ])
]);
$layout_content = include_template('layout.php', [
    'user' => $user_name,
    'content' => $page_content,
    'title' => 'Дела в порядке - Главная страница'
]);

print($layout_content);
