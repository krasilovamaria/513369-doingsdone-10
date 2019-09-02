<?php
require_once('config/init.php');
session_start();

/* очищает сессию и делает редирект на главную страницу*/
$_SESSION = [];
header("Location: index.php");
