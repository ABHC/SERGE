SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `science_sources_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`link` text COLLATE utf8mb4_bin NOT NULL,
	`name` text COLLATE utf8mb4_bin DEFAULT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;

INSERT INTO `science_sources_serge` (`id`, `link`, `name`) VALUES
(1, 'http://arxiv.org/', 'ArXiv.org'),
(2, 'https://doaj.org/', 'Directory of Open Access Journals (DOAJ)'),
(3, 'http://api.archives-ouvertes.fr', 'HAL - Archives Ouvertes'),
(4, 'https://api.elsevier.com', 'Science Direct (Elsevier)');
