-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 22 mars 2021 à 18:10
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
(1, 'cweinreich', 'so6j$', 'clement@weinreich.fr'),
(2, 'cweinreich1', 'so6j$', 'clement@weinreich.fr'),
(3, 'cweinreich2', 'so6j$', 'clement@weinreich.fr'),
(4, 'cweinreich3', 'so6j$', 'clement@weinreich.fr'),
(5, 'cweinreich4', 'so6j$', 'clement@weinreich.fr'),
(6, 'gestionax@gmail.com', 'oui', 'gestionax@gmail.com');

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
(1, 1, 1),
(2, 1, 2),
(3, 0, 3),
(4, 0, 4),
(5, 0, 5);

-- --------------------------------------------------------

--
-- Structure de la table `ExperiencePro`
--

CREATE TABLE `ExperiencePro` (
  `IdExperiencePro` int(11) NOT NULL,
  `IntituleExperiencePro` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `TypeExperiencePro` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `DateDebut` date DEFAULT NULL,
  `DateFin` date DEFAULT NULL,
  `TypeOrganisation` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `LibelleOrganisation` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `TypePoste` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Region` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Ville` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `SecteursActivites` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `DomainesCompetences` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Description` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Salaire` double DEFAULT NULL,
  `IdEleve` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `ExperiencePro`
--

INSERT INTO `ExperiencePro` (`IdExperiencePro`, `IntituleExperiencePro`, `TypeExperiencePro`, `DateDebut`, `DateFin`, `TypeOrganisation`, `LibelleOrganisation`, `TypePoste`, `Region`, `Ville`, `SecteursActivites`, `DomainesCompetences`, `Description`, `Salaire`, `IdEleve`) VALUES
(1, 'Stage de 2ème année au laboratoire IMS', 'Stage', '2023-04-02', '2023-06-02', 'Laboratoire', 'IMS', 'Avranches', 'Normandie', 'Avranches', 'Transport, Aéronautique, ', 'IA, SHS, UX, ', 'description de la fête', 2942, 2),
(2, 'Stage de 2ème année chez thales', 'Stage', '2023-04-02', '2023-06-02', 'Entreprise', 'thales', 'Avranches', 'Normandie', 'Avranches', 'Transport, Aéronautique, ', 'IA, SHS, UX, ', 'gros stage', 2942, 2);

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
(1, 6);

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
(1, 'weinreich', 'clément', 'M', 2023, '100B avenue roul', 'Talence', 33400, 624396336, 1),
(2, 'weinreich', 'clément', 'M', 2023, '100B avenue roul', 'Talence', 33400, 624396336, 2),
(3, 'weinreich', 'clément', 'M', 2023, '100B avenue roul', 'Talence', 33400, 624396336, 3),
(4, 'weinreich', 'clément', 'M', 2023, '100B avenue roul', 'Talence', 33400, 624396336, 4),
(5, 'weinreich', 'clément', 'M', 2023, '100B avenue roul', 'Talence', 33400, 624396336, 5);

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
  MODIFY `IdCompte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `Eleve`
--
ALTER TABLE `Eleve`
  MODIFY `IdEleve` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `IdInfosPerso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `Parametres`
--
ALTER TABLE `Parametres`
  MODIFY `IdParametres` int(11) NOT NULL AUTO_INCREMENT;

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