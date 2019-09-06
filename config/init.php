<?php
session_start();

date_default_timezone_set('Europe/Moscow');

require_once('functions.php');
require_once('helpers.php');

/* подключение к БД и кодировка*/
$connect = mysqli_connect('localhost:3366', 'root', 'rootpass', 'doingsdone');
mysqli_set_charset($connect, 'utf8');

/* имя пользователя*/
$user_name = $_SESSION['user_name'] ?? null;
