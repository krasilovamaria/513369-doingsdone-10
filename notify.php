<?php
require_once('config/init.php');
require_once('vendor/autoload.php');

/* получает список задач на сегодня*/
$sql = "SELECT u.id AS id, email, u.name AS name FROM task t
LEFT JOIN user u ON t.author_id = u.id WHERE status = 0
AND deadline = CURDATE() GROUP BY u.id";
$result = mysqli_query($connect, $sql);
$users_current_date = mysqli_fetch_all($result, MYSQLI_ASSOC);

/* если задачи есть отправляет письмо*/
if (!empty($users_current_date)) {
    /* создает объект transport для отправки письма*/
    $transport = new Swift_SmtpTransport('phpdemo.ru', 25); /* SMTP сервер: phpdemo.ru, порт:25*/
    $transport->setUsername('keks@phpdemo.ru'); /* отправитель: keks@phpdemo.ru*/
    $transport->setPassword('htmlacademy'); /* пароль: htmlacademy*/
    $mailer = new Swift_Mailer($transport); /* объект библиотеки SwiftMailer, ответственный за отправку сообщений*/
    /* формирует само письмо*/
    foreach ($users_current_date as $key => $value) {
        $msg = "";
        $msg_content = "";
        /* получает задачи для определенного пользователя*/
        $sql_tasks = "SELECT * FROM task WHERE status = 0 AND deadline = CURDATE() AND author_id = " . $value['id'];
        $result = mysqli_query($connect, $sql_tasks);
        $user_tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);

        /* меняет имя для каждого пользователя*/
        $msg_content = "Уважаемый(-ая) " . $value['name'] . " у вас запланирована задача(-и) ";
        /* обнуляет переменную для работы*/
        $msg = "";
        /* формирует список задач для пользователя*/
        foreach ($user_tasks as $k => $task) {
            $msg .= "<br>" . $task['name'] . " на дату: " . correct_visual_date($task['deadline']) . " ";
        }
        /* формирует шаблон письма*/
        $message = new Swift_Message('Уведомление от сервиса «Дела в порядке»');
        $message->setFrom(['keks@phpdemo.ru' => 'keks@phpdemo.ru']);
        $message->setTo([$value['email'] => $value['name']]);
        $message->setBody($msg_content . $msg, 'text/html');
        /* отправка письма*/
        $result = $mailer->send($message);
    }
}
