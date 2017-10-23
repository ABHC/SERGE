SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `background_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` text COLLATE utf8mb4_bin NOT NULL,
	`filename` text COLLATE utf8mb4_bin NOT NULL,
	`type` text COLLATE utf8mb4_bin NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

INSERT INTO `background_serge` VALUES (1,'Fujiyama','Japan01.jpeg','result'),
(2,'Forest mountain','Mountains01.jpg','result'),
(3,'Mountain lake','Mountains02.jpeg','result'),
(4,'Natural valley','Nature01.jpg','result'),
(5,'Lake','Nature02.jpg','result'),
(6,'Waterfall cairn','Nature03.jpeg','result'),
(7,'Cloudy nature','Nature04.jpeg','result'),
(8,'Forest','Nature05.jpg','result'),
(9,'On a lake','Nature06.jpeg','result'),
(10,'Blue Sea','Sea01.jpg','result'),
(11,'Sea cairn','Sea02.jpeg','result'),
(12,'Sea','Sea03.jpg','result'),
(13,'Peaceful sea','Sea04.jpeg','result'),
(14,'Skyscrapers','Skyscrapers01.jpg','result'),
(15,'Milky way','Space01.jpeg','result'),
(16,'Space','Space02.jpg','result');

SET NAMES utf8mb4;
