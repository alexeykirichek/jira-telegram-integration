-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Янв 10 2022 г., 17:57
-- Версия сервера: 8.0.27
-- Версия PHP: 7.3.31-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `jira-telegram-integration`
--

-- --------------------------------------------------------

--
-- Структура таблицы `jira_created_issues`
--

CREATE TABLE `jira_created_issues` (
  `id_created_issue` int NOT NULL,
  `issueId` int NOT NULL,
  `issueKey` text NOT NULL,
  `issueKeynum` int NOT NULL,
  `create_time` bigint NOT NULL,
  `subject` text NOT NULL,
  `description` text NOT NULL,
  `author_telegram_id` bigint NOT NULL,
  `attachment` text NOT NULL,
  `currentStatus` text NOT NULL,
  `update_time` bigint NOT NULL,
  `link` text NOT NULL,
  `warning` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `jira_input_changelog_items`
--

CREATE TABLE `jira_input_changelog_items` (
  `id_input_changelog_item` int NOT NULL,
  `id_changelog` int NOT NULL,
  `field_item` text NOT NULL,
  `fieldtype_item` text NOT NULL,
  `fieldId_item` text NOT NULL,
  `from_item` int NOT NULL,
  `to_item` int NOT NULL,
  `fromString` text NOT NULL,
  `toString` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `jira_input_comments`
--

CREATE TABLE `jira_input_comments` (
  `jira_input_id` int NOT NULL,
  `id_issue` int NOT NULL,
  `timestamp` bigint NOT NULL,
  `webhookEvent` text NOT NULL,
  `user_input` text NOT NULL,
  `comment_id` int NOT NULL,
  `comment_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `jira_input_updates`
--

CREATE TABLE `jira_input_updates` (
  `jira_input_update_id` int NOT NULL,
  `id_issue` int NOT NULL,
  `timestamp` bigint NOT NULL,
  `webhookEvent` text NOT NULL,
  `user_update` text NOT NULL,
  `id_changelog` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Структура таблицы `telegram_input`
--

CREATE TABLE `telegram_input` (
  `telegram_input_id` int NOT NULL,
  `update_id` int NOT NULL,
  `message_id` int NOT NULL,
  `message_text` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `message_attachment` tinyint(1) NOT NULL,
  `date` int NOT NULL,
  `user_id` bigint NOT NULL,
  `username` text NOT NULL,
  `is_bot` tinyint(1) NOT NULL,
  `first_name` text NOT NULL,
  `id_chat` bigint DEFAULT NULL,
  `name_chat` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `issue_jira_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `jira_created_issues`
--
ALTER TABLE `jira_created_issues`
  ADD PRIMARY KEY (`id_created_issue`),
  ADD KEY `issueId` (`issueId`) USING BTREE,
  ADD KEY `author_telegram_id` (`author_telegram_id`),
  ADD KEY `update_time` (`update_time`),
  ADD KEY `warning` (`warning`),
  ADD KEY `issueKeynum` (`issueKeynum`);

--
-- Индексы таблицы `jira_input_changelog_items`
--
ALTER TABLE `jira_input_changelog_items`
  ADD PRIMARY KEY (`id_input_changelog_item`),
  ADD KEY `id_changelog` (`id_changelog`);

--
-- Индексы таблицы `jira_input_comments`
--
ALTER TABLE `jira_input_comments`
  ADD PRIMARY KEY (`jira_input_id`),
  ADD KEY `id_issue` (`id_issue`) USING BTREE,
  ADD KEY `comment_id` (`comment_id`);

--
-- Индексы таблицы `jira_input_updates`
--
ALTER TABLE `jira_input_updates`
  ADD PRIMARY KEY (`jira_input_update_id`),
  ADD KEY `id_issue` (`id_issue`),
  ADD KEY `id_changelog` (`id_changelog`);

--
-- Индексы таблицы `telegram_input`
--
ALTER TABLE `telegram_input`
  ADD PRIMARY KEY (`telegram_input_id`),
  ADD KEY `request_jira_id` (`issue_jira_id`),
  ADD KEY `id_chat` (`id_chat`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `update_id` (`update_id`),
  ADD KEY `message_id` (`message_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `jira_created_issues`
--
ALTER TABLE `jira_created_issues`
  MODIFY `id_created_issue` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `jira_input_changelog_items`
--
ALTER TABLE `jira_input_changelog_items`
  MODIFY `id_input_changelog_item` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `jira_input_comments`
--
ALTER TABLE `jira_input_comments`
  MODIFY `jira_input_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `jira_input_updates`
--
ALTER TABLE `jira_input_updates`
  MODIFY `jira_input_update_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `telegram_input`
--
ALTER TABLE `telegram_input`
  MODIFY `telegram_input_id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
