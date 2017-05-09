SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `miscellaneous_serge` (
  `name` text COLLATE utf8mb4_bin NOT NULL,
  `value` text COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `miscellaneous_serge` (`name`, `value`) VALUES
('timelog', UNIX_TIMESTAMP()),
('feedtitles_refresh', UNIX_TIMESTAMP()),
('domain', 'Your_domain_name'),
('extension', 'Your_Extension1!amount_of_supplementary_tables1|Your_Extension2!amount_of_supplementary_tables2|Your_Extension3!amout_of_supplementary_tables3');

SET NAMES utf8mb4;
