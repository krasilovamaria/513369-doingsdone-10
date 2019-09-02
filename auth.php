<?php
require_once('config/init.php');

/* если есть данные о пользователе в $user из сессии открывает доступ к странице,
если нет то редирект на гостевую страницу*/
if (!empty($user)) {
    header("Location: /");
    exit();
}

$user = [
    'email' => $_POST['email'] ?? null,
    'password' => $_POST['password'] ?? null,
];
$errors = [];
/* валидация формы*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required = ['email', 'password'];

    /* проверяет, что обязательные поля заполнены*/
    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    $errors = array_filter($errors);

    /* проверяет корректный ли email*/
    if (!isset($errors['email']) && filter_var($user['email'], FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'E-mail введён некорректно';
    }

    /* если email заполнен корректно делаем запрос к БД*/
    $email = mysqli_real_escape_string($connect, $user['email']);
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($connect, $sql_email);
    $verify = mysqli_fetch_assoc($result);

    /* проверяет есть ли email в бд*/
    if (empty($errors['email']) && empty($result)) {
        $errors['email'] = "E-mail в базе не найден";
    }

    /* проверяет подходит ли пароль к email*/
    if (empty($errors)) {
        if (!password_verify($user['password'], $verify['password'])) {
            $errors['password'] = 'Неверный пароль';
        }
    }

    /* если нет ошибок, добавляет в сессию пользователя
    и делает редирект на главную страницу*/
    if (empty($errors)) {
        $_SESSION['user_id'] = $verify['user_id'];

        header("Location: index.php");
        exit();
    }
}

/* подключение контента*/
$page_content = include_template('auth.php', [
    'errors'  => $errors
]);

$layout_content = include_template('auth_layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке - Вход'
]);

print $layout_content;
