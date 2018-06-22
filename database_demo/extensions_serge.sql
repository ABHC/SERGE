SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `extension_serge` (
  `id` int(11) NOT NULL AUTO_INCREMENT,,
  `name` text COLLATE utf8mb4_bin NOT NULL,
  `sources_table_name` text COLLATE utf8mb4_bin DEFAULT NULL,
  `queries_table_name` text COLLATE utf8mb4_bin DEFAULT NULL,
  `results_table_name` text COLLATE utf8mb4_bin NOT NULL,
  `label_content` text COLLATE utf8mb4_bin DEFAULT NULL,
  `label_color` text COLLATE utf8mb4_bin DEFAULT NULL,
  `label_text_color` text COLLATE utf8mb4_bin DEFAULT NULL,
  `mail_switch` tinyint(1) DEFAULT 0,
  `general_switch` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `extension_serge` (`id`, `name`, `sources_table_name`, `queries_table_name`, `results_table_name`, `label_content`, `label_color`, `label_text_color`, `mail_switch`, `general_switch`) VALUES
(1, 'extension', '');

SET NAMES utf8mb4;