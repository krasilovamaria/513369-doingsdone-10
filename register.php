<?php
require_once('config/init.php');

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

    /* проверяем, что обязательные поля заполнены*/
    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    /* возвращает значения массива*/
    $errors = array_filter($errors);

    /* проверяет email*/
    if (!isset($errors['email'])) {
        $errors['email'] = "Электронная почта введена неверна";
    }
    /* проверяет, что email уникальный*/
    if (!isset($errors['email'])) {
        $sql_email = "SELECT email FROM user WHERE email = /* что прописать 'email' ??*/";
        $result = mysqli_query($connect, $sql_email);
        $email = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if ($email) {
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
