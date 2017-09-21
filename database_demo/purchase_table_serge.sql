SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `purchase_table_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`user_id` int(11) NOT NULL,
	`purchase_date` int(11) NOT NULL,
	`duration_premium` int(11) NOT NULL,
	`invoice_number` text COLLATE utf8mb4_bin NOT NULL,
	`price` int(11) NOT NULL,
	`premium_code_id` int(11) NOT NULL,
	`bank_details` text COLLATE utf8mb4_bin NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
