-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 16 Décembre 2016 à 13:37
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
-- Structure de la table `keyword_patents_key_serge`
--

CREATE TABLE `keyword_patents_key_serge` (
  `id` int(11) NOT NULL,
  `keyword` text COLLATE utf8_unicode_ci NOT NULL,
  `language` text COLLATE utf8_unicode_ci NOT NULL,
  `owners` text COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `keyword_patents_key_serge`
--

INSERT INTO `keyword_patents_key_serge` (`id`, `keyword`, `language`, `owners`, `active`) VALUES
(1, 'Laptop', ',EN,', ',1,2,', 2),
(2, 'machine learning', ',EN,FR,', ',2,', 1),
(3, 'modulaire', ',EN,', ',1,2,', 2),
(4, 'Une éolienne à noël', ',FR,', ',1,2,', 2);



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
