-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 04 Décembre 2016 à 19:04
-- Version du serveur :  5.7.11
-- Version de PHP :  7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `changesous`
--

-- --------------------------------------------------------

--
-- Structure de la table `news`
--
DROP TABLE `news` ;

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `date_creation` datetime NOT NULL,
  `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `corps` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date_mise_en_ligne` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `news`
--

INSERT INTO `news` (`id`, `date_creation`, `titre`, `corps`, `date_mise_en_ligne`) VALUES
(1, '2016-10-29 00:00:00', 'Run 1 : Configuration technique', '\r\n            <ul>\r\n            <li>Création compte github & configuration</li>\r\n<li>Mise en place de la page Wiki</li>\r\n<li>Mise en place des runs</li>\r\n<li>Installation de PHPstorm</li>\r\n<li>Installation de Symfony</li>\r\n<li>Installation de Bootstrap</li>\r\n<li>Installation de vendors tels que FosUserBundle et FosJsRouting</li>\r\n<li>Mise en place de l\'architecture du projet</li>\r\n</ul>\r\n<br/>\r\nDébut : Samedi 29 Octobre<br/>\r\n<br/>\r\nFin estimé : Jeudi 03 Novembre<br/>\r\n            ', '2016-11-03 00:00:00'),
(2, '2016-11-04 00:00:00', 'Run 2 : Création des entités', '\r\n            <ul>\r\n            <li>Mise en place du modèle objet</li>\r\n<li>Mise en place des fixtures</li>\r\n<li>Système de news</li>\r\n<li>Alimentation par le webservice & enregistrement de la donnée de référence</li>\r\n</ul>\r\n<br/>\r\nDébut : Vendredi 04 Novembre<br/>\r\n<br/>\r\nFin estimé : Dimanche 27 Novembre<br/>\r\n<br/>\r\nFin réel : Mardi 8 Novembre<br/>\r\n            ', '2016-11-08 00:00:00'),
(3, '2016-11-20 00:00:00', 'Run 3 : Visuel des données (Version 0.3)', '\r\n            <ul>\r\n            <li>Mise en place du modèle objet</li>\r\n<li>Affichage des données</li>\r\n<li>Enregistrement des préférences en base</li>\r\n<li>Système d’alerte</li>\r\n</ul>\r\n<br/>\r\nDébut : Mercredi  09 Novembre<br/>\r\n<br/>\r\nFin estimé : Dimanche 11 Décembre<br/>\r\n<br/>\r\nFin réel : Dimanche 20 Novembre<br/>\r\n            ', '2016-11-20 00:00:00');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
