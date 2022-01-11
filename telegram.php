<?php
include ('blocks/variables.php'); // Переменные
include ('blocks/functions.php'); // Функции
$body = file_get_contents('php://input'); // Получаем в $body json строку от Телеграма
save_telegram_log($body); // Сохраняем полученные данные в лог
$arr = json_decode($body, true); //Разбираем json запрос на массив
if (isset($arr['update_id'])) { // Проверяем есть ли в массиве идентификатор полученного обновления
    $data['update_id']=$arr['update_id']; // Записываем его в основной массив с данными, используемыми в работе
    if (isset($arr['message'])) { // Проверяем есть ли сообщение в полученных данных
        $data['message_id']=$arr['message']['message_id']; // Сохраняем идентификатор сообщения в основной массив
        $data['id_user']=$arr['message']['from']['id']; // Сохраняем идентификатор автора сообщения в основной массив
        $data['username']=$arr['message']['from']['username']; // Сохраняем в основной массив имя пользователя, написавшего сообщение
        $data['first_name']=remove_emoji($arr['message']['from']['first_name']); // Сохраняем имя автора сообщения в основной массив. Сразу убираем из него эмоджи, чтобы не было проблем с записью данных в БД
        $data['date']=$arr['message']['date']; // Сохраняем дату и время поступления сообщения
        if (isset($arr['message']['chat'])) { // Проверяем было ли сообщение отправлено в общем чате или в лс
            $data['id_chat']=$arr['message']['chat']['id']; // Сохраняем идентификатор чата в основной массив
            $data['title']=remove_emoji($arr['message']['chat']['title']); // Сохраняем название чата в основной массив, убираем из него эмоджи
            $data['type_chat']=$arr['message']['chat']['type']; // Сохраняем тип чата в основной массив
        }
        if (isset($arr['message']['new_chat_member'])) { // Проверяем не является ли поступившее обновление информацией о добавлении пользователя в чат
            if ($arr['message']['new_chat_member']['username']==$bot_name){ // Проверяем не является ли добавленный пользователь данным ботом
                message_to_telegram($intro_text_chat,$data['id_chat']); // Отправляем вступительное сообщение в чат
                exit('ok'); // Завершаем скрипт и говорим Телеграму, что все принято корректно
            }
            else (exit('ok')); // Кого-то добавили в чат, но не этого бота. Завершаем скрипт и говорим Телеграму, что все принято корректно
        }
        if (isset($arr['message']['caption'])) { // Проверяем есть ли вложения в поступившем сообщении
            $data['text']=remove_emoji($arr['message']['caption']); // Если есть, то текст сообщения в данном элементе массива, сохраняем его в основной массив, убираем эмоджи
            $data['message_attachment']=true; // Помечаем, что вложение было обнаружено
        }
        else { // Если вложений нет
            $data['text']=remove_emoji($arr['message']['text']); // Сохраняем текст сообщения из данного элемента массива, не забыв убрать эмоджи
            $data['message_attachment']=0; // Помечаем, что вложения не было
        }
        if ($arr['message']['from']['is_bot']=='') { // Проверяем не было ли сообщение получено от бота
            $data['is_bot']=0; // Результат сохраняем в основной массив
        }
        else ($data['is_bot']=1);// Результат сохраняем в основной массив
        if (isset($arr['message']['entities'])) { // Проверяем есть ли в сообщении упоминания пользователей
            $data['entities']=$arr['message']['entities']; // Сохраняем все упоминания в отдельный массив в основном массиве
        }
        if ($data['type_chat']=='private') { // Если пользователь написал в ЛС
            if ($data['text']=='/start') { // Если полученное сообщение это команда "/start"
                message_to_telegram($intro_text_private,$data['id_chat']); // Написать ему в ответ вводное сообщение
                exit('ok'); // и больше ничего не делать, завершив скрипт, сказав Телеграму, что все данные были приняты корректно
            }
        }
        if ((($data['type_chat']=='group'&&preg_match('/@'.$bot_name.'/',$data['text']) == true)||$data['type_chat']=='private')&&(!isset($arr['message']['reply_to_message']))) { // Если сообщение было адресовано боту (было упоминание его никнейма в чате, либо сообщение было отправлено в ЛС) и это не ответ на сообщение
            save_telegram_input($data); // Данные по входящему сообщению пишем в базу
            if (preg_match('/'.$project_name.'-/',$data['text'])==true) { // Проверяем не комментарий ли это к задаче
                $issueKey=take_issueKey($data['text']); // Выбираем из текста сообщения упоминание задачи
                $update_issue_jira_data=update_issue_jira_id($data['update_id'],$issueKey); // Присваиваем номер задачи комментарию в БД
                if ($update_issue_jira_data['result']==true) { // Если все успешно и запрос, к которому мы могли бы прикрепить комментарий, нашелся
                    $result_check_author_issue=check_author_issue($data['id_user'],$issueKey);
                    if ($result_check_author_issue==0) {
                        message_to_telegram('К сожалению, я не могу добавить Ваш комментарий к запросу '.$issueKey.', поскольку Вы не являетесь его автором. Спасибо за понимание.',$data['id_chat']); // Пишем пользователю данный текст
                        exit('ok'); //Сообщаем Тегерамму, что все приняли и сделали, что нужно
                    }
                    $data['text']=delete_bot_name_from_text($data['text']); // Удаляем из сообщения обращение к боту
                    $data['text']=delete_issueKey_from_text($data['text'],$issueKey); // Удаляем из сообщения упоминание задачи
                    create_comment_jira($data['text'],$update_issue_jira_data['issueId']); // Создаем комментарий в Джире к нужной задаче
                    if ($data['type_chat']=='private') { // Если чат личный
                        message_to_telegram('Ваш комментарий к запросу '.$issueKey.' добавлен.',$data['id_chat']); // Пишем пользователю данный текст
                    }
                    else { // Если чат общий
                        message_to_telegram('@'.$data['username'].', Ваш комментарий к запросу '.$issueKey.' добавлен.',$data['id_chat']); // Пишем пользователю данный текст
                    }
                }
                else { // Если не получилось найти запрос, к которому можно было бы прикрепить данный комментарий
                    if ($data['type_chat']=='private') { // Если чат общий
                        message_to_telegram('К сожалению, мне не удалось добавить Ваш комментарий. Попробуйте еще раз, пожалуйста. В тексте сообщения укажите номер запроса в формате '.$project_name.'-12345. Спасибо за понимание.',$data['id_chat']); // Пишем пользователю данный текст
                    }
                    else { // Если чат личный
                        message_to_telegram('@'.$data['username'].', к сожалению, мне не удалось добавить Ваш комментарий. Попробуйте еще раз, пожалуйста. В тексте сообщения укажите номер запроса в формате '.$project_name.'-12345. Спасибо за понимание.',$data['id_chat']);// Пишем пользователю данный текст
                    }
                }
                exit('ok'); // Сообщаем Телеграму, что все корректно и завершаем работу
            }
            // Если условия не сработали и это обычное сообщение с целью создания задачи
            $data['text']=delete_bot_name_from_text($data['text']); // Удаляем из текста упоминание бота
            $issue_data=createissue($data['text']); // Создаем запрос в Джиру
            if ($data['type_chat']=='private') { // Если чат личный
                $create_issue_text='По Вашему обращению создан запрос в службу поддержки. Номер запроса: "'.$issue_data['issueKey'].'"'; // Подготавливаем следующий текст для отправки клиенту
            }
            else { // Если общий
                $create_issue_text='@'.$data['username'].', по Вашему обращению создан запрос в службу поддержки. Номер запроса: "'.$issue_data['issueKey'].'"'; // Подготавливаем следующий текст для отправки клиенту
            }
            message_to_telegram($create_issue_text,$data['id_chat']); // Отправляем сообщение клиенту
            $non_working_time=check_non_working_time(); // Проверяю не спят ли сотрудники
            $warning_telegram_users=check_warning_telegram_users($data['username']); // Проверяю не от важного ли клиента поступил запрос
            $warning_result = $non_working_time*$warning_telegram_users; // Делаю вывод о необходимости установки варнинга
            save_issue_data($issue_data, $data['id_user'],$data['update_id'],$warning_result); // Сохраняем в базу данные по созданному запросу
        }
        if (isset($arr['message']['reply_to_message'])) { // Если это ответ на какое-то сообщение
            if ($arr['message']['reply_to_message']['from']['username']==$bot_name) { // Если это ответ на сообщение самого бота
                $data['text_reply']=$arr['message']['reply_to_message']['text']; // Записываем текст пересланного сообщения
                if (preg_match('/'.$project_name.'-/',$data['text_reply'])==true) { //Если в тексте пересланного сообщения есть название проекта (начало названия задачи)
                    $data['text_message']=$data['text']; // Сохраняем исходный текст сообщения
                    $data['text']=$data['text_reply'].' => '.$data['text']; // Подготавливаем строку с текстом пересланного и исходного сообщения с "=>" в качестве разделителя для записи в базу
                    save_telegram_input($data); // Пишем данные в базу
                    $issueKey=take_issueKey($data['text_reply']); // Выбираем из текста сообщения упоминание задачи
                    $update_issue_jira_data=update_issue_jira_id($data['update_id'],$issueKey); // Присваиваем номер задачи комментарию в БД
                    if ($update_issue_jira_data['result']==true) { // Если все успешно и запрос, к которому мы могли бы прикрепить комментарий, нашелся
                        $result_check_author_issue=check_author_issue($data['id_user'],$issueKey);
                        if ($result_check_author_issue==0) {
                            message_to_telegram('К сожалению, я не могу добавить Ваш комментарий к запросу '.$issueKey.', поскольку Вы не являетесь его автором. Спасибо за понимание.',$data['id_chat']); // Пишем пользователю данный текст
                            exit('ok'); //Сообщаем Тегерамму, что все приняли и сделали, что нужно
                        }
                        $data['text_message']=delete_bot_name_from_text($data['text_message']); // Удаляем из сообщения обращение к боту, если есть
                        $data['text_message']=delete_issueKey_from_text($data['text_message'],$issueKey); // Удаляем из сообщения упоминание задачи, если есть
                        create_comment_jira($data['text_message'],$update_issue_jira_data['issueId']); // Создаем комментарий в Джире к нужной задаче
                        if ($data['type_chat']=='private') { // Если чат личный
                            message_to_telegram('Ваш комментарий к запросу '.$issueKey.' добавлен.',$data['id_chat']); // Пишем пользователю данный текст
                        }
                        else { // Если чат общий
                            message_to_telegram('@'.$data['username'].', Ваш комментарий к запросу '.$issueKey.' добавлен.',$data['id_chat']); // Пишем пользователю данный текст
                        }
                    }
                    else { // Если не получилось найти запрос, к которому можно было бы прикрепить данный комментарий
                        if ($data['type_chat']=='private') { // Если чат общий
                            message_to_telegram('К сожалению, мне не удалось добавить Ваш комментарий. Попробуйте еще раз, пожалуйста. В тексте сообщения укажите номер запроса в формате '.$project_name.'-12345. Спасибо за понимание.',$data['id_chat']); // Пишем пользователю данный текст
                        }
                        else { // Если чат личный
                            message_to_telegram('@'.$data['username'].', к сожалению, мне не удалось добавить Ваш комментарий. Попробуйте еще раз, пожалуйста. В тексте сообщения укажите номер запроса в формате '.$project_name.'-12345. Спасибо за понимание.',$data['id_chat']);// Пишем пользователю данный текст
                        }
                    }
                    exit('ok'); // Сообщаем Телеграму, что все корректно и завершаем работу
                }
            }
        }
        exit('ok'); // Завершаем работу скрипта, сказав Телеграму, что все данные приняты корректно
    }
    else (exit('ok')); // Завершаем работу скрипта, сказав Телеграму, что все данные приняты корректно (сообщения в теле полученного запроса нет)
}
else (exit('Отсутствует update_id')); // Завершаем работу скрипта, сообщив Телеграму, что мы не увидели никакого обновления в полученных данных, т.к. нет даже идентификатора этого обновления
