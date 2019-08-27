<?php
require_once('config/init.php');

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
        'tasks' => $tasks])
]);
$layout_content = include_template('layout.php', [
    'user' => $user_name,
    'content' => $page_content,
    'title' => 'Дела в порядке - Главная страница'
]);
print($layout_content);
