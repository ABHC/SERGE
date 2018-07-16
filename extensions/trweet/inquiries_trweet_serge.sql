SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `inquiries_trweet_serge` (
	`id` int(11) NOT NULL,
	`type` text COLLATE utf8mb4_bin NOT NULL,
	`inquiry` text COLLATE utf8mb4_bin NOT NULL,
	`applicable_owners_targets` text COLLATE utf8mb4_bin NOT NULL,
	`lang` text COLLATE utf8mb4_bin,
	`last_launch` int(11) NOT NULL,
	`active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
