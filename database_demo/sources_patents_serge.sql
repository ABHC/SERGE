SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `sources_patents_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`apikey` int(11),
	`type` text COLLATE utf8mb4_bin NOT NULL,
	`basename` text COLLATE utf8mb4_bin NOT NULL,
	`name` text COLLATE utf8mb4_bin NOT NULL,
	`link` text COLLATE utf8mb4_bin NOT NULL,
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

#TODO compléter la base sources_patents_serge pour la mettre en conformité avec requestBuilder attention aux langues

INSERT INTO `sources_patents_serge` (`id`, `apikey`, `type`, `basename`, `name`, `link`, `prelink`, `postlink`, `AND`, `OR`, `NOT`, `(`, `)`, `quote`, `title`, `author`, `abstract`, `publisher`, `category`, `all`, `active`) VALUES
(1, NULL, 'RSS', 'wipo', 'WIPO : World Intellectual Property Organization', 'http://www.wipo.int/','https://patentscope.wipo.int/search/rss.jsf?query=', '&office=&rss=true&sortOption=Pub+Date+Desc', '+AND+', '+OR+', '+ANDNOT+', '%28', '%29', '%22', 'ti:', 'au:', 'abs:', 'jr:', 'cat:', 'all:', 1),
