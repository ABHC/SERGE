SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `users_table_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`users` text COLLATE utf8mb4_bin NOT NULL,
	`email` text COLLATE utf8mb4_bin NOT NULL,
	`password` text COLLATE utf8mb4_bin NOT NULL,
	`last_mail` int(11) DEFAULT NULL,
	`send_condition` text COLLATE utf8mb4_bin NOT NULL,
	`frequency` int(11) DEFAULT NULL,
	`link_limit` int(11) DEFAULT NULL,
	`selected_days` text COLLATE utf8mb4_bin DEFAULT NULL,
	`selected_hour` int(11) DEFAULT NULL,
	`mail_design` text COLLATE utf8mb4_bin NOT NULL,
	`language` varchar(2) COLLATE utf8mb4_bin DEFAULT 'EN',
	`record_read` BOOLEAN NOT NULL,
	`history_lifetime` int(11) DEFAULT NULL,
	`background_result` text COLLATE utf8mb4_bin NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
