SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `premium_code_table_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`code` text COLLATE utf8mb4_bin NOT NULL,
	`creation_date` int(11) NOT NULL,
	`users` text COLLATE utf8mb4_bin NOT NULL,
	`duration_premium` int(11) NOT NULL,
	`expiration_date` int(11) NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
