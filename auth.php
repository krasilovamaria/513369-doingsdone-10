<?php
require_once('config/init.php');

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

    if (empty($errors)) {
        /* если email заполнен корректно делаем запрос к БД*/
        $result = getUser($connect, $user);
        $verify = mysqli_fetch_assoc($result);

        /* проверяет есть ли email в бд*/
        if (empty($result)) {
            $errors['email'] = "E-mail в базе не найден";
        }

        /* проверяет подходит ли пароль к email*/
        if (!password_verify($user['password'], $verify['password'])) {
            $errors['password'] = 'Неверный пароль';
        }

        /* если нет ошибок, добавляет в сессию пользователя
        и делает редирект на главную страницу*/
        $_SESSION['user_id'] = $verify['id'];

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

/* если пользователь есть в сессии, делаем редирект на index.php*/
if (!empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

print $layout_content;
