-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 21 2023 г., 14:11
-- Версия сервера: 8.0.24
-- Версия PHP: 8.0.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `todo_list`
--

-- --------------------------------------------------------

--
-- Структура таблицы `status_list`
--

CREATE TABLE `status_list` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `active_from` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active_to` date NOT NULL DEFAULT '2122-05-18'
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `status_list`
--

INSERT INTO `status_list` (`id`, `name`, `active_from`, `active_to`) VALUES
(1, 'Обычное', '2023-06-19 06:07:17', '2122-05-18'),
(2, 'Важное', '2023-06-19 06:07:17', '2122-05-18'),
(3, 'Выполненное', '2023-06-19 06:07:31', '2122-05-18');

-- --------------------------------------------------------

--
-- Структура таблицы `todo_list`
--

CREATE TABLE `todo_list` (
  `id` int NOT NULL,
  `text` text NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `active_from` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active_to` date NOT NULL DEFAULT '2122-12-21'
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `todo_list`
--

INSERT INTO `todo_list` (`id`, `text`, `completed`, `active_from`, `active_to`) VALUES
(1, 'Обычное дело', 0, '2023-06-19 06:10:19', '2122-12-21'),
(2, 'Важное дело', 0, '2023-06-19 06:10:19', '2122-12-21'),
(3, 'Выполненное дело', 1, '2023-06-19 06:10:39', '2122-12-21');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `login` varchar(255) NOT NULL,
  `active_from` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active_to` date NOT NULL DEFAULT '2122-05-18'
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `active_from`, `active_to`) VALUES
(1, 'admin', '2023-06-19 06:05:51', '2122-05-18');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `status_list`
--
ALTER TABLE `status_list`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `todo_list`
--
ALTER TABLE `todo_list`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `status_list`
--
ALTER TABLE `status_list`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `todo_list`
--
ALTER TABLE `todo_list`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
