SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `users_table_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`users` text COLLATE utf8mb4_bin NOT NULL,
	`email` text COLLATE utf8mb4_bin NOT NULL,
	`password` text COLLATE utf8mb4_bin NOT NULL,
	`salt` text COLLATE utf8mb4_bin NOT NULL,
	`signup_date` int(11) NOT NULL,
	`last_mail` int(11) DEFAULT NULL,
	`send_condition` varchar(15) COLLATE utf8mb4_bin NOT NULL DEFAULT 'link_limit',
	`frequency` int(11) DEFAULT NULL,
	`link_limit` int(11) DEFAULT 30,
	`selected_days` text COLLATE utf8mb4_bin DEFAULT NULL,
	`selected_hour` int(11) DEFAULT NULL,
	`mail_design` varchar(15) COLLATE utf8mb4_bin NOT NULL DEFAULT 'masterword',
	`language` varchar(2) COLLATE utf8mb4_bin DEFAULT 'EN',
	`record_read` BOOLEAN NOT NULL DEFAULT 1,
	`history_lifetime` int(11) DEFAULT 12,
	`background_result` varchar(15) COLLATE utf8mb4_bin NOT NULL DEFAULT 'Skyscrapers',
	`premium_expiration_date` int(11) DEFAULT 0,
	`email_validation` BOOLEAN NOT NULL DEFAULT 0,
	`token` varchar(8) COLLATE utf8mb4_bin NOT NULL,
	`add_source_status` text COLLATE utf8mb4_bin DEFAULT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
