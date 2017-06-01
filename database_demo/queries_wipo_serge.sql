SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `queries_wipo_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`query` text COLLATE utf8mb4_bin DEFAULT 3,
	`legal_research` tinyint(1) NOT NULL,
	`owners` text COLLATE utf8mb4_bin NOT NULL,
	`active` int(11) NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
