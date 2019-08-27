<?php
require_once('config/init.php');

/* подключение контента*/
$page_content = include_template('add_main.php', [
    'projects' => $projects,
    'content' => include_template('table_tasks.php', [
        'tasks' => $tasks])
]);

$layout_content = include_template('layout.php',[
    'user' => $user_name,
    'content' => $page_content,
    'title' => 'Дела в порядке - Добавление задачи'
]);

print $layout_content;
