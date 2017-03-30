SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `result_patents_serge` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8mb4_bin NOT NULL,
  `link` text COLLATE utf8mb4_bin NOT NULL,
  `send_status` VARCHAR(16000) COLLATE utf8mb4_bin DEFAULT ',0,',
  `date` text COLLATE utf8mb4_bin DEFAULT NULL,
  `id_query_wipo` text COLLATE utf8mb4_bin NOT NULL,
  `owners` text COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
