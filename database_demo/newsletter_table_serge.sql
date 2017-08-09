SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `newsletter_table_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`email` text COLLATE utf8mb4_bin NOT NULL,
	`signup_date` int(11) NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
