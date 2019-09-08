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

/* показывает выполненные задачи, после нажатия на чекбокс*/
if (isset($_GET['task_id'], $_GET['check']) && $_GET['task_id'] !== '' && $_GET['check'] !== '') {
    $task_id = mysqli_real_escape_string($connect, (int) ($_GET['task_id'] ?? 0));
    $check = mysqli_real_escape_string($connect, (int) ($_GET['check'] ?? 0));
    $user_id = mysqli_real_escape_string($connect, $user_id);

    /* обновляет task в бд*/
    $sql_update = "UPDATE task SET status = $check WHERE id = $task_id AND author_id = $user_id";
    $res = mysqli_query($connect, $sql_update);
}

/* получает список проектов*/
/* если параметра нет, то NULL(показывает задачи как есть)*/
$project_id = $_GET['project_id'] ?? null;
$filter = $_GET['filter'] ?? null;
/* если нет параметра устанавливает 0 по умолчанию*/
$show_complete_tasks = $_GET['show_completed'] ?? '0';

/* не дает ввести в строку данные кроме 0 и 1*/
if ($show_complete_tasks !== '0' && $show_complete_tasks !== '1') {
    $show_complete_tasks = '0';
}

$projects = getProjects($connect, $user_id);
/* если параметра запроса не существует, то 404*/
if ($project_id === '') {
    print404Page($user_name, $projects, $show_complete_tasks);
}

/* получает список задач*/
$tasks = getTasks($connect, $user_id, $project_id, $filter, $show_complete_tasks);
/* если по id проекта не нашлось ни одной записи, то 404*/
if (count($tasks) === 0) {
    print404Page($user_name, $projects, $show_complete_tasks);
}

/* добавляет полнотекстовый поиск*/
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($connect, $_GET['search']);
    $sql = "SELECT * FROM task WHERE MATCH(name) AGAINST(?) AND author_id = $user_id ORDER BY date DESC";
    $result = mysqli_query($connect, $sql);

    $find_tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (empty($find_tasks)) {
        $page_content = include_template('not-found.php');
    }
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
    'projects' => $projects,
    'user' => $user_name,
    'content' => $page_content,
    'title' => 'Дела в порядке - Главная страница'
]);

print($layout_content);
