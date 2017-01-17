-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 16 Décembre 2016 à 13:35
-- Version du serveur :  5.7.16-0ubuntu0.16.04.1
-- Version de PHP :  7.0.8-0ubuntu0.16.04.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `CairnDevices`
--

-- --------------------------------------------------------

--
-- Structure de la table `keyword_news_serge`
--

CREATE TABLE `keyword_news_serge` (
  `id` int(11) NOT NULL,
  `keyword` text COLLATE utf8_unicode_ci NOT NULL,
  `owners` text COLLATE utf8_unicode_ci NOT NULL,
  `id_source` text COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `keyword_news_serge`
--

INSERT INTO `keyword_news_serge` (`id`, `keyword`, `owners`, `id_source`, `active`) VALUES
(1, 'Israel', ',3,', ',1,', 1),
(2, 'Linux', ',1,2,', ',2,3,4,5,', 2),
(3, 'logiciel libre', ',1,2,3,', ',2,3,4,5,', 3),
(4, 'océan bleu', ',2,3,', ',1,', 2),
(5, 'sécurité informatique', ',2,', ',2,3,', 1),
(6, 'SpaceX', ',1,', ',2,3,', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
