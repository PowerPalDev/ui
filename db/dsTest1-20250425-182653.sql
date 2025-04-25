-- AdminNeo 5.0-dev MySQL 11.6.2-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE DATABASE `dsTest1` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `dsTest1`;

DROP TABLE IF EXISTS `channel`;
CREATE TABLE `channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `deviceId` varchar(255) NOT NULL,
  `channel` int(11) NOT NULL COMMENT 'this is the primary channel when turned on, the blue',
  `name` varchar(255) NOT NULL,
  `type` enum('light','thermostat','plug') CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `greenChannel` int(11) NOT NULL COMMENT 'when excess of green energy need to be signaled',
  `violetChannel` int(11) NOT NULL COMMENT 'energy from battery ',
  `lastUpdate` int(11) NOT NULL,
  `color` enum('blue','green','orange','violet') NOT NULL,
  `state` int(11) NOT NULL,
  `isDimmable` tinyint(4) NOT NULL DEFAULT 0,
  `duty` int(11) NOT NULL,
  `power` float NOT NULL,
  `voltage` float NOT NULL,
  `isThermostat` tinyint(4) NOT NULL DEFAULT 0,
  `currentTemp` float NOT NULL,
  `targetTemp` float NOT NULL,
  `note` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `channel` (`id`, `userId`, `deviceId`, `channel`, `name`, `type`, `greenChannel`, `violetChannel`, `lastUpdate`, `color`, `state`, `isDimmable`, `duty`, `power`, `voltage`, `isThermostat`, `currentTemp`, `targetTemp`, `note`) VALUES
(1,	0,	'AC1518D6640C',	33,	'Street Lighting',	'light',	0,	0,	1745605156,	'green',	1,	1,	29,	3.1,	5,	0,	0,	0,	'they do not have other color, so if on will always be same color'),
(2,	0,	'AC1518D6640C',	25,	'Heat Pump',	'light',	26,	27,	1745605375,	'green',	1,	0,	100,	600,	5,	1,	22.2474,	22,	''),
(3,	0,	'AC1518D6640C',	23,	'Kettle',	'plug',	24,	0,	1745605051,	'violet',	0,	0,	0,	0,	5,	0,	0,	0,	'');

DROP TABLE IF EXISTS `device`;
CREATE TABLE `device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `mac` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- 2025-04-25 18:26:53 UTC
