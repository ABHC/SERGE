SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `patents_sources_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`link` text COLLATE utf8mb4_bin NOT NULL,
	`name` text COLLATE utf8mb4_bin DEFAULT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;

INSERT INTO `patents_sources_serge` (`id`, `link`, `name`) VALUES
(1, 'http://www.wipo.int', 'The World Intellectual Property Organization (WIPO)');
