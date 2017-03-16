SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `users_table_serge` (
  `id` int(11) NOT NULL,
  `users` text COLLATE utf8mb4_bin NOT NULL,
  `email` text COLLATE utf8mb4_bin NOT NULL,
  `last_mail` int(11) DEFAULT NULL,
  `send_condition` text COLLATE utf8mb4_bin NOT NULL,
  `frequency` int(11) DEFAULT NULL,
  `link_limit` int(11) DEFAULT NULL,
  `selected_days` text COLLATE utf8mb4_bin DEFAULT NULL,
  `selected_hour` int(11) DEFAULT NULL,
  `mail_design` text COLLATE utf8mb4_bin NOT NULL,
  `language` varchar(2) COLLATE utf8mb4_bin DEFAULT 'EN',
  `permission_news` tinyint(1) NOT NULL,
  `permission_science` tinyint(1) NOT NULL,
  `permission_patents` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
