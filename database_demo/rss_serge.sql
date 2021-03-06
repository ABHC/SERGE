SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `rss_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`link` text COLLATE utf8mb4_bin NOT NULL,
	`name` text COLLATE utf8mb4_bin DEFAULT NULL,
	`favicon` blob,
	`owners` text COLLATE utf8mb4_bin NOT NULL,
	`etag` text COLLATE utf8mb4_bin DEFAULT NULL,
	`active` int(11) NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
