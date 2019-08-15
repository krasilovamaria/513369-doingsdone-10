<?php
error_reporting(E_ALL);
require_once('config/data.php');
require_once('config/funcs.php');
require_once('config/config.php');
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

if($config['enable'] === true) {
    require_once($config['tpl_path'] . 'main.php');
}
?>
