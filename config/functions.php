<?php
/*показывать или нет выполненные задачи*/
$show_complete_tasks = rand(0, 1);

/* считает часы до даты */
function is_date_important($date)
{
    $date_ts = strtotime($date);
    if ($date_ts === null) {
        return false;
    }
    $current_time = time();
    $dt_diff = $date_ts - $current_time;
    $seconds_in_hour = 3600;
    $hours = floor($dt_diff / $seconds_in_hour);
    return $hours <= 24 && $hours > 0;
}

/* получает массив задач и SQL-запрос для отображения списка задач у текущего пользователя */
function getTasks($connect, $project_id = null)
{
    $sql = 'SELECT id, name, status, file, deadline, project_id FROM task';

    if ($project_id !== null) {
        $sql .= ' WHERE project_id = ' . mysqli_real_escape_string($connect, $project_id) /* чтобы не было SQL-инъекций*/;
    }

    $result = mysqli_query($connect, $sql);
    if ($result === false) {
        die("Ошибка при выполнении запроса '$sql'.<br> Текст ошибки: " . mysqli_error($connect));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/* получает массив проектов и SQL-запрос для отображения списка проектов у текущего пользователя */
function getProjects($connect)
{
    $sql = 'SELECT p.id, p.name, COUNT(t.id) as projects_count, t.project_id FROM project p
            LEFT JOIN task t on p.id = t.project_id
            GROUP BY p.id';
    $result = mysqli_query($connect, $sql);
    if ($result === false) {
        die("Ошибка при выполнении запроса '$sql'.<br> Текст ошибки: " . mysqli_error($connect));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/* выделяет активный проект*/
function getProjectsMenuActiveItemClass($project_id)
{
    if (isset($_GET['project_id']) && $_GET['project_id'] === $project_id) {
        return ' main-navigation__list-item--active';
    }
    return '';
}

/* подключает not_found.php*/
function print404Page($user_name, $projects, $show_complete_tasks)
{
    http_response_code(404);

    $page_content = include_template('main.php', [
        'projects' => $projects,
        'show_complete_tasks' => $show_complete_tasks,
        'content' => include_template('not_found.php')
    ]);

    $layout_content = include_template('layout.php', [
        'user' => $user_name,
        'content' => $page_content,
        'title' => 'Дела в порядке - Главная страница'
    ]);
    print($layout_content);

    exit();
}
