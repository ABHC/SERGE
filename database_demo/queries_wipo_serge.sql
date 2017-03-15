SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

  `id` int(11) NOT NULL,
  `query` text COLLATE utf8mb4_bin NOT NULL,
  `owners` text COLLATE utf8mb4_bin NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
