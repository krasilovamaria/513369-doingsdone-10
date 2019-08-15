<?php
/* подсчет задач */
function count_tasks(array $arr_tasks, string $project_name)
{
    $tasks_amount = 0;
    foreach ($arr_tasks as $value) {
        if ($value['category'] === $project_name) {
            $tasks_amount++;
        }
    }

    return $tasks_amount;
}

/* фильтрует данные, для защиты от XSS */
function filter_text($str) {
	$text = htmlspecialchars($str);

	return $text;
}
?>
