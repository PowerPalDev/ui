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
  `deviceId` varchar(255) NOT NULL,
  `channel` int(11) NOT NULL COMMENT 'this is the primary channel when turned on, the blue',
  `name` varchar(255) NOT NULL,
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

INSERT INTO `channel` (`id`, `deviceId`, `channel`, `name`, `greenChannel`, `violetChannel`, `lastUpdate`, `color`, `state`, `isDimmable`, `duty`, `power`, `voltage`, `isThermostat`, `currentTemp`, `targetTemp`, `note`) VALUES
(1,	'AC1518D6640C',	33,	'Street Lighting',	0,	0,	1744423204,	'violet',	1,	1,	19,	3.1,	5,	0,	0,	0,	'they do not have other color, so if on will always be same color'),
(2,	'AC1518D6640C',	25,	'Heat Pump',	26,	27,	1744423251,	'violet',	1,	0,	100,	600,	5,	1,	25.7774,	25.5,	'');

-- 2025-04-12 02:07:26 UTC
