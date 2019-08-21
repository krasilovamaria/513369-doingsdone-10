<?php
date_default_timezone_set('Europe/Moscow');

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

/* подключение к БД и кодировка*/
$db_doingsdone = mysqli_connect('localhost', 'root', '', 'doingsdone');
mysqli_set_charset($db_doingsdone, "utf8");

/* массив проектов и SQL-запрос для получения списка проектов у текущего пользователя */
$projects = [];
$sql_projects = 'SELECT id, name FROM project';
$result_projects = mysqli_query($db_doingsdone, $sql);
if($result_projects) {
    $projects = mysqli_fetch_all($result_projects, MYSQLI_ASSOC);
}

/* массив задач и SQL-запрос для получения списка из всех задач у текущего пользователя */
$tasks = [];
$sql_tasks = 'SELECT id, name, file, deadline FROM task';
$result_tasks = mysqli_query($db_doingsdone, $sql_tasks);
if($result_tasks) {
    $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/* поиск задачи, параметр запроса с поисковой строкой */
$search = trim($_GET['q']) ?? '';

if (!strlen($search)) {
    $content = include_template('main.php', ['tasks' => []]);
}
else {
    $search = "%" . $search . "%";

    /* запрос на поиск задач по имени */
    $sql = "SELECT t.id, name FROM tasks t "
      . "WHERE name LIKE ?";

      $stmt = mysqli_prepare($db_doingsdone, $sql);
      mysqli_stmt_bind_param($stmt, 'ss', $search, $search);
      mysqli_stmt_execute($stmt);

      if ($tasks = mysqli_stmt_get_result($stmt)) {
          $tasks = mysqli_fetch_all($tasks, MYSQLI_ASSOC);
          /* передаем в шаблон результат выполнения */
          $content = include_template('main.php', ['tasks' => $item]);
      }
    }
