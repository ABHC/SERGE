SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `text_content_serge` (
	`index_name` text  COLLATE utf8mb4_bin NOT NULL,
	`EN` text COLLATE utf8mb4_bin DEFAULT NULL,
	`FR` text COLLATE utf8mb4_bin DEFAULT NULL,
	`ES` text COLLATE utf8mb4_bin DEFAULT NULL,
	`DE` text COLLATE utf8mb4_bin DEFAULT NULL,
	`CN` text COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
CREATE INDEX index_text ON text_content_serge (index_name(30));

INSERT INTO `text_content_serge` (`index_name`, `EN`, `FR`, `ES`, `DE`, `CN`) VALUES
('main_title_index', 'Stay always update with Serge', 'Restez toujours à jour avec Serge', NULL, NULL, NULL),
('sub_title_index', 'Improving performance through news monitoring can often takes time.<br>
	By searching the news, patents and scientific publications, Serge allows you to stay update effectively and gives you time to do other thing', 'L\'amélioration des performances grâce à la veille peut souvent prendre du temps.
En recherchant les actualités, les brevets et les publications scientifiques, Serge vous permet de rester informé efficacement et vous donne le temps de faire autre chose', NULL, NULL, NULL),
('try1_button_index', 'Try for free', 'Essayer gratuitement', NULL, NULL, NULL),
('try2_button_index', 'Let me try !', 'Laissez moi essayer !', NULL, NULL, NULL),
('comingSoon_button_index', 'Coming soon', 'Fonctionnalités à venir', NULL, NULL, NULL),
('functionality1_title_index', 'Follow RSS flux', 'Suivre les flux RSS', NULL, NULL, NULL),
('functionality2_title_index', 'Patents', 'Brevets', 'Patentes', NULL, NULL),
('functionality3_title_index', 'Scientifics publications', 'Publication scientifiques', NULL, NULL, NULL),
('functionality4_title_index', 'Newsletter', 'Newsletter', NULL, NULL, NULL),
('functionality5_title_index', 'Hight customization', 'Haute personalisation', NULL, NULL, NULL),
('functionality6_title_index', 'Effective history', 'Historyqie efficace', NULL, NULL, NULL),
('functionality7_title_index', 'RSS feed', 'Flux RSS', NULL, NULL, NULL),
('functionality8_title_index', 'Track twitter', 'Suivie de twitter', NULL, NULL, NULL),
('functionality9_title_index', 'Wiki', 'Wiki', NULL, NULL, NULL),
('functionality10_title_index', 'Alert by SMS', 'Alert par SMS', NULL, NULL, NULL),
('functionality11_title_index', 'Statistics', 'Statistique', NULL, NULL, NULL);
