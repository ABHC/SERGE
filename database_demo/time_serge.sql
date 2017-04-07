SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `time_serge` (
  `name` text COLLATE utf8mb4_bin NOT NULL,
  `timestamps` bigint(8) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `time_serge` (`name`, `timestamps`) VALUES
('timelog', UNIX_TIMESTAMP()),
('feedtitles_refresh', UNIX_TIMESTAMP());

SET NAMES utf8mb4;
