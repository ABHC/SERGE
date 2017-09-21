SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `price_table_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`price` int(11) NOT NULL,
	`currency` text COLLATE utf8mb4_bin NOT NULL,
	`type` text COLLATE utf8mb4_bin NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
