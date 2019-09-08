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
function getTasks($connect, $user_id, $project_id = null, $filter = null, $show_completed = null)
{
    $user_id = mysqli_real_escape_string($connect, $user_id);
    $sql = 'SELECT id, name, status, file, deadline, project_id FROM task WHERE task.author_id =' . $user_id;

    if ($project_id !== null) {
        $sql .= ' AND project_id = ' . mysqli_real_escape_string($connect, $project_id) /* чтобы не было SQL-инъекций*/;
    }

    /* условия для запроса получения задач*/
    if ($filter === 'today') {
        $sql .= ' AND deadline = CURDATE()';
    } elseif ($filter === 'tomorrow') {
        $sql .= ' AND deadline = DATE_ADD(CURDATE(), INTERVAL 1 DAY)';
    } elseif ($filter === 'bad') {
        $sql .= ' AND deadline < CURDATE()';
    }
    if ($show_completed === '0') {
        $sql .= ' AND status = 0';
    }

    $result = mysqli_query($connect, $sql);
    if ($result === false) {
        die("Ошибка при выполнении запроса '$sql'.<br> Текст ошибки: " . mysqli_error($connect));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/* получает массив проектов и SQL-запрос для отображения списка проектов у текущего пользователя */
function getProjects($connect, $user_id)
{
    /* экранируем, чтобы не было иньекций*/
    $user_id = mysqli_real_escape_string($connect, $user_id);
    $sql = 'SELECT p.id, p.name, COUNT(t.id) as projects_count, t.project_id FROM project p
            LEFT JOIN task t on p.id = t.project_id
            WHERE p.author_id =' . $user_id .
        ' GROUP BY p.id';
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
        'projects' => $projects,
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

/* проверяет, чтобы названия проктов были разными для user*/
function validateProjectExists($connect, $user_id, $project_key)
{
    $project_name = $_POST[$project_key] ?? null;

    $project_name  = mysqli_real_escape_string($connect, $project_name);
    $author_id = mysqli_real_escape_string($connect, $user_id);

    $sql = "SELECT * FROM project WHERE name = '$project_name' AND author_id = $author_id";
    $result = mysqli_query($connect, $sql);
    $project = mysqli_fetch_assoc($result);

    if ($project !== null) {
        return 'Проект с таким названием уже существует';
    }

    return null;
}

/* проверяет массив с ошибками, если он не пустой значит показывает их пользователю,
если ошибок нет добавляем задачу в бд и делаем редирект на главную страницу*/
function saveTaskAndRedirect($errors, $connect, $user_id, $task)
{
    if (count($errors) === 0) {
        $sql = 'INSERT INTO task (date, status, name, file, deadline, author_id, project_id)
                VALUES (NOW(), 0, ?, ?, ?, ?, ?)';
        $stmt = db_get_prepare_stmt($connect, $sql, [$task['name'], $task['file'], $task['date'], $user_id, $task['project']]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $task_id = mysqli_insert_id($connect);

            header("Location: index.php?id=" . $task_id);
            exit();
        }
    }
}

/* проверяет массив с ошибками, если он не пустой значит показывает их пользователю,
если ошибок нет добавляем задачу в бд и делаем редирект на главную страницу*/
function saveProjectAndRedirect($errors, $connect, $user_id, $project)
{
    if (count($errors) === 0) {
        $sql = 'INSERT INTO project (name, author_id)
                VALUES (?, ?)';
        $stmt = db_get_prepare_stmt($connect, $sql, [$project['name'], $user_id]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $project_id = mysqli_insert_id($connect);

            header("Location: index.php?id=" . $project_id);
            exit();
        }
    }
}

/* проверяет массив с ошибками, если он не пустой значит показывает их пользователю,
если ошибок нет добавляем user в бд и делаем редирект на главную страницу*/
function saveUserAndRedirect($errors, $connect, $user)
{
    if (count($errors) === 0) {
        $password = password_hash($user['password'], PASSWORD_DEFAULT);

        $sql = 'INSERT INTO user (date, email, name, password)
                VALUES (NOW(), ?, ?, ?)';
        $stmt = db_get_prepare_stmt($connect, $sql, [$user['email'], $user['name'], $password]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $user_id = mysqli_insert_id($connect);

            header("Location: index.php?id=" . $user_id);
            exit();
        }
    }
}

/* получает данные user*/
function getUser($connect, $user)
{
    $email = mysqli_real_escape_string($connect, $user['email']);
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($connect, $sql);
    return $result;
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
        if (strtotime($date) <= strtotime($currentDate)) {
            return 'Дата не может быть меньше текущей';
        }
    }
    return null;
}

/* добавляет фильтры*/
function buildFilterLinkUrl($filterName)
{
    $queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
    parse_str($queryString, $data);
    $data['filter'] = $filterName;

    return http_build_query($data);
}

/* добавляет фильтр для проектов*/
function buildProjectLinkUrl($projectName)
{
    $queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
    parse_str($queryString, $data);
    $data['project'] = $projectName;

    return http_build_query($data);
}
