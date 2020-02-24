-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 24 fév. 2020 à 16:41
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `switch`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE IF NOT EXISTS `avis` (
  `id_avis` int(3) NOT NULL AUTO_INCREMENT,
  `id_membre` int(3) NOT NULL,
  `id_salle` int(3) NOT NULL,
  `commentaire` text NOT NULL,
  `note` int(2) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_avis`),
  KEY `membre` (`id_membre`),
  KEY `salle` (`id_salle`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id_commande` int(3) NOT NULL AUTO_INCREMENT,
  `id_membre` int(3) NOT NULL,
  `id_produit` int(3) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_commande`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

DROP TABLE IF EXISTS `membre`;
CREATE TABLE IF NOT EXISTS `membre` (
  `id_membre` int(3) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(20) NOT NULL,
  `mdp` varchar(60) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `civilite` enum('m','f') NOT NULL,
  `statut` int(1) NOT NULL,
  `date_enregistrement` date NOT NULL,
  PRIMARY KEY (`id_membre`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `civilite`, `statut`, `date_enregistrement`) VALUES
(18, 'dany', '$2y$10$0BevpFPWoJOaX27iHq1yEOK0HROob1Mce/VNMHrjTBeCzSO4tCluK', 'beber', 'daniel', 'daniel.beber@sfr.fr', 'm', 1, '2020-02-18'),
(19, 'admin', '$2y$10$Xy.DhKJ2g/FGUEuavQA18OCfqBgLsi30th9B4.VriXcCE9c7BRLka', 'greuzat', 'damien', 'damien.greuzat@mail.com', 'm', 2, '2020-02-18'),
(20, 'Boby', '$2y$10$DWvx56IQHe61c1.4z9XCSOrEIlIHLgkAaC9R9gHxPYKp7t5zo/wKW', 'LEBRICOLEUR', 'Bob', 'Boby@gmail.com', 'm', 1, '2020-02-20');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id_produit` int(3) NOT NULL AUTO_INCREMENT,
  `id_salle` int(3) NOT NULL,
  `date_arrivee` date NOT NULL,
  `date_depart` date NOT NULL,
  `prix` int(3) NOT NULL,
  `etat` enum('libre','reservation') NOT NULL,
  PRIMARY KEY (`id_produit`),
  KEY `produit_ibfk_1` (`id_salle`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `id_salle`, `date_arrivee`, `date_depart`, `prix`, `etat`) VALUES
(4, 1, '2020-02-28', '2020-03-04', 500, 'libre'),
(7, 1, '2020-04-06', '2020-04-10', 800, 'reservation'),
(8, 8, '2020-02-05', '2020-02-28', 500, 'libre'),
(9, 8, '2020-02-05', '2020-02-28', 500, 'libre'),
(10, 1, '2020-02-20', '2020-02-26', 800, 'libre'),
(14, 5, '2020-02-22', '2020-02-29', 1200, 'libre'),
(17, 5, '2020-03-02', '2020-03-08', 500, 'libre');

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

DROP TABLE IF EXISTS `salle`;
CREATE TABLE IF NOT EXISTS `salle` (
  `id_salle` int(3) NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) NOT NULL,
  `pays` varchar(20) NOT NULL,
  `ville` varchar(20) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `cp` int(5) NOT NULL,
  `capacite` int(3) NOT NULL,
  `categorie` enum('réunion','bureau','formation') NOT NULL,
  PRIMARY KEY (`id_salle`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`id_salle`, `titre`, `description`, `photo`, `pays`, `ville`, `adresse`, `cp`, `capacite`, `categorie`) VALUES
(1, 'salle gaveau', 'Salle idéale pour vos réunions d\'affaire', 'bureau_1.jpg', 'france', 'paris', '10 rue de la paix', 75002, 100, 'réunion'),
(5, 'Salle Tour auvergne', 'salle très atypique en plein cœur du neuvième arrondissement.', 'bureau_2.jpg', 'france', 'paris', '1 rue de la tour d\'auvergne', 75009, 50, 'bureau'),
(8, 'Salle gironde', 'Très belle salle à Lyon, en plein centre historique cette espace atypique est idéale pour vos formations', 'bureau_4.jpg', 'france', 'Lyon', '21 rue du port du temple', 69002, 75, 'formation'),
(17, 'La phocéenne', 'Salle de réunion au cœur de marseille.', 'bureau_13.jpg', 'France', 'Marseille', '50 boulevard de paris', 13003, 50, 'réunion'),
(18, 'La Cannebiere', 'Une salle atypique située sur la fameuse cannebière', 'bureau_9.jpg', 'France', 'Marseille', '100 la cannebière', 13001, 70, 'bureau'),
(19, 'Salle prevot', 'Très belle espace pour organiser vos journée de formation', 'bureau_14.jpg', 'France', 'Marseille', '12 Boulevard de Dunkerque', 13002, 50, 'formation'),
(20, 'salle cleberg', 'Bureau situé dans la partie historique de lyon', 'bureau_16.jpg', 'France', 'Lyon', '11 rue cleberg', 69005, 45, 'bureau'),
(21, 'Salle bayard', 'Salle de réunion situé cours bayard', 'bureau_11.jpg', 'France', 'Lyon', '45 cours bayard', 69002, 50, 'réunion'),
(22, 'Bureau condé', 'Très beaux bureaux situé dans le 6éme arrondissement de la capitale', 'bureau_1.jpg', 'France', 'Paris', '25 rue de condé', 75006, 50, 'bureau');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `membre` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `salle` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
