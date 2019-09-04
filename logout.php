<?php
require_once('config/init.php');

/* очищает сессию и делает редирект на главную страницу*/
$_SESSION = [];
header("Location: index.php");
