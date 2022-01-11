<?php
include ('blocks/variables.php'); // Переменные
include ('blocks/functions.php'); // Функции
$body = file_get_contents('php://input'); //Получаем в $body json строку от Джиры
save_jira_log($body);// Сохраняем полученные данные в лог
$arr = json_decode($body, true); //Разбираем json запрос на массив
if (isset($arr['webhookEvent'])) { // Проверяем есть ли какое-то обновление
    $data['webhookEvent']=$arr['webhookEvent']; // Сохраняем название обновления в основной массив
    $data['timestamp']=$arr['timestamp']; // Сохраняем время обновления в основной массив
    $data['id_issue']=$arr['issue']['id']; // Сохраняем идентификатор задачи, по которой было получено обновление, в основной массив
    $data['issueKey']=$arr['issue']['key']; // Сохраняем ключ задачи, по которой было получено обновление, в основной массив
    if ($data['webhookEvent']=='comment_created'&&$arr['comment']['jsdPublic']==true) { // Если обновление сообщает о том, что получен новый комментарий
        $data['comment_id']=$arr['comment']['id']; // Сохраняем идентификатор комментария в основной массив
        $data['comment_text']=$arr['comment']['body']; // Сохраняем текст комментария в основной массив
        $data['comment_text']=linkreformator($data['comment_text']);// Изменяем формат ссылки/ссылок, если они есть
        $data['user_input']=$arr['comment']['author']['displayName']; // Сохраняем имя автора комментария в основной массив
        save_jira_input_comments($data); // Записываю данные по полученному комментарию в БД
        if ($data['user_input']!=$display_name_jira) { // Проверяю не от самого ли клиента (бота) был получен комментарий
            $comment_issue_data=take_comment_issue_data($data['comment_id']); // Если не от бота, то подготавливаю текст для сообщения клиенту
            message_to_telegram($comment_issue_data['text'],$comment_issue_data['id_chat']); // И пишу ему о том, что получен новый комментарий
        }
        exit('Ok'); // Завершаю работу, сообщив джире, что данные были корректно получены
    }
    if ($data['webhookEvent']=='jira:issue_updated') { // Если обновление сообщает о том, что был обновлен запрос
        $data['user_update']=$arr['user']['displayName']; // Сохраняем имя того, кто обновил запрос, в основной массив
        $data['id_changelog']=$arr['changelog']['id']; // Сохраняем идентификатор обновления в основной массив
        save_jira_input_updates($data); // Записываю данные по обновлению в БД
        foreach ($arr['changelog']['items'] as $key => $value) { // Перебираю все действия, полученные в рамках поступившего обновления
            // Сохраняем данные обновления в основной массив
            $data['field_item']=$value['field'];
            $data['fieldtype_item']=$value['fieldtype'];
            $data['fieldId_item']=$value['fieldId'];
            $data['from_item']=$value['from'];
            $data['fromString']=$value['fromString'];
            $data['to_item']=$value['to'];
            $data['toString']=$value['toString'];
            $jira_input_update_id=save_input_changelog_items($data); // Записываю данные по полученному обновлению в БД
            if ($data['fieldId_item']=='status') { // Если были произведены изменения текущего статуса задачи
                change_status_issue($data); // Меняю статус задачи и время ее последнего изменения в БД
                disable_warning($data['id_issue']); // Отключаю warning по задаче при необходимости
                $change_status_issue_data=take_change_status_issue_data($jira_input_update_id); // Подготавливаю данные для оповещения клиента
                message_to_telegram($change_status_issue_data['text'],$change_status_issue_data['id_chat']); // Оповещаю клиента об изменениях в Телеграм
            }
        }
        exit('Ok'); // Завершаю работу, сообщив джире, что данные были корректно получены
    }
    else (exit('Ok'));  // Завершаю работу, сообщив джире, что данные были корректно получены (обновлений не выявлено)
}
else (exit('Отсутствует webhookEvent')); // Завершаю работу, сообщив джире, что данные не были получены (даже webhookEvent не увидел)
