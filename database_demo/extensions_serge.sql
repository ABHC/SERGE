SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `extensions_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` text COLLATE utf8mb4_bin NOT NULL,
	`optionnal_tables` int(11) NOT NULL,
	`sources_table_name` text COLLATE utf8mb4_bin DEFAULT NULL,
	`inquiries_table_name` text COLLATE utf8mb4_bin DEFAULT NULL,
	`results_table_name` text COLLATE utf8mb4_bin NOT NULL,
	`optionnal_tables_names` text COLLATE utf8mb4_bin NOT NULL,
	`label_content` text COLLATE utf8mb4_bin NOT NULL,
	`label_color` text COLLATE utf8mb4_bin NOT NULL,
	`label_text_color` text COLLATE utf8mb4_bin NOT NULL,
	`mail_switch` tinyint(1) DEFAULT 0,
	`general_switch` tinyint(1) DEFAULT 0,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

/*INSERT INTO `extensions_serge` (`id`, `name`, `sources_table_name`, `inquiries_table_name`, `results_table_name`, `label_content`, `label_color`, `label_text_color`, `mail_switch`, `general_switch`) VALUES
/*(1, 'extension', '');

SET NAMES utf8mb4;
