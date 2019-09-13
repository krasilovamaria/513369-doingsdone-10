<?php
require_once('config/init.php');
require_once('vendor/autoload.php');

/* получает список задач*/
$tasks = getTasks($connect, $user_id, $project_id, $filter, $show_complete_tasks);

/* если задачи есть отправляет письмо*/
if (!empty($tasks)) {
    /* создает объект transport для отправки письма*/
    $transport = new Swift_SmtpTransport('phpdemo.ru', 25); /* SMTP сервер: phpdemo.ru, порт:25*/
    $transport->setUsername('keks@phpdemo.ru'); /* отправитель: keks@phpdemo.ru*/
    $transport->setPassword('htmlacademy'); /* пароль: htmlacademy*/
    $mailer = new Swift_Mailer($transport); /* объект библиотеки SwiftMailer, ответственный за отправку сообщений*/
    /* формирует само письмо*/
    foreach ($tasks as $key => $value) {
        $msg = "";
        $msg_content = "";
        /* меняет имя для каждого пользователя*/
        $msg_content = "Уважаемый(-ая) " . $value['name'] . " у вас запланирована задача(-и) ";
        /* обнуляет переменную для работы*/
        $msg = "";
        /* формирует список задач для пользователя*/
        foreach ($tasks as $k => $task) {
            $msg .= "<br>" . $task['name'] . " на дату: " . correct_visual_date($task['term']) . " ";
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
