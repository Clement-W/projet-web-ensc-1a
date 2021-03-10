-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 10 mars 2021 à 17:38
-- Version du serveur :  10.4.17-MariaDB
-- Version de PHP : 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `annuaireEleves`
--

-- --------------------------------------------------------

--
-- Structure de la table `Compte`
--

CREATE TABLE `Compte` (
  `IdCompte` int(11) NOT NULL,
  `NomUtilisateur` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `MotDePasse` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `AdresseMail` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `Compte`
--

INSERT INTO `Compte` (`IdCompte`, `NomUtilisateur`, `MotDePasse`, `AdresseMail`) VALUES
(1, 'username1', 'password1', 'username1@ensc.fr'),
(2, 'username2', 'password2', 'username2@ensc.fr');

-- --------------------------------------------------------

--
-- Structure de la table `Eleve`
--

CREATE TABLE `Eleve` (
  `IdEleve` int(11) NOT NULL,
  `CompteValide` tinyint(1) DEFAULT NULL,
  `IdCompte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `Eleve`
--

INSERT INTO `Eleve` (`IdEleve`, `CompteValide`, `IdCompte`) VALUES
(1, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `ExperiencePro`
--

CREATE TABLE `ExperiencePro` (
  `IdExperiencePro` int(11) NOT NULL,
  `TypeExperiencePro` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `DateDebut` date DEFAULT NULL,
  `DateFin` date DEFAULT NULL,
  `TypeOrganisation` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Lieu` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `SecteursActivites` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `DomainesCompetences` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Salaire` double DEFAULT NULL,
  `IdEleve` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `ExperiencePro`
--

INSERT INTO `ExperiencePro` (`IdExperiencePro`, `TypeExperiencePro`, `DateDebut`, `DateFin`, `TypeOrganisation`, `Lieu`, `SecteursActivites`, `DomainesCompetences`, `Description`, `Salaire`, `IdEleve`) VALUES
(1, 'Stage', '2019-04-22', '2019-06-22', 'Laboratoire', 'Tonlence', 'Santé', 'IA', 'Stage visant à étudier et expliciter les processus danalyse explcites', 3942, 1),
(2, 'Stage', '2018-04-22', '2019-06-22', 'Entreprise', 'Bordaxe', 'Commerce', 'IHM', 'Stage visant à commercer avec le commerce', 3942, 1);

-- --------------------------------------------------------

--
-- Structure de la table `Gestionnaire`
--

CREATE TABLE `Gestionnaire` (
  `IdGestionnaire` int(11) NOT NULL,
  `IdCompte` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `Gestionnaire`
--

INSERT INTO `Gestionnaire` (`IdGestionnaire`, `IdCompte`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `InfosPerso`
--

CREATE TABLE `InfosPerso` (
  `IdInfosPerso` int(11) NOT NULL,
  `Nom` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Prenom` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Genre` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Promotion` int(11) DEFAULT NULL,
  `Adresse` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Ville` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `CodePostal` int(11) DEFAULT NULL,
  `NumTelephone` int(11) DEFAULT NULL,
  `IdEleve` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `InfosPerso`
--

INSERT INTO `InfosPerso` (`IdInfosPerso`, `Nom`, `Prenom`, `Genre`, `Promotion`, `Adresse`, `Ville`, `CodePostal`, `NumTelephone`, `IdEleve`) VALUES
(1, 'Carena', 'Emma', 'F', 2023, '2 rue de la poisse', 'Tonlence', 30290, 622222222, 1);

-- --------------------------------------------------------

--
-- Structure de la table `Parametres`
--

CREATE TABLE `Parametres` (
  `IdParametres` int(11) NOT NULL,
  `LibelleInformation` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Visibilite` tinyint(1) DEFAULT NULL,
  `IdEleve` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `Parametres`
--

INSERT INTO `Parametres` (`IdParametres`, `LibelleInformation`, `Visibilite`, `IdEleve`) VALUES
(1, 'Nom', 1, 1),
(2, 'Prenom', 1, 1),
(3, 'Genre', 0, 1),
(4, 'Promotion', 1, 1),
(5, 'Adresse', 0, 1),
(6, 'Ville', 1, 1),
(7, 'CodePostal', 1, 1),
(8, 'NumTelephone', 0, 1),
(9, '1', 1, 1),
(10, '2', 1, 1),
(11, 'AdresseMail', 0, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Compte`
--
ALTER TABLE `Compte`
  ADD PRIMARY KEY (`IdCompte`);

--
-- Index pour la table `Eleve`
--
ALTER TABLE `Eleve`
  ADD PRIMARY KEY (`IdEleve`),
  ADD UNIQUE KEY `IdCompte` (`IdCompte`);

--
-- Index pour la table `ExperiencePro`
--
ALTER TABLE `ExperiencePro`
  ADD PRIMARY KEY (`IdExperiencePro`),
  ADD KEY `IdEleve` (`IdEleve`);

--
-- Index pour la table `Gestionnaire`
--
ALTER TABLE `Gestionnaire`
  ADD PRIMARY KEY (`IdGestionnaire`),
  ADD UNIQUE KEY `IdCompte` (`IdCompte`);

--
-- Index pour la table `InfosPerso`
--
ALTER TABLE `InfosPerso`
  ADD PRIMARY KEY (`IdInfosPerso`),
  ADD UNIQUE KEY `IdEleve` (`IdEleve`);

--
-- Index pour la table `Parametres`
--
ALTER TABLE `Parametres`
  ADD PRIMARY KEY (`IdParametres`),
  ADD KEY `IdEleve` (`IdEleve`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Compte`
--
ALTER TABLE `Compte`
  MODIFY `IdCompte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `Eleve`
--
ALTER TABLE `Eleve`
  MODIFY `IdEleve` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `ExperiencePro`
--
ALTER TABLE `ExperiencePro`
  MODIFY `IdExperiencePro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `Gestionnaire`
--
ALTER TABLE `Gestionnaire`
  MODIFY `IdGestionnaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `InfosPerso`
--
ALTER TABLE `InfosPerso`
  MODIFY `IdInfosPerso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `Parametres`
--
ALTER TABLE `Parametres`
  MODIFY `IdParametres` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Eleve`
--
ALTER TABLE `Eleve`
  ADD CONSTRAINT `Eleve_ibfk_1` FOREIGN KEY (`IdCompte`) REFERENCES `Compte` (`IdCompte`);

--
-- Contraintes pour la table `ExperiencePro`
--
ALTER TABLE `ExperiencePro`
  ADD CONSTRAINT `ExperiencePro_ibfk_1` FOREIGN KEY (`IdEleve`) REFERENCES `Eleve` (`IdEleve`);

--
-- Contraintes pour la table `Gestionnaire`
--
ALTER TABLE `Gestionnaire`
  ADD CONSTRAINT `Gestionnaire_ibfk_1` FOREIGN KEY (`IdCompte`) REFERENCES `Compte` (`IdCompte`);

--
-- Contraintes pour la table `InfosPerso`
--
ALTER TABLE `InfosPerso`
  ADD CONSTRAINT `InfosPerso_ibfk_1` FOREIGN KEY (`IdEleve`) REFERENCES `Eleve` (`IdEleve`);

--
-- Contraintes pour la table `Parametres`
--
ALTER TABLE `Parametres`
  ADD CONSTRAINT `Parametres_ibfk_1` FOREIGN KEY (`IdEleve`) REFERENCES `Eleve` (`IdEleve`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
