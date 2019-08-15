<?php
require_once('../config/data.php');
require_once('../config/funcs.php');
require_once('../config/config.php');
require_once('../templates/off.php');
require_once('helpers.php');

$page_content = include_template('../templates/main.php', [
    'projects' => $projects,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);
$layout_content = include_template('../templates/layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке - Главная страница'
]);
print($layout_content);

if($config['enable'] === true) {
    require_once($config['tpl_path'] . '../templates/main.php');
} else {
    $error_msg = "Сайт на техническом обслуживании";
    require_once($config['tpl_path'] . '../templates/off.php');
}
?>
