SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `equivalence_science_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`apikey` int(11),
	`type` text COLLATE utf8mb4_bin NOT NULL,
	`basename` text COLLATE utf8mb4_bin NOT NULL,
	`completename` text COLLATE utf8mb4_bin NOT NULL,
	`prelink` text COLLATE utf8mb4_bin NOT NULL,
	`postlink` text COLLATE utf8mb4_bin NOT NULL,
	`AND` text COLLATE utf8mb4_bin DEFAULT NULL,
	`OR` text COLLATE utf8mb4_bin DEFAULT NULL,
	`NOT` text COLLATE utf8mb4_bin DEFAULT NULL,
	`(` text COLLATE utf8mb4_bin DEFAULT NULL,
	`)` text COLLATE utf8mb4_bin DEFAULT NULL,
	`quote` text COLLATE utf8mb4_bin DEFAULT NULL,
	`title` text COLLATE utf8mb4_bin DEFAULT NULL,
	`author` text COLLATE utf8mb4_bin DEFAULT NULL,
	`abstract` text COLLATE utf8mb4_bin DEFAULT NULL,
	`publisher` text COLLATE utf8mb4_bin DEFAULT NULL,
	`category` text COLLATE utf8mb4_bin DEFAULT NULL,
	`all` text COLLATE utf8mb4_bin DEFAULT NULL,
	`active` int(11) NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;

INSERT INTO `equivalence_science_serge` (`id`, `apikey`, `type`, `basename`, `completename`, `prelink`, `postlink`, `AND`, `OR`, `NOT`, `(`, `)`, `quote`, `title`, `author`, `abstract`, `publisher`, `category`, `all`, `active`) VALUES
(1, NULL, 'RSS', 'plos', 'PLOS', 'http://journals.plos.org/plosone/search/feed/atom?resultsPerPage=30&q=', '&sortOrder=DATE_NEWEST_FIRST&page=1', '+AND+', '+OR+', '+NOT+', '%28', '%29', NULL, 'title%3', 'author%3A', 'abstract%3A', 'everything%3A', 'subject%3A', 'everything%3A', 1);
