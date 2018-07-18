SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `sources_kalendar_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`link` text COLLATE utf8mb4_bin NOT NULL,
	`name` text COLLATE utf8mb4_bin DEFAULT NULL,
	`owners` VARCHAR(8000) COLLATE utf8mb4_bin DEFAULT ',',
	`active` int(11) NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
