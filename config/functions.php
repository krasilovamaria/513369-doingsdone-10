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

/* проверяет совпадает ли категория проекта с полем project*/
function validateCategory($project, $allowed_list)
{
    $id = $_POST[$project];

    if (empty($id)) {
        return null;
    }

    if (!in_array($id, $allowed_list)) {
        return "Указана несуществующая категория";
    }

    return null;
}

/* проверяет длину строки поля name*/
function validateLength($name, $min, $max)
{
    $len = strlen($_POST[$name]);

    if (empty($len)) {
        return null;
    }

    if ($len < $min or $len > $max) {
        return "Значение должно быть от $min до $max символов";
    }

    return null;
}

/* проверяет массив с ошибками, если он не пустой значит показывает их пользователю,
если ошибок нет добавляем задачу в бд и делаем редирект на главную страницу*/
function getErrors($errors, $connect, $task)
{
    if (count($errors) > 0) {
        $page_content = include_template('add.php', ['errors' => $errors]);
    } else {
        $sql = 'INSERT INTO tasks (id, date, status, name, file, deadline, author_id, project_id)
                VALUES (?, NOW(), 0, ?, ?, ?, 1, ?)';
        $stmt = db_get_prepare_stmt($connect, $sql, [$task['name'], $task['file'], $task['date'], $task['project']]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $task_id = mysqli_insert_id($connect);

            header("Location: index.php?id=" . $task_id);
            exit();
        }
    }
}

/* проверяет дату в форме*/
function validateDate($date)
{
    $date = $_POST['date'];
    /* проверяет дату, если она заполнена*/
    if (!empty($date)) {
        $currentDate = date('Y-m-d');
        /* проверяет формат даты с помощью функции is_date_valid в helpers*/
        if (!is_date_valid($date)) {
           return 'Неверный формат даты';
        }
        /* проверяет меньше ли дата текущей даты*/
        elseif (strtotime($date) <= $currentDate) {
            return 'Дата не может быть меньше текущей';
        }
    }
    return $date /* не знаю что здесь должно быть*/;
}
