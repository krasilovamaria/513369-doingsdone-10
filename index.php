<?php
date_default_timezone_set('Europe/Moscow');

require_once('config/data.php');
require_once('config/functions.php');
require_once('helpers.php');

$page_content = include_template('main.php', [
    'projects' => $projects,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке - Главная страница'
]);

print($layout_content);
