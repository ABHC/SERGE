SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `miscellaneous_serge` (
  `name` text COLLATE utf8mb4_bin NOT NULL,
  `value` text COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `miscellaneous_serge` (`name`, `value`) VALUES
('timelog', UNIX_TIMESTAMP()),
('feedtitles_refresh', UNIX_TIMESTAMP()),
('domain', ''),
('extension', ''),
('support_email', 'support@your-domain.tld');

SET NAMES utf8mb4;
