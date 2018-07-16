SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `credentials_trweet_serge` (
	`consumer_key` text COLLATE utf8mb4_bin NOT NULL,
	`consumer_secret` text COLLATE utf8mb4_bin NOT NULL,
	`access_token` text COLLATE utf8mb4_bin NOT NULL,
	`access_token_secret` text COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
