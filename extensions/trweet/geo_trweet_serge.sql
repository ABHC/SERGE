SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `geo_trweet_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`query_id` text COLLATE utf8mb4_bin NOT NULL,
	`owners` text COLLATE utf8mb4_bin NOT NULL,
	`trweet_id` text COLLATE utf8mb4_bin NOT NULL,
	`latitude` DECIMAL(10,7) NOT NULL,
	`longitude` DECIMAL(10,7) NOT NULL,
	`country` text COLLATE utf8mb4_bin NOT NULL,
	`date` text COLLATE utf8mb4_bin,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
