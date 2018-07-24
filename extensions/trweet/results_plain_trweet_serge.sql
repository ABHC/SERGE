SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `results_plain_trweet_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`author` text COLLATE utf8mb4_bin NOT NULL,
	`tweet` text COLLATE utf8mb4_bin,
	`date` text COLLATE utf8mb4_bin,
	`likes` int(11) NOT NULL,
	`retweets` int(11) NOT NULL,
	`link` text COLLATE utf8mb4_bin NOT NULL,
	`send_status` VARCHAR(8000) COLLATE utf8mb4_bin DEFAULT ',0,',
	`read_status` VARCHAR(8000) COLLATE utf8mb4_bin DEFAULT ',0,',
	`inquiry_id` text COLLATE utf8mb4_bin NOT NULL,
	`owners` text COLLATE utf8mb4_bin NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
