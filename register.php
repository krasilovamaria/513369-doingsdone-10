<?php
require_once('config/init.php');

/* если пользователь есть в сессии, делаем редирект на index.php*/
if (empty($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user = [
    'email' => $_POST['email'] ?? null,
    'password' => $_POST['password'] ?? null,
    'name' => $_POST['name'] ?? null
];
$errors = [];
/* валидация формы*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required = ['email', 'password', 'name'];

    $rules = [
        'name' => function () {
            return validateLength('name', 1, 100);
        }
    ];

    /* проверяет, что обязательные поля заполнены*/
    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    /* отфильтровывает массив от пустых значений, чтобы оставить только ошибки*/
    foreach ($_POST as $key => $value) {
        if (!isset($errors[$key]) && isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);

    /* проверяет email*/
    if (!isset($errors['email']) && filter_var($user['email'], FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Невалидный адрес электронной почты';
    }

    /* проверяет, что email уникальный*/
    if (!isset($errors['email'])) {
        $email = mysqli_real_escape_string($connect, $user['email']);
        $sql_email = "SELECT id FROM user WHERE email = '$email'";
        $result = mysqli_query($connect, $sql_email);

        /* если количество строк больше 0 выводит ошибку*/
        if (mysqli_num_rows($result) > 0) {
            $errors['email'] = "Пользователь с таким e-mail уже зарегистрирован";
        }
    }

    /* проверяет массив с ошибками, если он не пустой значит показывает их пользователю,
    если ошибок нет добавляем user в бд и делаем редирект на главную страницу*/
    saveUserAndRedirect($errors, $connect, $user);
}

/* подключение контента*/
$page_content = include_template('register_main.php', [
    'errors'  => $errors
]);

$layout_content = include_template('auth_layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке - Регистрация'
]);

print $layout_content;
