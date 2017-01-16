-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 15 Décembre 2016 à 16:25
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
-- Structure de la table `rss_serge`
--

CREATE TABLE `rss_serge` (
  `id` int(11) NOT NULL,
  `link` text COLLATE utf8_unicode_ci NOT NULL,
  `owners` text COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `rss_serge`
--

INSERT INTO `rss_serge` (`id`, `link`, `owners`, `active`) VALUES
(1, 'http://feeds.harvardbusiness.org/harvardbusiness', ',3,', 1),
(2, 'http://www.futura-sciences.com/rss/espace/actualites.xml', ',1,2,', 2),
(3, 'http://www.numerama.com/feed/', ',1,2,3,', 3),
(4, 'https://www.kickstarter.com/projects/feed.atom', ',2,3,', 2),
(5, 'https://www.technologyreview.com/stories.rss', ',2,', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
