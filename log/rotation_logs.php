<?php // Архивирование и удаление старых логов
$today_log = new DateTime(); // Сохраняю текущую дату
$yesterday=$today_log->modify('-1 day'); // Отнимаю от нее день
$yesterday=$yesterday->format('d_m_Y'); // Привожу ее в нужный формат
$today_del = new DateTime(); // Сохраняю текущую дату
$today_min_7=$today_del->modify('-7 day'); // Отнимаю от нее 7 дней
$today_min_7=$today_min_7->format('d_m_Y'); // Привожу ее в нужный формат
$dir = ''; // Выбираю директорию
$files = scandir($dir, 1); // Смотрю какие файлы есть в директории
foreach ($files as $file1) { // Перебираю названия файлов
    if ($file1=='jira_log_'.$yesterday.'.txt') { // Если есть лог по Джире за вчера
        $zip = new ZipArchive();
        $zip->open($dir.'/jira_log_' . $yesterday . '.zip', ZipArchive::CREATE); // Создаю архив
        $zip->addFile($dir.'/'.$file1, $file1); // Добавляю в него файл
        $zip->close();
        unlink($dir.'/jira_log_'.$yesterday.'.txt'); // Удаляю заархивированный файл
        break; // Выхожу из цикла
    }
}
foreach ($files as $file2) {
    if ($file2=='telegram_log_'.$yesterday.'.txt') { // Если есть лог по телеграму за вчера
        $zip = new ZipArchive();
        $zip->open($dir.'/telegram_log_' . $yesterday . '.zip', ZipArchive::CREATE); // Создаю архив
        $zip->addFile($dir.'/'.$file2, $file2); // Добавляю в него файл
        $zip->close();
        unlink($dir.'/telegram_log_'.$yesterday.'.txt'); // Удаляю заархивированный файл
        break; // Выхожу из цикла
    }
}
foreach ($files as $file3) {
    if ($file3=='jira_log_'.$today_min_7.'.zip') { // Если есть архивный лог по джире семидневной давности
        unlink($dir.'/jira_log_'.$today_min_7.'.zip'); // Удаляю его
    }
}
foreach ($files as $file4) {
    if ($file4=='telegram_log_'.$today_min_7.'.zip') { // Если есть архивный лог по джире семидневной давности
        unlink($dir.'/telegram_log_'.$today_min_7.'.zip'); // Удаляю его
    }
}
