SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `watch_pack_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`pack_id` int(11) NOT NULL,
	`search_index` text COLLATE utf8mb4_bin DEFAULT NULL,
	`name` text COLLATE utf8mb4_bin NOT NULL,
	`author` text COLLATE utf8mb4_bin NOT NULL,
	`users` text COLLATE utf8mb4_bin DEFAULT NULL,
	`query` text COLLATE utf8mb4_bin NOT NULL,
	`source` text COLLATE utf8mb4_bin NOT NULL,
	`category` text COLLATE utf8mb4_bin NOT NULL,
	`language` text COLLATE utf8mb4_bin NOT NULL,
	`update_date` text COLLATE utf8mb4_bin NOT NULL,
	`rating` int(11) NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;

ALTER TABLE `watch_pack_serge` ADD FULLTEXT INDEX `search` (`search_index`);
