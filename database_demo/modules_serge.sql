SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `modules_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` text COLLATE utf8mb4_bin NOT NULL,
	`optionnal_tables` int(11) NOT NULL,
	`sources_table_name` text COLLATE utf8mb4_bin DEFAULT NULL,
	`inquiries_table_name` text COLLATE utf8mb4_bin DEFAULT NULL,
	`results_table_name` text COLLATE utf8mb4_bin NOT NULL,
	`optionnal_tables_names` text COLLATE utf8mb4_bin,
	`label_content` text COLLATE utf8mb4_bin NOT NULL,
	`label_color` text COLLATE utf8mb4_bin NOT NULL,
	`label_text_color` text COLLATE utf8mb4_bin NOT NULL,
	`mail_switch` tinyint(1) DEFAULT 0,
	`general_switch` tinyint(1) DEFAULT 0,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;

INSERT INTO `modules_serge` (`id`, `name`, `optionnal_tables`, `sources_table_name`, `inquiries_table_name`, `results_table_name`, `optionnal_tables_names`, `label_content`, `label_color`, `label_text_color`, `mail_switch`, `general_switch`) VALUES
(1, 'news', 0, 'sources_news_serge', 'inquiries_news_serge', 'results_news_serge', NULL, '> news', '#00802b', '#ffffff', 1, 1),
(2, 'patents', 0, 'sources_patents_serge', 'inquiries_patents_serge', 'results_patents_serge', NULL, '> patents', '#660022', '#ffffff', 1, 1),
(3, 'sciences', 0, 'sources_sciences_serge', 'inquiries_sciences_serge', 'results_sciences_serge', NULL, '> sciences', '#006666', '#ffffff', 1, 1);
