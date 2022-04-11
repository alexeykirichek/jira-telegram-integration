# Integration of Jira and Telegram

**Русская версия README доступна ниже**

This project is a software that allows you to create requests in Jira Service Desk/Jira Service Management and receive feedback on them without using the standard Jira interface. This allows customers not to leave their comfort zone and create new support requests simply by writing to the Telegram bot. In turn, support staff can process requests in a single window without being distracted by messengers.

**Disclaimer**: The project code is as simple as possible. The project is not positioned as a serious development. I understand that much can be done more correctly, better, more logically, etc. It was done at the training stage and with minimal time costs.

**Example of work:**

![](https://github.com/alexeykirichek/jira-telegram-integration/blob/main/blocks/tgbotexample.png "Example of work")

**[The scheme of the project](https://viewer.diagrams.net/?tags=%7B%7D&highlight=0000ff&edit=_blank&layers=1&nav=1&title=%D0%A2%D0%B5%D0%BB%D0%B5%D0%B3%D1%80%D0%B0%D0%BC%D0%BC%20%D0%B1%D0%BE%D1%82%20github.drawio.png#R7VxZk6M2EP41rkoedgpJYODR42NTlaQyVZvUHm%2BMzdpksXFh5vD%2B%2BgiQROvAYBtjT3b3gYW20NXqr79uiRmQ8fr1fRpsV38mizAeYGvxOiCTAcYI%2B0P6Xy7ZlxIf2aVgmUYLVqgSfIi%2Bh0xoMelTtAh3UsEsSeIs2srCebLZhPNMkgVpmrzIxb4msdzqNliGmuDDPIh16cdoka1KqYfdSv5bGC1XvGU09Mtf1gEvzEayWwWL5AWIyHRAxmmSZOXd%2BnUcxvnk8Xkp35vV%2FCo6loabrM0LI%2Fvvj399eUXz4bfH6J%2FHBxI%2BvH%2FHOvscxE9swKyz2Z7PQLigE8IekzRbJctkE8TTSnqfJk%2BbRZg3Y9GnqswfSbKlQkSF%2F4ZZtmfaDZ6yhIpW2Tpmv%2BpDYaPbJU%2FpPDzQf74kgnQZZgfKeWW5fCygATZR78NkHWbpnhZIwzjIomdZ%2BQFbQ0tRrppmesNm%2BohZR%2Fqs06nz6RUNPCe%2Fv58WV7uQzIqrX0gcdqXKroqhokApsbTCk%2BLqyS%2BOihtaEoOS9%2BCeyHXS65i%2Fi3jT5ev0SrRVI6%2BJl1WUhR%2B2QaHLF4oULfX%2FHKZZ%2BHpQY%2BxXm7%2Byl%2FHjpTJaNGRYtAIG61oX0jFx3rhpeS1Ni9g3ZVueYdqHMR3A%2FSJ6prfL%2FDZfwCO4jB1ttU8OWRCr8TEVFTIJ7TJsxtwytaTKUhG%2FihYw%2BxXzAszsbK13k6IArNOr3qrgAYKEERgKjMHjcmoxnAUMMGIqzREsTsUuGJIYQM2cBOvc%2FjePuy2fEMxa9Ytu%2B7ZmKQ0AIptJF3CCfBlOsAFPOHb0gido%2BMbxhBO%2FRkBBzk0BCn7rQN564vFtITnvt0qTZgCHpgAxIYaPBIYegWjlzYz9xB5d8K4RgUUvxpwKQQSeAYxFvGtmxrRK1o9Pu57Yki3BGxma6JIB3oSwezNzr2FVdArT%2FSf48Dmv7M7hj5NXVnn5tG9yMc3W6FzNGotXR2ka7EGBbRJtsh2o%2BSEXVEvFtV1pqTi%2Bpei6rLHSvOjaGaZvwFxm%2BtDchNFjxegBqdC5kCiP71Sbh0GQVMvp1KQDax16srXapuAGG4KbixkrctuybN8BKrgHHPUgIJ9PsRXlG2G4IzYP%2FZGn%2BRVPjrLFveDuRfSBeeHWLWM4exZg9WPO5%2BU45qiKlVgIRkqunIYw16qYCF3nmWwVQRwtN%2FR%2BTq0iTKkgt4ZoHsQj9sM6WixKiA930ffgsagqR16GV7Re537gTPK6KKrvSoDPq95lafItHCdxQuudbJJNXsvXKI4VUQeG6biKG3UMhokMbtS7mGH6V%2FWiwHF%2Blvxm5160bXaCx20de1HNTXq%2Bd%2BeYUZpXUvaVvafouQufach85GkOT4rJJQASPJcKbUZLq59KojqUcU1JG1j8LXqdggRDWd43kWgErh5oCJUpBQ1SjRRfZEUPNFrv1CtfXjyOzs%2BBlrzjyjlQjxBlDZqIAukza4GJgSi8peCZ40cz0OCugea8ibe6YGg1JtQJN5PNMO%2BhhVryiT5DZY%2F7dA7rlm5Tvtuji%2BcLTcV5rNFQAq5OxaxhSCThMwbaFi4AxFvVnlMVQoHKkUlumXPV%2FQdRHlEdtGNKemADOl4sjCK3gY790zfcNiVpD%2Fuhb44lM%2Fmh6hEvTN6wOdc5gqkLtyYHqezsHrUlXEaKvRujbcvTfX1TxKbtFbO%2F9MAUWlJM%2FINHw0hRqmHPzDHoFF9Mp7cbEY3qsol9xwzIkumN4zi6JZq0drmQoc2ZpM1ilB%2Fuym0mDna7aF4s9SDNdDF0dK9R9gncg1w%2Ffaq8XP7QgZNre%2BiowT647EwXJ%2FZ4uIuzHLmKckCai9Mqsm1lyfj9%2BkpiOD5VA9WQqs60YN64awBTFUoaQvBmQaNFMlTJwbo8wimvM%2BY3ygTH%2BelmGJMJsIGJ2cPEwJL3LbGW94Cv%2B63GenZEWNWHwIEcAjTEp%2B%2FQcR1PjlQw0NmBDZ22ff9B%2FDh2FD9ODOTMPwBU3cdJ190b7i0sIq2PyNzWSQ1iiF6akk3i7KiwViX3hNVchRrc1J1xFTUohzOULEh7rO82J9X9WTUeJDN7xQbejZBhe1hwv%2B4XhJl5%2BxOgBZ3V%2FV8B1UEyYSIGQLX7DIxsE4Oqp9hsJozQOOjm2Ex3p4z7IdMKl9aioVO5tFbRhbm0bUgmH7USajV6m4pyVchrq6gmhV9aT20yxaaguI2qmrOwbU%2Fkvi2dChyuq%2BjSSq054MpTvD%2BKfyS2vDGDDcdoTLvWl%2FOPpuNt%2FQcgtVPbGEjYbQ%2BZdr%2B%2Fct7E15z7tLUEBszgeKZoYqSR%2F7EcLygpYLhx6leVtI0UbuIjOIxlQxK7WFfbVrHf%2Bgem3ECaLem2PjG1DftZ%2Bb5D3bFO%2BNGZdfAjiAPL3%2FrlvAzlOTbr8KSBQAAPdH2sNV2X6VQyD%2FpGkRgOAnJ4gpeVvCue7btftQXfe4IAyzzHCAtDr09YqMkPnJCvF8qSv0Bs%2FuZHfKvTPhF0fh79ZNO69hJSNuzdq3sWzhFb5Bwhe5hpBsx3F8io9Z4F4h%2BOnHI%2Bo8MNJiR%2Fvk%2BamjYB%2FhFbNeJ7mQY%2BVmcC5%2BM8%2FLDhWG525PcPTVt7tcfgBPbwEyInprEPag2PZfyDk6pDHZjsM%2FfqlD05XQETefQHku8TUNtQe4UciZLKWj5hcOTn6u4SV1Q%2B1LRsDHOATZ0ZgXHjqhtnLuoT%2FlxC0zKXpuEogiHGbYMyFmjC6m70v08%2FP7yz6L9jXrJbmsrtBOYd%2FcmOKUAriPYQci0e9Iyl4TYS0mNWyt3PQwsKQXXlEIfop9hwV9%2Bz08fqr3aVueDqb5%2BR6X8%3D "Block diagram") on draw.io**.

## Installation Instructions:

Download the sources:

`git clone https://github.com/alexeykirichek/jira-telegram-integration.git`

Configure your web server so that Telegram and Jira can use a web hook to transfer data to files telegram.php and jira.php accordingly. Since Telegram transmits data only to a server with an ssl certificate, it is necessary to set up a domain/subdomain and generate and certify/buy a certificate.

Create a database:

`mysql -u root -p`

``CREATE DATABASE `jira-telegram-integration` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;``

Import the structure (file "jira-telegram-integration.sql" in the root of the project):

``use `jira-telegram-integration` ``

`source path/to/jira-telegram-integration.sql;` ("path/to" replace with a real path)

Create a user and configure his rights to access this database:

``CREATE USER 'jira-telegram-integration'@'localhost' IDENTIFIED WITH mysql_native_password BY '***';GRANT USAGE ON *.* TO 'jira-telegram-integration'@'localhost';``

``ALTER USER 'jira-telegram-integration'@'localhost' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;``

``GRANT ALL PRIVILEGES ON `jira-telegram-integration`.* TO 'jira-telegram-integration'@'localhost';``

Specify values for variables from the "Database" section in the file /blocks/variables.php . For this example:

``$db_login = 'jira-telegram-integration'; // Login to connect to the database``

``$db_pass = '***'; // Pass to connect to the database``

``$db_name = 'jira-telegram-integration'; // Name database``

Go to the Atlassian project and copy the name of the organization from the link "https://<<organization>>.atlassian.net /`. For example for https://google.atlassian.net / in the $organization variable, you need to enter the value 'google'.

Register as a customer on your support portal. Specify the login and name of the registered user in the variables $sd_login and $display_name_jira. Get a token for the user. Instructions for obtaining a token from the official Atlassian documentation [тут](https://support.atlassian.com/atlassian-account/docs/manage-api-tokens-for-your-atlassian-account/). Specify the received token in the $sd_token variable.

Open the profile of the created user and copy his ID from the address bar in the format "5da960e1e813f00c2353d696". Specify it in the $JiraRequestParticipant variable. The $project_name variable is not difficult to fill in. It indicates the name of the project. You can find it out from any created task, for example, if the access key is "VJQD-658", then you need to specify "VJQD", this will be the name of the project in Jira.

To receive updates on the created tasks from Jira, you need to configure the appropriate web hook. Documentation [here](https://developer.atlassian.com/server/jira/platform/webhooks /). Specify the name of the web hook, the url before the file jira.php, specify JQL only for sending events: "project = <<organization>> ORDER BY created DESC` and select events - task created and updated. That will be enough.

Next, you need to create a Telegram bot. Documentation [here](https://tlgrm.ru/docs/bots#create-a-new-bot ). In the $bot_name variable, specify the bot's username without the @ symbol, and in $bot_token, specify the bot's API token. Make sure that the welcome messages specified in the variables $intro_text_chat and $intro_text_private are suitable for your purposes. The bot will send this text when it is added to the general chat, or when it receives a message /start (for personal dialogues).

For the software to work correctly, you need to give the bot access to all chat messages (@BotFather >> Bot Settings >> Group Privacy >> Disabled) and configure sending notifications about incoming updates to the web hook handler (file telegram.php ). To configure the web hook, adjust the template link: https://api.telegram.org/botТОКЕН/setWebhook ?url=https://ВАШ_ДОМЕН/ПУТЬ/telegram.php and click on it in the browser. You should get the following response from the server: {"ok":true,"result":true,"description":"Webhook was set"}. Important - there must be a "bot" before the bot token, do not erase these three letters when inserting the token.

All data received from Jira and Telegram will be recorded in log files. The paths to them can be specified in the variables $jira_log_path and $telegram_log_path. By default, all log files are saved to the log folder in the project. Since this path will be used by the web server, it can be specified relative to the project folder. In the file rotation_logs.php in the $dir variable, you must specify the full path to the folder with the log, since it will run on a schedule without the participation of a web server. For example: /var/www/jira-telegram-integration/log. Don't forget to make sure that the web server has write permissions to the logs folder. By default, they will not be and you will need to make the appropriate settings. If you plan to leave a folder with logs in the root of the project, then configure the web server so that it is not shared. The easiest way to do this is to turn it on .htaccess, which is already located in this directory.

Set up automatic daily file launch rotation_logs.php . To do this, for example, add a crontab entry in the format `0 1 * * * root /usr/bin/php /var/www/jira-telegram-integration/log/rotation_logs.php `. Don't forget to change the file path to the correct one. This script archives log files for the previous day and deletes log files older than seven days.

This software allows you to make a note in the database for each request received outside of working hours / on weekends / on holidays from certain telegram users. To use this functionality, it is necessary that:
- the time zone was correctly configured on the server;
- the variables $work_time_begin and $work_time_end were correctly filled in according to the start and end time of your support service;
- in the file warning_telegram_users.php the usernames of users without @ were indicated, for which it is necessary to make a note.

Example:
``$warning_telegram_users=array(
    'user1',
    'user2',
    'user3'
);``
    
After the task starts working (its status changes), the mark will be removed. By itself, it represents a number (1/0) in the table with tasks in the "warning" column. There are different ways to use this tag. The easiest way is by calling a file through the browser monitoring.php . It returns the number of marked tasks. This value can be saved using zabbix and set up a notification about the presence of marks in it. However, as practice has shown, this method is unreliable and, in principle, not optimal - periodically the web server does not respond. A more reasonable option would be to install Zabbix agent on the server and make a UserParameter with a connection to the database and executing an sql query that counts the marks (you can see it in the file monitoring.php ).

After making all the settings, the system should work. If you have any problems, contact me via e-mail alexkirichek@yandex.ru, or better describe the problem in the Issues tab. I'll help you.

# Интеграция Jira и Telegram

**The English version of the README is available above**

Данный проект представляет собой ПО, позволяющее создавать запросы в Jira Service Desk/Jira Service Management и получать по ним обратную связь не используя стандартный интерфейс Jira. Это дает возможность клиентам не выходить из зоны комфорта и создавать новые обращения в службу поддержки просто написав Телеграм боту. В свою очередь сотрудники службы поддержки могут обрабатывать запросы в едином окне не отвлекаясь на мессенджеры.

**Дисклеймер**: код проекта максимально простой. Проект не позиционируется как серьезная разработка. Я понимаю, что многое можно сделать правильнее, лучше, логичнее и т.д. Делалось на этапе обучения и с минимальными временными затратами.

**Пример работы:**

![](https://github.com/alexeykirichek/jira-telegram-integration/blob/main/blocks/tgbotexample.png "Пример работы")

**[Схема работы проекта](https://viewer.diagrams.net/?tags=%7B%7D&highlight=0000ff&edit=_blank&layers=1&nav=1&title=%D0%A2%D0%B5%D0%BB%D0%B5%D0%B3%D1%80%D0%B0%D0%BC%D0%BC%20%D0%B1%D0%BE%D1%82%20github.drawio.png#R7VxZk6M2EP41rkoedgpJYODR42NTlaQyVZvUHm%2BMzdpksXFh5vD%2B%2BgiQROvAYBtjT3b3gYW20NXqr79uiRmQ8fr1fRpsV38mizAeYGvxOiCTAcYI%2B0P6Xy7ZlxIf2aVgmUYLVqgSfIi%2Bh0xoMelTtAh3UsEsSeIs2srCebLZhPNMkgVpmrzIxb4msdzqNliGmuDDPIh16cdoka1KqYfdSv5bGC1XvGU09Mtf1gEvzEayWwWL5AWIyHRAxmmSZOXd%2BnUcxvnk8Xkp35vV%2FCo6loabrM0LI%2Fvvj399eUXz4bfH6J%2FHBxI%2BvH%2FHOvscxE9swKyz2Z7PQLigE8IekzRbJctkE8TTSnqfJk%2BbRZg3Y9GnqswfSbKlQkSF%2F4ZZtmfaDZ6yhIpW2Tpmv%2BpDYaPbJU%2FpPDzQf74kgnQZZgfKeWW5fCygATZR78NkHWbpnhZIwzjIomdZ%2BQFbQ0tRrppmesNm%2BohZR%2Fqs06nz6RUNPCe%2Fv58WV7uQzIqrX0gcdqXKroqhokApsbTCk%2BLqyS%2BOihtaEoOS9%2BCeyHXS65i%2Fi3jT5ev0SrRVI6%2BJl1WUhR%2B2QaHLF4oULfX%2FHKZZ%2BHpQY%2BxXm7%2Byl%2FHjpTJaNGRYtAIG61oX0jFx3rhpeS1Ni9g3ZVueYdqHMR3A%2FSJ6prfL%2FDZfwCO4jB1ttU8OWRCr8TEVFTIJ7TJsxtwytaTKUhG%2FihYw%2BxXzAszsbK13k6IArNOr3qrgAYKEERgKjMHjcmoxnAUMMGIqzREsTsUuGJIYQM2cBOvc%2FjePuy2fEMxa9Ytu%2B7ZmKQ0AIptJF3CCfBlOsAFPOHb0gido%2BMbxhBO%2FRkBBzk0BCn7rQN564vFtITnvt0qTZgCHpgAxIYaPBIYegWjlzYz9xB5d8K4RgUUvxpwKQQSeAYxFvGtmxrRK1o9Pu57Yki3BGxma6JIB3oSwezNzr2FVdArT%2FSf48Dmv7M7hj5NXVnn5tG9yMc3W6FzNGotXR2ka7EGBbRJtsh2o%2BSEXVEvFtV1pqTi%2Bpei6rLHSvOjaGaZvwFxm%2BtDchNFjxegBqdC5kCiP71Sbh0GQVMvp1KQDax16srXapuAGG4KbixkrctuybN8BKrgHHPUgIJ9PsRXlG2G4IzYP%2FZGn%2BRVPjrLFveDuRfSBeeHWLWM4exZg9WPO5%2BU45qiKlVgIRkqunIYw16qYCF3nmWwVQRwtN%2FR%2BTq0iTKkgt4ZoHsQj9sM6WixKiA930ffgsagqR16GV7Re537gTPK6KKrvSoDPq95lafItHCdxQuudbJJNXsvXKI4VUQeG6biKG3UMhokMbtS7mGH6V%2FWiwHF%2Blvxm5160bXaCx20de1HNTXq%2Bd%2BeYUZpXUvaVvafouQufach85GkOT4rJJQASPJcKbUZLq59KojqUcU1JG1j8LXqdggRDWd43kWgErh5oCJUpBQ1SjRRfZEUPNFrv1CtfXjyOzs%2BBlrzjyjlQjxBlDZqIAukza4GJgSi8peCZ40cz0OCugea8ibe6YGg1JtQJN5PNMO%2BhhVryiT5DZY%2F7dA7rlm5Tvtuji%2BcLTcV5rNFQAq5OxaxhSCThMwbaFi4AxFvVnlMVQoHKkUlumXPV%2FQdRHlEdtGNKemADOl4sjCK3gY790zfcNiVpD%2Fuhb44lM%2Fmh6hEvTN6wOdc5gqkLtyYHqezsHrUlXEaKvRujbcvTfX1TxKbtFbO%2F9MAUWlJM%2FINHw0hRqmHPzDHoFF9Mp7cbEY3qsol9xwzIkumN4zi6JZq0drmQoc2ZpM1ilB%2Fuym0mDna7aF4s9SDNdDF0dK9R9gncg1w%2Ffaq8XP7QgZNre%2BiowT647EwXJ%2FZ4uIuzHLmKckCai9Mqsm1lyfj9%2BkpiOD5VA9WQqs60YN64awBTFUoaQvBmQaNFMlTJwbo8wimvM%2BY3ygTH%2BelmGJMJsIGJ2cPEwJL3LbGW94Cv%2B63GenZEWNWHwIEcAjTEp%2B%2FQcR1PjlQw0NmBDZ22ff9B%2FDh2FD9ODOTMPwBU3cdJ190b7i0sIq2PyNzWSQ1iiF6akk3i7KiwViX3hNVchRrc1J1xFTUohzOULEh7rO82J9X9WTUeJDN7xQbejZBhe1hwv%2B4XhJl5%2BxOgBZ3V%2FV8B1UEyYSIGQLX7DIxsE4Oqp9hsJozQOOjm2Ex3p4z7IdMKl9aioVO5tFbRhbm0bUgmH7USajV6m4pyVchrq6gmhV9aT20yxaaguI2qmrOwbU%2Fkvi2dChyuq%2BjSSq054MpTvD%2BKfyS2vDGDDcdoTLvWl%2FOPpuNt%2FQcgtVPbGEjYbQ%2BZdr%2B%2Fct7E15z7tLUEBszgeKZoYqSR%2F7EcLygpYLhx6leVtI0UbuIjOIxlQxK7WFfbVrHf%2Bgem3ECaLem2PjG1DftZ%2Bb5D3bFO%2BNGZdfAjiAPL3%2FrlvAzlOTbr8KSBQAAPdH2sNV2X6VQyD%2FpGkRgOAnJ4gpeVvCue7btftQXfe4IAyzzHCAtDr09YqMkPnJCvF8qSv0Bs%2FuZHfKvTPhF0fh79ZNO69hJSNuzdq3sWzhFb5Bwhe5hpBsx3F8io9Z4F4h%2BOnHI%2Bo8MNJiR%2Fvk%2BamjYB%2FhFbNeJ7mQY%2BVmcC5%2BM8%2FLDhWG525PcPTVt7tcfgBPbwEyInprEPag2PZfyDk6pDHZjsM%2FfqlD05XQETefQHku8TUNtQe4UciZLKWj5hcOTn6u4SV1Q%2B1LRsDHOATZ0ZgXHjqhtnLuoT%2FlxC0zKXpuEogiHGbYMyFmjC6m70v08%2FP7yz6L9jXrJbmsrtBOYd%2FcmOKUAriPYQci0e9Iyl4TYS0mNWyt3PQwsKQXXlEIfop9hwV9%2Bz08fqr3aVueDqb5%2BR6X8%3D "Блок схема") на draw.io**.

## Инструкция по инсталляции:

Скачайте исходники:

`git clone https://github.com/alexeykirichek/jira-telegram-integration.git`

Настройте Ваш веб сервер таким образом, чтобы Телеграм и Jira могли с помощью веб хука передать данные в файлы telegram.php и jira.php соответсвенно. Поскольку Telegram передает данные только на сервер с ssl сертификатом, необходимо настроить домен/поддомен и сгенерировать и заверить/купить сертификат.

Создайте базу данных:

`mysql -u root -p`

``CREATE DATABASE `jira-telegram-integration` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;``

Импортируйте структуру (файл "jira-telegram-integration.sql" в корне проекта):

``use `jira-telegram-integration` ``

`source path/to/jira-telegram-integration.sql;` ("path/to" замените на реальный путь)

Создайте пользователя и настройте ему права для доступа к данной БД:

``CREATE USER 'jira-telegram-integration'@'localhost' IDENTIFIED WITH mysql_native_password BY '***';GRANT USAGE ON *.* TO 'jira-telegram-integration'@'localhost';``

``ALTER USER 'jira-telegram-integration'@'localhost' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;``

``GRANT ALL PRIVILEGES ON `jira-telegram-integration`.* TO 'jira-telegram-integration'@'localhost';``

Укажите значения для переменных из раздела "База данных" в файле /blocks/variables.php. Для данного примера:

``$db_login = 'jira-telegram-integration'; // Логин для подключения к БД``

``$db_pass = '***'; // Пароль для подключения к БД``

``$db_name = 'jira-telegram-integration'; // Название БД``

Перейдите в проект на Atlassian и скопируйте название организации из ссылки ``https://<<organization>>.atlassian.net/``. К примеру для https://google.atlassian.net/ в переменную $organization нужно вписать значение 'google'.

Зарегистрируйтесь в роли клиента на Вашем портале поддержки. Укажите логин и имя зарегистрированного пользователя в переменных $sd_login и $display_name_jira. Получите токен для пользователя. Инструкция по получению токена из официальной документации Atlassian [тут](https://support.atlassian.com/atlassian-account/docs/manage-api-tokens-for-your-atlassian-account/). Укажите полученный токен в переменной $sd_token.

Откройте профиль созданного пользователя и скопируйте из адресной строки его ID в формате "5da960e1e813f00c2353d696". Укажите его в переменной $JiraRequestParticipant. Переменную $project_name заполнить не сложно. В ней указывается название проекта. Узнать его можно из любой созданной задачи, к примеру, если ключ обращения "VJQD-658", то необходимо указать "VJQD", это и будет название проекта в Jira.

Для получения обновлений по созданным задачам от Jira, необходимо настроить соответствующий веб хук. Документация [тут](https://developer.atlassian.com/server/jira/platform/webhooks/). Укажите название веб хука, url до файла jira.php, укажите JQL только для отправки событий: ``project = <<organization>> ORDER BY created DESC`` и выберите события - создана и обновлена задача. Этого будет достаточно.

Далее необходимо создать Телеграм бота. Документация [тут](https://tlgrm.ru/docs/bots#create-a-new-bot). В переменной $bot_name укажите юзернейм бота без символа @ , а в $bot_token АПИ токен бота. Убедитесь, что приветственные сообщения, указанные в переменных $intro_text_chat и $intro_text_private подходят для ваших целей. Этот текст бот будет отправлять при добавлении в общий чат, либо при получении сообщения /start (для личных диалогов).

Для корректной работы ПО, необходимо дать доступ боту ко всем сообщениям в чатах (@BotFather >> Настройки ботов >> Group Privacy >> Disabled) и настроить отправку уведомлений о поступающих обновлениях на обработчик веб хуков (файл telegram.php). Для настройки веб хука скорректируйте ссылку-шаблон: https://api.telegram.org/botТОКЕН/setWebhook?url=https://ВАШ_ДОМЕН/ПУТЬ/telegram.php и перейдите по ней в браузере. Вы должны получить следующий ответ от сервера: {"ok":true,"result":true,"description":"Webhook was set"}. Важно - перед токеном бота обязательно должно быть "bot", не сотрите эти три буквы при вставке токена.

Все данные, полученные от Jira и Telegram будут записываться в лог файлы. Пути к ним можно указать в переменных $jira_log_path и $telegram_log_path. По умолчанию все лог файлы сохраняются в папку log в проекте. Поскольку этот путь будет использоваться веб сервером, он может быть указан относительно папки проекта. В файле rotation_logs.php в переменной $dir необходимо указать полный путь к папке с логом, поскольку он будет запускаться по расписанию без участия веб сервера. К примеру: /var/www/jira-telegram-integration/log. Не забудьте убедиться, что у веб сервера есть права на запись в папку с логами. По умолчанию их не будет и потребуется сделать соответствующие настройки. Если Вы планируете оставить папку с логами в корне проекта, то настройте веб сервер таким образом, чтобы она не находилась в общем доступе. Самый простой способ это сделать - включить .htaccess, который уже располагается в данной директории.

Настройте автоматический ежедневный запуск файла rotation_logs.php. Для этого, к примеру, добавьте в crontab запись в формате ``0  1    * * *   root    /usr/bin/php /var/www/jira-telegram-integration/log/rotation_logs.php``. Не забудьте изменить путь к файлу на корректный. Данный скрипт архивирует лог файлы за предыдущий день и удаляет лог файлы старше семи дней.

Данное ПО позволяет делать пометку в БД для каждого запроса, поступившего не в рабочее время/на выходных/в праздники от определенных телеграм пользователей. Для использования этого функционала необходимо, чтобы:
- на сервере был корректно настроен часовой пояс;
- были корректно заполнены переменные $work_time_begin и $work_time_end в соответствии с временем начала и окончания рабочего времени Вашей службы поддержки;
- в файле warning_telegram_users.php были указаны юзернеймы пользователей без @, по которым необходимо делать пометку.

Пример заполнения:
``$warning_telegram_users=array(
    'user1',
    'user2',
    'user3'
);``

После того, как по задаче начнется работа (сменится ее статус), пометка будет снята. Сама по себе она представляет число (1/0) в таблице с задачами в столбце "warning". Использовать данную пометку можно разными способами. Самый простой - путем вызова через браузер файла monitoring.php. Он возвращает число задач с пометкой. Данное значение можно сохранить используя zabbix и в нем же настроить уведомление о наличии пометок. Однако, как показала практика, этот способ не надежный да и в принципе не оптимальный - периодически веб сервер не отвечает. Более разумным вариантом будет установить на сервер Zabbix agent и сделать UserParameter с подключеним к базе данных и выполнением sql запроса, подсчитывающего пометки (его можно посмотреть в файле monitoring.php).

После выполнения всех настроек, система должна работать. Если у Вас возникли проблемы, свяжитесь со мной через e-mail alexkirichek@yandex.ru, а лучше опишите проблему во вкладке Issues. Я помогу Вам.

