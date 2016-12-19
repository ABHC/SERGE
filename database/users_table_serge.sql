-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 15 Décembre 2016 à 15:47
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
-- Structure de la table `users_table_serge`
--

CREATE TABLE `users_table_serge` (
  `id` int(11) NOT NULL,
  `users` text COLLATE utf8_unicode_ci NOT NULL,
  `email` text COLLATE utf8_unicode_ci NOT NULL,
  `last_mail` int(11) DEFAULT NULL,
  `frequency` int(11) DEFAULT NULL,
  `send_condition` text COLLATE utf8_unicode_ci NOT NULL,
  `link_limit` int(11) DEFAULT NULL,
  `permission_actu` tinyint(1) NOT NULL,
  `permission_science` tinyint(1) NOT NULL,
  `permission_patents_class` tinyint(1) NOT NULL,
  `permission_patents_inventor` tinyint(1) NOT NULL,
  `permission_patents_key` tinyint(1) NOT NULL,
  `permission_patents` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `users_table_serge`
--

INSERT INTO `users_table_serge` (`id`, `users`, `email`, `last_mail`, `frequency`, `send_condition`, `link_limit`, `permission_actu`, `permission_science`, `permission_patents_class`, `permission_patents_inventor`, `permission_patents_key`, `permission_patents`) VALUES
(1, 'Alexandre', 'X@serge.com ', 0, 43200, 'freq', NULL, 0, 0, 0, 0, 0, 0),
(2, 'Gspohu', 'GS@serge.com', 0, NULL, 'link_limit', 50, 0, 0, 1, 1, 0, 0),
(3, 'al_53', 'al@serge.com', NULL, 3600, 'freq', NULL, 0, 1, 1, 1, 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
