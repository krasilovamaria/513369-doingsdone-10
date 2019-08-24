<?php
/*показывать или нет выполненные задачи*/
$show_complete_tasks = rand(0, 1);

/* подсчет задач */
function count_tasks(array $arr_tasks, string $id)
{
    $tasks_amount = 0;
    foreach ($arr_tasks as $value) {
        if ($value['project_id'] === $id) {
            $tasks_amount++;
        }
    }

    return $tasks_amount;
}

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
function getTasks($connect)
{
    $sql = 'SELECT id, name, status, file, deadline, project_id FROM task';
    $result = mysqli_query($connect, $sql);
    if ($result === false) {
        die("Ошибка при выполнении запроса '$sql'.<br> Текст ошибки: ".mysqli_error($connect));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/* получает массив проектов и SQL-запрос для отображения списка проектов у текущего пользователя */
function getProjects($connect)
{
    $sql = 'SELECT id, name FROM project';
    $result = mysqli_query($connect, $sql);
    if ($result === false) {
        die("Ошибка при выполнении запроса '$sql'.<br> Текст ошибки: ".mysqli_error($connect));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
