SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `sources_sciences_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`apikey` int(11),
	`type` text COLLATE utf8mb4_bin NOT NULL,
	`basename` text COLLATE utf8mb4_bin NOT NULL,
	`name` text COLLATE utf8mb4_bin,
	`link` text COLLATE utf8mb4_bin,
	`prelink` text COLLATE utf8mb4_bin,
	`postlink` text COLLATE utf8mb4_bin,
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
	`owners` VARCHAR(8000) COLLATE utf8mb4_bin DEFAULT ',',
	`active` int(11) NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;

INSERT INTO `sources_sciences_serge` (`id`, `apikey`, `type`, `basename`, `name`, `link`, `prelink`, `postlink`, `AND`, `OR`, `NOT`, `(`, `)`, `quote`, `title`, `author`, `abstract`, `publisher`, `category`, `all`, `active`) VALUES
(1, NULL, 'language', 'EN', NULL, NULL, NULL, NULL, ' AND ', ' OR ', ' AND NOT ', '(', ')', '"', 'title like : ', 'author like : ', 'abstract like : ', 'publisher is : ', 'category is :', 'search in all datas : ', 1),
(2, NULL, 'language', 'FR', NULL, NULL, NULL, NULL, ' ET ', ' OU ', ' EXCLUS ', '(', ')', '"', 'dans le titre : ', 'auteur : ', 'Dans le résumé : ', 'éditeur : ', 'categorie : ', 'dans tous les champs : ', 1),
(3, NULL, 'language', 'ES', NULL, NULL, NULL, NULL, ' AND ', ' OR ', ' AND NOT ', '(', ')', '"', '', '', '', '', '', '', 0),
(4, NULL, 'language', 'DE', NULL, NULL, NULL, NULL, ' AND ', ' OR ', ' AND NOT ', '(', ')', '"', '', '', '', '', '', '', 0),
(5, NULL, 'language', 'CN', NULL, NULL, NULL, NULL, ' AND ', ' OR ', ' AND NOT ', '(', ')', '"', '', '', '', '', '', '', 0),
(6, NULL, 'RSS', 'arxiv', 'ArXiv.org', 'http://arxiv.org/','http://export.arxiv.org/api/query?search_query=', '&sortBy=lastUpdatedDate&start=0&max_results=20', '+AND+', '+OR+', '+ANDNOT+', '%28', '%29', '%22', 'ti:', 'au:', 'abs:', 'jr:', 'cat:', 'all:', 1),
(7, NULL, 'JSON', 'doaj', 'DOAJ - Directory of Open Access Journals', 'https://doaj.org/', 'https://doaj.org/api/v1/search/articles/', '?pageSize=20&sort=last_updated%3Adesc', ' AND ', ' OR ', ' AND NOT ', '%28', '%29', '%22', 'bibjson.title', 'bibjson.author.name', 'bibjson.abstract', 'bibjson.publisher', 'bibjson.subject.term', 'bibjson.abstract', 1),
(8, NULL, 'RSS', 'hal', 'HAL - Archives Ouvertes', 'http://api.archives-ouvertes.fr', 'http://api.archives-ouvertes.fr/search/?q=', '&wt=rss&rows=20', '+AND+', '+OR+', '+NOT+', '(', ')', NULL, 'title_autocomplete:', 'authFullName_t:', 'abstract_t:', 'journalTitle_t:', 'text:', 'text:', 1),
(9, NULL, 'RSS', 'plos', 'PLOS - Public Library Of Science', 'http://journals.plos.org', 'http://journals.plos.org/plosone/search/feed/atom?resultsPerPage=30&q=', '&sortOrder=DATE_NEWEST_FIRST&page=1', '+AND+', '+OR+', '+NOT+', '%28', '%29', NULL, 'title%3A', 'author%3A', 'abstract%3A', 'everything%3A', 'subject%3A', 'everything%3A', 1);
