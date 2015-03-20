-- phpMyAdmin SQL Dump
-- version 3.3.10
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Час створення: Бер 20 2015 р., 14:10
-- Версія сервера: 5.6.21
-- Версія PHP: 5.6.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- БД: `cms2`
--

-- --------------------------------------------------------

--
-- Структура таблиці `fv_language`
--

CREATE TABLE IF NOT EXISTS `fv_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(2) NOT NULL COMMENT 'alias',
  `local` varchar(5) NOT NULL COMMENT 'local',
  `title` varchar(50) NOT NULL COMMENT 'title',
  `default` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'default',
  `update_time` int(11) NOT NULL COMMENT 'update_time',
  `create_time` int(11) NOT NULL COMMENT 'create_time',
  `published` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'published',
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`alias`),
  KEY `published` (`published`),
  KEY `default` (`default`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп даних таблиці `fv_language`
--

INSERT INTO `fv_language` (`id`, `alias`, `local`, `title`, `default`, `update_time`, `create_time`, `published`) VALUES
(1, 'ua', 'uk-UA', 'Українська', '0', 1408398752, 0, '1'),
(2, 'en', 'en-EN', 'English', '1', 1408314183, 0, '1'),
(3, 'ru', 'ru-RU', 'Русский', '0', 1408313849, 0, '1');
