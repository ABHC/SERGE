SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `results_plain_trweet_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`type` text COLLATE utf8mb4_bin NOT NULL,
	`author` text COLLATE utf8mb4_bin,
	`tweet` text COLLATE utf8mb4_bin,
	`date` text COLLATE utf8mb4_bin,
	`likes` int(11),
	`retweets` int(11),
	`latitude` DECIMAL(10,7) NOT NULL,
	`longitude` DECIMAL(10,7) NOT NULL,
	`country` text COLLATE utf8mb4_bin NOT NULL,
	`link` text COLLATE utf8mb4_bin,
	`trweet_id` text COLLATE utf8mb4_bin NOT NULL,
	`inquiry_id` text COLLATE utf8mb4_bin NOT NULL,
	`owners` text COLLATE utf8mb4_bin NOT NULL,
	`send_status` VARCHAR(8000) COLLATE utf8mb4_bin DEFAULT ',0,',
	`read_status` VARCHAR(8000) COLLATE utf8mb4_bin DEFAULT ',0,',
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;

(inquiry_id, owners, author, tweet, date, likes, retweets, link)
(inquiry_id, owners, trweet_id, latitude, longitude, country, `date`)
(type, author, tweet, date, likes, retweets, latitude, longitude, country, link, trweet_id, inquiry_id, owners)
(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
