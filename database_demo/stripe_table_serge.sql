SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `stripe_table_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`account_name` text COLLATE utf8mb4_bin NOT NULL,
	`secret_key` text COLLATE utf8mb4_bin NOT NULL,
	`publishable_key` text COLLATE utf8mb4_bin NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
