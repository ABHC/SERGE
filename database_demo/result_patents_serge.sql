SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `result_patents_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`search_index` text COLLATE utf8mb4_bin DEFAULT NULL,
	`title` text COLLATE utf8mb4_bin NOT NULL,
	`link` text COLLATE utf8mb4_bin NOT NULL,
	`send_status` VARCHAR(8000) COLLATE utf8mb4_bin DEFAULT ',0,',
	`read_status` VARCHAR(8000) COLLATE utf8mb4_bin DEFAULT ',0,',
	`date` text COLLATE utf8mb4_bin DEFAULT NULL,
	`id_source` int(11) NOT NULL,
	`id_query_wipo` text COLLATE utf8mb4_bin NOT NULL,
	`owners` text COLLATE utf8mb4_bin NOT NULL,
	`legal_abstract` text COLLATE utf8mb4_bin DEFAULT NULL,
	`legal_status` text COLLATE utf8mb4_bin DEFAULT NULL,
	`lens_link` text COLLATE utf8mb4_bin DEFAULT NULL,
	`legal_check_date` double DEFAULT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;

ALTER TABLE `result_patents_serge` ADD FULLTEXT INDEX `search` (`search_index`);
