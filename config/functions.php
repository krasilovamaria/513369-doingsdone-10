<?php
/*показывать или нет выполненные задачи*/
$show_complete_tasks = rand(0, 1);

/* подсчет задач */
function count_tasks(array $arr_tasks, string $project_name)
{
    $tasks_amount = 0;
    foreach ($arr_tasks as $value) {
        if ($value['name'] === $project_name) {
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
