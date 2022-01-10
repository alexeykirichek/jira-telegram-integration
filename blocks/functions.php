<?php
function message_to_telegram($text,$chat_id) { // Отправка сообщения от имени бота в Телеграм
    include ('variables.php'); // Подключаю переменные
    // Формирую запрос
    $ch = curl_init();
    curl_setopt_array(
        $ch,
        array(
            CURLOPT_URL => 'https://api.telegram.org/bot'.$bot_token.'/sendMessage',
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POSTFIELDS => array(
                'chat_id' => $chat_id,
                'text' => $text,
            ),
        )
    );
    curl_exec($ch); // Отправляю запрос
}
function save_telegram_input($data) {    // Функция для сохранения данных полученного в Телеграм сообщения в БД
    include ('variables.php'); // Подключаю переменные
    $data['text']=addslashes($data['text']); // Добавляю экранирование слешами для записи в базу
    $mysql_telegram_input = new mysqli('localhost', $db_login, $db_pass, $db_name); // Подключаюсь к БД
    if ($data['type_chat']=='private') {$data["title"]='private';} // Помечаю в личном ли чате было написано сообщение
    $mysql_telegram_input->query('INSERT INTO `telegram_input` (`telegram_input_id`, `update_id`, `message_id`, `message_text`, 
    `message_attachment`, `date`, `user_id`, `username`, `is_bot`, `first_name`, `id_chat`, `name_chat`, `issue_jira_id`) 
    VALUES (NULL, "'.$data["update_id"].'", "'.$data["message_id"].'", "'.$data["text"].'", "'.$data["message_attachment"].'", "'.$data["date"].'", 
    "'.$data["id_user"].'", "'.$data["username"].'", "'.$data["is_bot"].'", "'.$data["first_name"].'", "'.$data["id_chat"].'", 
    "'.$data["title"].'", "0");'); // Записываю данные

}
function remove_emoji($text){ // Функция для удаления смайликов перед записью данных в БД
    return preg_replace('~[\x{10000}-\x{10FFFF}]~u', '', $text);
}
function createissue($description) {    // Функция для создания задачи в СД
    include ('variables.php'); // Подключаю переменные
    $description = str_replace("\n", "\\n", $description); // Экранирование переносов строк, чтобы не было ошибок при создании задачи
    $auth_token=base64_encode($sd_login.':'.$sd_token); // Подготовка строки для авторизации в Jira
    // Подготовка даты для темы запроса
    $data_issue = new DateTime();
    $data_issue=$data_issue->format('d.m.Y H:i');
    // Подготовка и отправка запроса в Jira
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://prontosms.atlassian.net/rest/servicedeskapi/request',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
  "requestParticipants": [
    "'.$JiraRequestParticipant.'"
  ],
  "serviceDeskId": "1",
  "requestTypeId": "1",
  "requestFieldValues": {
    "summary": "Запрос из Telegram бота от '.$data_issue.'",
    "description": "'.$description.'",
    "priority": {
        "self": "https://prontosms.atlassian.net/rest/api/2/priority/4",
        "iconUrl": "https://prontosms.atlassian.net/images/icons/priorities/low.svg",
        "name": "Текущие",
        "id": "4"
    }
  }
}',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Basic '.$auth_token,
            'Content-Type: application/json'
        ),
    ));
    $response = curl_exec($curl); // Отправка запроса
    curl_close($curl);
    return json_decode($response, true); // Возврат ответа от Jira
}
function save_issue_data($issue_data, $telegram_user_id, $update_id,$warning_result) { // Сохранение данных по созданному запросу в БД
    include ('variables.php'); // Подключаю переменные
    $mysql_createissue = new mysqli('localhost', $db_login, $db_pass, $db_name); // Подключаюсь к БД
    // Помечаю наличие вложения
    if(empty($issue_data["requestFieldValues"]["4"]["value"])) {
        $issue_data["requestFieldValues"]["4"]["value"]=0;
    }
    else {
        $issue_data["requestFieldValues"]["4"]["value"]=1;
    }
    $issueKeynum = substr($issue_data["issueKey"], 5);
    $issue_data["requestFieldValues"]["2"]["value"]=addslashes($issue_data["requestFieldValues"]["2"]["value"]); // Добавляю экранирование слешами для записи в базу
    $mysql_createissue->query('INSERT INTO `jira_created_issues` (`id_created_issue`, 
    `issueId`, `issueKey`, `issueKeynum`, `create_time`, `subject`, `description`, `author_telegram_id`, `attachment`, `currentStatus`, `update_time`, `link`, `warning`)
     VALUES (NULL, "'.$issue_data["issueId"].'", "'.$issue_data["issueKey"].'", "'.$issueKeynum.'", "'.$issue_data["createdDate"]["epochMillis"].'", 
     "'.$issue_data["requestFieldValues"]["0"]["value"].'", "'.$issue_data["requestFieldValues"]["2"]["value"].'", "'.$telegram_user_id.'", 
     "'.$issue_data["requestFieldValues"]["4"]["value"].'", "'.$issue_data["currentStatus"]["status"].'", 
     "'.$issue_data["currentStatus"]["statusDate"]["epochMillis"].'", "'.$issue_data["_links"]["agent"].'", "'.$warning_result.'");'); // Записываю данные по созданной задаче
    $mysql_createissue->query('UPDATE `telegram_input` SET `issue_jira_id` = "'.$issue_data["issueId"].'" WHERE `telegram_input`.`update_id` = "'.$update_id.'";'); // Обновляю данные по обращению в таблице с сообщениями из Телеграм
}
function save_telegram_log($body) {    // Логирование данных по входящим запросам от Телеграм
    include ('variables.php'); // Подключаю переменные
    $data_telegram_log = new DateTime(); // Создание текущей даты
    $data_telegram_log=$data_telegram_log->format('d_m_Y'); // Указание формата даты
    $file = $telegram_log_path.'/telegram_log_'.$data_telegram_log.'.txt'; // Путь для лога
    $current = file_get_contents($file); // Открываем файл для получения существующего содержимого или создаем файл, если его нет
    $current .= $body."\n"."---------------------"."\n"; // Пишем в строку новый запрос с разделителем
    file_put_contents($file, $current); // Записываем эту строку в файл
}

function save_jira_input_comments($data) { // Записываю данные по полученному комментарию от Джира
    include ('variables.php'); // Подключаю переменные
    // $data['comment_text']=addslashes($data['comment_text']); // Добавляю экранирование слешами для записи в базу
    $mysql_jira_input_comments = new mysqli('localhost', $db_login, $db_pass, $db_name); // Подключаюсь к БД
    $mysql_jira_input_comments->query('INSERT INTO `jira_input_comments` 
    (`jira_input_id`, `id_issue`, `timestamp`, `webhookEvent`, `user_input`, `comment_id`, `comment_text`) 
    VALUES (NULL, "'.$data['id_issue'].'", "'.$data['timestamp'].'", "'.$data['webhookEvent'].'", "'.$data['user_input'].'", 
    "'.$data['comment_id'].'", "'.$data['comment_text'].'");'); // Записываю данные по полученному комментарию в БД
    $mysql_jira_input_comments->query('UPDATE `jira_created_issues` SET `update_time` = "'.$data['timestamp'].'" WHERE `jira_created_issues`.`issueId` = "'.$data['id_issue'].'";'); // Обновляю время последнего изменения задачи в таблице с задачами Jira
}
function take_comment_issue_data($comment_id) { // Подготавливаю данные для отправки сообщения о полученном комментарии пользователю в Телеграм
    include ('variables.php'); // Подключаю переменные
    $mysql_jira_input_comments = new mysqli('localhost', $db_login, $db_pass, $db_name); // Подключаюсь к БД
    $result_take_comment_issue_text=$mysql_jira_input_comments->query('SELECT jci.issueKey, jic.comment_text, ti.username, ti.name_chat, ti.id_chat
    FROM `jira_input_comments` as jic LEFT JOIN `jira_created_issues` as jci ON jic.id_issue=jci.issueId 
    LEFT JOIN `telegram_input` as ti ON jci.issueId=ti.issue_jira_id WHERE jic.comment_id="'.$comment_id.'" LIMIT 1;'); // Отправляю запрос
    for ($jira_input_comments = array(); $row = $result_take_comment_issue_text->fetch_assoc(); $jira_input_comments[] = $row) ; //Сохраняю результат в массив
    if ($jira_input_comments[0]['name_chat']=='private') { // Если чат личный
        $jira_input_comments_data['text']='По Вашему запросу '.$jira_input_comments[0]['issueKey'].' получен новый комментарий с текстом: "'.$jira_input_comments[0]['comment_text'].'".'; // Используем данный текст
    }
    else { // Если общий
        $jira_input_comments_data['text']='@'.$jira_input_comments[0]['username'].', по Вашему запросу '.$jira_input_comments[0]['issueKey'].' получен новый комментарий с текстом: "'.$jira_input_comments[0]['comment_text'].'".'; // Используем данный текст
    }
    $jira_input_comments_data['id_chat']=$jira_input_comments[0]['id_chat']; // Сохраняем идентификатор чата, чтобы можно было отправить в него сообщение
    return $jira_input_comments_data;
}
function save_jira_input_updates($data) { // Записываю данные по полученному обновлению от Джиры
    include ('variables.php'); // Подключаю переменные
    $mysql_jira_input_updates = new mysqli('localhost', $db_login, $db_pass, $db_name); // Подключаюсь к БД
    $mysql_jira_input_updates->query('INSERT INTO `jira_input_updates` 
    (`jira_input_update_id`, `id_issue`, `timestamp`, `webhookEvent`, `user_update`, `id_changelog`) VALUES 
    (NULL, "'.$data['id_issue'].'", "'.$data['timestamp'].'", "'.$data['webhookEvent'].'", "'.$data['user_update'].'", 
    "'.$data['id_changelog'].'");'); // Записываю данные по полученному обновлению в БД
    $mysql_jira_input_updates->query('UPDATE `jira_created_issues` SET `update_time` = "'.$data['timestamp'].'" WHERE `jira_created_issues`.`issueId` = "'.$data['id_issue'].'";'); // Обновляю время последнего изменения задачи в таблице с задачами Jira
}
function save_input_changelog_items($data) { // Записываю данные по полученным изменениям в обновлении от Джиры
    include ('variables.php'); // Подключаю переменные
    $mysql_input_changelog_items = new mysqli('localhost', $db_login, $db_pass, $db_name); // Подключаюсь к БД
    $mysql_input_changelog_items->query('INSERT INTO `jira_input_changelog_items` 
    (`id_input_changelog_item`, `id_changelog`, `field_item`, `fieldtype_item`, `fieldId_item`, `from_item`, `to_item`, 
     `fromString`, `toString`) VALUES (NULL, "'.$data['id_changelog'].'", "'.$data['field_item'].'", "'.$data['fieldtype_item'].'", "'.$data['fieldId_item'].'", "'.$data['from_item'].'", "'.$data['to_item'].'", 
     "'.$data['fromString'].'", "'.$data['toString'].'");'); // Записываю данные в БД
    return $mysql_input_changelog_items->insert_id; // Возвращаю идентификатор созданной строки
}
function take_change_status_issue_data($jira_input_update_id) { // Подготавливаю данные для отправки сообщения о полученном изменении в задаче пользователю в Телеграм
    include ('variables.php'); // Подключаю переменные
    $mysql_change_status_issue_data = new mysqli('localhost', $db_login, $db_pass, $db_name); // Подключаюсь к БД
    $result_change_status_issue_data=$mysql_change_status_issue_data->query(' SELECT jici.fromString, jici.toString, jiu.user_update, jci.issueKey, ti.username, ti.name_chat, ti.id_chat 
    FROM jira_input_changelog_items as jici LEFT JOIN jira_input_updates as jiu on jici.id_changelog=jiu.id_changelog LEFT JOIN `jira_created_issues` as jci ON jiu.id_issue=jci.issueId 
    LEFT JOIN `telegram_input` as ti ON jci.issueId=ti.issue_jira_id where jici.id_input_changelog_item="'.$jira_input_update_id.'" LIMIT 1'); // Делаю запрос в БД
    for ($jira_change_status_issue = array(); $row = $result_change_status_issue_data->fetch_assoc(); $jira_change_status_issue[] = $row) ; //Сохраняю результат в массив
    if ($jira_change_status_issue[0]['name_chat']=='private') { // Если чат личный
        $change_status_issue_data['text']='Спешу сообщить, что '.$jira_change_status_issue[0]['user_update'].' изменил статус Вашего запроса '.$jira_change_status_issue[0]['issueKey'].' c "'.$jira_change_status_issue[0]['fromString'].'" на "'.$jira_change_status_issue[0]['toString'].'"'; // Использую такой текст
    }
    else { // Если общий
        $change_status_issue_data['text']='@'.$jira_change_status_issue[0]['username'].', спешу сообщить, что '.$jira_change_status_issue[0]['user_update'].' изменил статус Вашего запроса '.$jira_change_status_issue[0]['issueKey'].' c "'.$jira_change_status_issue[0]['fromString'].'" на "'.$jira_change_status_issue[0]['toString'].'"'; // Использую такой текст
    }
    if ($jira_change_status_issue[0]['toString']=='Обработано') { // Если статус изменился на "Обработано"
        $change_status_issue_data['text'].='. Спасибо, что Вы с нами!'; // Дописать данный текст в конце строки
    }
    else { // Если нет
        $change_status_issue_data['text'].='.'; // Дописать данный текст в конце строки
    }
    $change_status_issue_data['id_chat']=$jira_change_status_issue[0]['id_chat']; // Сохраняем идентификатор чата в который отправлять сообщение
    return $change_status_issue_data; // Возвращаем все подготовленные данные
}
function change_status_issue($data) { // Изменение статуса задачи
    include ('variables.php'); // Подключаю переменные
    $mysql_change_status_issue = new mysqli('localhost', $db_login, $db_pass, $db_name); // Подключаюсь к БД
    $mysql_change_status_issue->query('UPDATE `jira_created_issues` SET `currentStatus` = "'.$data['toString'].'" WHERE `jira_created_issues`.`issueId` = "'.$data['id_issue'].'";'); // Обновляю время последнего изменения задачи в таблице с задачами Jira
}
function save_jira_log($body) { // Логирование полученных от Джиры данных
    include ('variables.php'); // Подключаю переменные
    $data_jira_log = new DateTime(); // Сохраняю текущую дату
    $data_jira_log=$data_jira_log->format('d_m_Y'); // Изменяю ее формат
    $file = $jira_log_path.'/jira_log_'.$data_jira_log.'.txt'; // Создаю путь к файлу с логом
    $current = file_get_contents($file); // Открываю файл для получения существующего содержимого или создаю файл, если его нет
    $current .= $body."\n"."---------------------"."\n"; // Пишу в строку полученные данные с разделителем
    file_put_contents($file, $current); // Пишу строку в файл
}
function take_issueKey($text) { // Выбрать идентификатор задачи из текста сообщения
    include ('variables.php'); // Подключаю переменные
    $issueKey=$project_name.'-'; // Генерирую начало идентификатора в соответствии с кодом проекта
    $pos = strpos($text, $project_name); // Ищу код проекта в тексте сообщения
    $text = substr($text, $pos+5); // Обрезаю строку до начала номера запроса
    $issuenum=''; // Создаю строку для номера запроса
    $i=0; // Создаю счетчик для цикла
    while ($i < strlen($text)) {// Перебираю оставшиеся символы в строке
        if (is_numeric($text[$i])) {// Если цифра
            $issuenum.=$text[$i];  // Сохраняю в строку номера запроса
        }
        else { // Если цифры кончились
            break; // Выхожу из цикла
        }
        $i = $i + 1; // Если сохранил цифру, обновляю счетчик
    }
    $issueKey.=$issuenum; // Собираю идентификатор задачи
    return $issueKey; // Отдаю его
}
function update_issue_jira_id($update_id,$issueKey) { // Обновление идентификатора запроса по комментарию
    include ('variables.php'); // Подключаю переменные
    $issueKeynum = substr($issueKey, 5);
    $mysql_update_issue_jira_id = new mysqli('localhost', $db_login, $db_pass, $db_name); // Подключаюсь к БД
    $result_update_issue_jira_id = $mysql_update_issue_jira_id->query('SELECT issueId FROM `jira_created_issues` WHERE issueKeynum="' . $issueKeynum . '" LIMIT 1'); // Ищу идентификатор запроса по ключу запроса
    $jira_update_issue_jira_id = $result_update_issue_jira_id->fetch_assoc(); // Сохраняем результат
    if(!empty($jira_update_issue_jira_id)) { // Если нашлось
        $issueId = $jira_update_issue_jira_id['issueId']; // Записываю его в отдельную переменную
        $mysql_update_issue_jira_id->query('UPDATE `telegram_input` SET `issue_jira_id` = "' . $issueId . '" WHERE `telegram_input`.`update_id` = "' . $update_id . '";'); // Сохраняю в БД
        $update_issue_jira_id_data['issueId']=$issueId; // Подготавливаю данные для возврата
        $update_issue_jira_id_data['result']=true; // Подготавливаю данные для возврата
        return $update_issue_jira_id_data; // Возвращаю нужную для дальнейших действий информацию
    }
    else { // Если не нашлось
        $update_issue_jira_id_data['result']=false; // Подготавливаю данные для возврата
        return $update_issue_jira_id_data; // Возвращаю нужную для дальнейших действий информацию
    }
}
function create_comment_jira($text,$issueId) { // Создание комментария по запросу в Джире
    include ('variables.php'); // Подключаю переменные
    $text = str_replace("\n", "\\n", $text); // Экранирование переносов строк, чтобы не было ошибок при создании комментария
    $auth_token=base64_encode($sd_login.':'.$sd_token); // Подготовка строки для авторизации в Jira
    // Подготовка и отправка комментария в Jira
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://prontosms.atlassian.net/rest/servicedeskapi/request/'.$issueId.'/comment',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
  "public": true,
  "body": "'.$text.'"
}',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Basic '.$auth_token,
            'Content-Type: application/json'
        ),
    ));
    $response = curl_exec($curl); // Отправка запроса
    curl_close($curl);
    return json_decode($response, true); // Возврат ответа от Jira
}
function delete_bot_name_from_text($text) { // Удаление имени бота из текста сообщения
    include ('variables.php'); // Подключаю переменные
    $text = str_replace('@'.$bot_name.' , ', '', $text); // Ищу и удаляю имя бота в разных конфигурациях
    $text = str_replace('@'.$bot_name.', ', '', $text); // Ищу и удаляю имя бота в разных конфигурациях
    $text = str_replace('@'.$bot_name.'. ', '', $text); // Ищу и удаляю имя бота в разных конфигурациях
    $text = str_replace('@'.$bot_name.' ', '', $text); // Ищу и удаляю имя бота в разных конфигурациях
    $text = str_replace('@'.$bot_name, '', $text); // Ищу и удаляю имя бота в разных конфигурациях
    return $text; // Возвращаю результат
}
function delete_issueKey_from_text($text,$issueKey) { // Удаление ключа запроса из текста сообщения
    include ('variables.php'); // Подключаю переменные
    $text = str_replace($issueKey.' , ', '', $text); // Ищу и удаляю ключ запроса в разных конфигурациях
    $text = str_replace($issueKey.', ', '', $text); // Ищу и удаляю ключ запроса в разных конфигурациях
    $text = str_replace($issueKey.'. ', '', $text); // Ищу и удаляю ключ запроса в разных конфигурациях
    $text = str_replace($issueKey.' ', '', $text); // Ищу и удаляю ключ запроса в разных конфигурациях
    $text = str_replace($issueKey, '', $text); // Ищу и удаляю ключ запроса в разных конфигурациях
    return $text; // Возвращаю результат
}
function check_non_working_time() { // Проверяет нерабочее время и возвращяет true, если сейчас никто не работает
    include ('variables.php'); // Подключаю переменные
    $day_of_the_week = new DateTime(); // Сохраняю текущую дату
    $day_of_the_week=$day_of_the_week->format('l'); // Привожу ее в формат дня недели
    $hour_of_the_day=new DateTime(); // Сохраняю текущую дату
    $hour_of_the_day=$hour_of_the_day->format('H'); // Привожу ее в формат текущего часа
    $hour_of_the_day=(int)$hour_of_the_day; // Преобразую в число
    $date_of_the_day = new DateTime(); // Сохраняю текущую дату
    $date_of_the_day=$date_of_the_day->format('d.m'); // Привожу ее в формат текущей минуты
    if ($day_of_the_week=='Saturday'||$day_of_the_week=='Sunday') {
        return true;
    }
    elseif ($date_of_the_day=='01.01'||$date_of_the_day=='02.01'||$date_of_the_day=='03.01'||$date_of_the_day=='04.01'||
        $date_of_the_day=='05.01'||$date_of_the_day=='06.01'||$date_of_the_day=='07.01'||$date_of_the_day=='08.01'||
        $date_of_the_day=='23.02'||$date_of_the_day=='08.03'||$date_of_the_day=='01.05'||$date_of_the_day=='09.05'||
        $date_of_the_day=='12.06'||$date_of_the_day=='04.11') {
        return true;
    }
    elseif ($hour_of_the_day<$work_time_begin || $hour_of_the_day>=$work_time_end) {
        return true;
    }
    else {
        return 0;
    }
}
function disable_warning($issueId) { // Отлючение варнинга по задаче
    include ('variables.php'); // Подключаю переменные
    $mysql_disable_warning = new mysqli('localhost', $db_login, $db_pass, $db_name); // Подключаюсь к БД
    $result_disable_warning = $mysql_disable_warning->query('SELECT warning FROM `jira_created_issues` WHERE issueId="' . $issueId . '" LIMIT 1'); // Проверяю наличие варнинга по ключу запроса
    $status_warning = $result_disable_warning->fetch_assoc(); // Сохраняем результат
    if($status_warning['warning']==true) { // Если варнинг есть
        $mysql_disable_warning->query('UPDATE `jira_created_issues` SET `warning` = "0" WHERE `jira_created_issues`.`issueId` = "' . $issueId . '";'); // Сохраняю в БД
    }
}
function check_warning_telegram_users($username) {
    include ('warning_telegram_users.php');
    $result_check_warning_telegram_users=0;
    if(!empty($warning_telegram_users)) {
        foreach ($warning_telegram_users as $warning_telegram_user) {
            if ($warning_telegram_user==$username) {
                $result_check_warning_telegram_users=true;
                break;
            }
        }
    }
    return $result_check_warning_telegram_users;
}
function check_author_issue($id_user,$issueKey) { // Проверка является ли автор комментария автором запроса
    include ('variables.php'); // Подключаю переменные
    $issueKeynum = substr($issueKey, 5);
    $mysql_check_author_issue = new mysqli('localhost', $db_login, $db_pass, $db_name); // Подключаюсь к БД
    $result_check_author_issue = $mysql_check_author_issue->query('SELECT author_telegram_id FROM `jira_created_issues` WHERE issueKeynum="' . $issueKeynum . '" LIMIT 1'); // Ищу идентификатор запроса по ключу запроса
    $result_check_author_issue = $result_check_author_issue->fetch_assoc(); // Сохраняем результат
    if ($result_check_author_issue['author_telegram_id']==$id_user) {
        return true;
    }
    else {
        return 0;
    }
}
function linkreformator($comment_text) { // Меняем формат ссылок, если они есть
    $comment_text_trim=trim($comment_text); // Обрезам пробелы с начала и с конца
    if ((mb_substr($comment_text_trim, 0,5)=='[http'&&mb_substr($comment_text_trim,-1)==']')){ // Если без пробелов в тексте с начала и конца только ссылка
        // Если нужно привести ссылку в формат вида ya.ru
        $comment_text_trim = mb_substr($comment_text_trim, mb_strpos($comment_text_trim, '//')+2); // Обрезаем ссылку слева для вставки
        $comment_text_trim = mb_substr($comment_text_trim, 0, mb_strpos($comment_text_trim, '|')); // Обрезаем ссылку справа для вставки
        if (mb_substr($comment_text_trim, -1)=='/') { // Если в конце ссылки слеш
            $comment_text_trim=substr_replace ($comment_text_trim, "", -1); // Обрезать его
        }
        // Если нужно привести ссылку в формат вида http://ya.ru или https://ya.ru (зависит от входных данных)
//    $comment_text_trim = str_replace($comment_text_trim, mb_substr($comment_text_trim, 1, mb_strpos($comment_text_trim, '|')-1), $comment_text_trim);
        return $comment_text_trim; // Вернуть обрезанную ссылку в качестве текста
    }
    else { // Если в тексте без пробелов с начала и конца не только ссылка
        while (strpos($comment_text, '[http')!=0) { // Для каждой ссылки
            $link = mb_substr($comment_text, mb_strpos($comment_text, '[http')); // Готовим ссылку обрезая слева
            $link = mb_substr($link, 0, (mb_strpos($link, ']')-mb_strlen($link)+1)); // Готовим ссылку обрезая справа
            // Если нужно привести ссылку в формат вида ya.ru
            $replace_link = mb_substr($link, mb_strpos($link, '//')+2); // Обрезаем ссылку слева для вставки
            $replace_link = mb_substr($replace_link, 0, mb_strpos($replace_link, '|')); // Обрезаем ссылку справа для вставки
            if (mb_substr($replace_link, -1)=='/') { // Если в конце ссылки слеш
                $replace_link=substr_replace ($replace_link, "", -1); // Обрезать его
            }
            $comment_text = str_replace($link, $replace_link, $comment_text); // Заменить ссылку в тексте
            // Если нужно привести ссылку в формат вида http://ya.ru или https://ya.ru (зависит от входных данных)
    //        $comment_text = str_replace($link, mb_substr($link, 1, mb_strpos($link, '|')-1), $comment_text);
        }
        return $comment_text; // Вернуть измененный текст
    }
}
