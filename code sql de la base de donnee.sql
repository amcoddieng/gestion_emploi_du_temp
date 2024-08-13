-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 07 août 2024 à 02:37
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `emploi_du_temps`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE `administrateur` (
  `utilisateur_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `classe`
--

CREATE TABLE `classe` (
  `id` int(11) NOT NULL,
  `formation` varchar(100) DEFAULT NULL,
  `niveau` varchar(50) DEFAULT NULL,
  `prof_responsable_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `classe`
--

INSERT INTO `classe` (`id`, `formation`, `niveau`, `prof_responsable_id`) VALUES
(2, 'INFO', 'L2', 'A2001'),
(3, 'INFO', 'L3', 'A2003'),
(4, 'RETEL', 'M1', 'ras'),
(5, 'RETEL', 'M2', 'ras'),
(6, 'SIR', 'M1', 'ras'),
(7, 'SIR', 'M2', 'ras'),
(8, 'INFO', 'L1', 'ras');

-- --------------------------------------------------------

--
-- Structure de la table `classe_module`
--

CREATE TABLE `classe_module` (
  `id_clm` int(11) NOT NULL,
  `id_classe` int(11) DEFAULT NULL,
  `id_module` int(11) DEFAULT NULL,
  `id_prof` varchar(50) DEFAULT NULL,
  `volume_horaire_faite` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `classe_module`
--

INSERT INTO `classe_module` (`id_clm`, `id_classe`, `id_module`, `id_prof`, `volume_horaire_faite`) VALUES
(3, 2, 1, 'A2001', 4),
(4, 2, 2, 'A2001', 1);

-- --------------------------------------------------------

--
-- Structure de la table `cours`
--

CREATE TABLE `cours` (
  `id` int(11) NOT NULL,
  `classe_id` int(11) DEFAULT NULL,
  `enseignant_id` varchar(50) DEFAULT NULL,
  `horaire` varchar(10) DEFAULT NULL,
  `jour` varchar(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `salle_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cours`
--

INSERT INTO `cours` (`id`, `classe_id`, `enseignant_id`, `horaire`, `jour`, `module_id`, `salle_id`) VALUES
(13, 2, 'A2001', '8', 'lundi', 1, 7),
(14, 2, 'A2001', '9', 'mardi', 1, NULL),
(15, 2, 'A2001', '10', 'jeudi', 1, NULL),
(16, 2, 'A2001', '8', 'samedi', 1, NULL),
(17, 2, 'A2001', '8', 'jeudi', 1, NULL),
(18, 2, 'A2001', '8', 'mardi', 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `demande_inscription`
--

CREATE TABLE `demande_inscription` (
  `id` int(11) NOT NULL,
  `autorisation` varchar(10) DEFAULT 'attente',
  `date` date DEFAULT current_timestamp(),
  `id_classe` int(11) DEFAULT NULL,
  `id_etudiant` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `demande_inscription`
--

INSERT INTO `demande_inscription` (`id`, `autorisation`, `date`, `id_classe`, `id_etudiant`) VALUES
(7, 'accepte', '2024-08-06', 3, '48045ggloyi'),
(10, 'accepte', '2024-08-06', 4, '48045ggloyi'),
(11, 'accepte', '2024-08-06', 7, '48045ggloyi'),
(12, 'accepte', '2024-08-06', 6, '48045ggloyi'),
(13, 'accepte', '2024-08-06', 2, '48045ggloyi');

-- --------------------------------------------------------

--
-- Structure de la table `disponibilite`
--

CREATE TABLE `disponibilite` (
  `enseignant_id` varchar(50) DEFAULT NULL,
  `etat` varchar(20) DEFAULT NULL,
  `heure` int(11) DEFAULT NULL,
  `jour` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `disponibilite`
--

INSERT INTO `disponibilite` (`enseignant_id`, `etat`, `heure`, `jour`) VALUES
('A2001', 'dispo', 8, 'lundi'),
('A2001', 'dispo', 9, 'lundi'),
('A2001', 'dispo', 10, 'lundi'),
('A2001', 'dispo', 11, 'lundi'),
('A2001', 'dispo', 12, 'lundi'),
('A2001', 'dispo', 13, 'lundi'),
('A2001', 'dispo', 14, 'lundi'),
('A2001', 'dispo', 15, 'lundi'),
('A2001', 'dispo', 16, 'lundi'),
('A2001', 'dispo', 17, 'lundi'),
('A2001', 'dispo', 8, 'mardi'),
('A2001', 'dispo', 9, 'mardi'),
('A2001', 'dispo', 10, 'mardi'),
('A2001', 'dispo', 11, 'mardi'),
('A2001', 'dispo', 12, 'mardi'),
('A2001', 'dispo', 13, 'mardi'),
('A2001', 'dispo', 14, 'mardi'),
('A2001', 'dispo', 15, 'mardi'),
('A2001', 'dispo', 16, 'mardi'),
('A2001', 'dispo', 17, 'mardi'),
('A2001', 'dispo', 8, 'mercredi'),
('A2001', 'dispo', 9, 'mercredi'),
('A2001', 'dispo', 10, 'mercredi'),
('A2001', 'dispo', 11, 'mercredi'),
('A2001', 'dispo', 12, 'mercredi'),
('A2001', 'dispo', 13, 'mercredi'),
('A2001', 'dispo', 14, 'mercredi'),
('A2001', 'dispo', 15, 'mercredi'),
('A2001', 'dispo', 16, 'mercredi'),
('A2001', 'dispo', 17, 'mercredi'),
('A2001', 'dispo', 8, 'jeudi'),
('A2001', 'dispo', 9, 'jeudi'),
('A2001', 'dispo', 10, 'jeudi'),
('A2001', 'dispo', 11, 'jeudi'),
('A2001', 'dispo', 12, 'jeudi'),
('A2001', 'dispo', 13, 'jeudi'),
('A2001', 'dispo', 14, 'jeudi'),
('A2001', 'dispo', 15, 'jeudi'),
('A2001', 'dispo', 16, 'jeudi'),
('A2001', 'dispo', 17, 'jeudi'),
('A2001', 'dispo', 8, 'vendredi'),
('A2001', 'dispo', 9, 'vendredi'),
('A2001', 'dispo', 10, 'vendredi'),
('A2001', 'dispo', 11, 'vendredi'),
('A2001', 'dispo', 12, 'vendredi'),
('A2001', 'dispo', 13, 'vendredi'),
('A2001', 'dispo', 14, 'vendredi'),
('A2001', 'dispo', 15, 'vendredi'),
('A2001', 'dispo', 16, 'vendredi'),
('A2001', 'dispo', 17, 'vendredi'),
('A2001', 'dispo', 8, 'samedi'),
('A2001', 'dispo', 9, 'samedi'),
('A2001', 'dispo', 10, 'samedi'),
('A2001', 'dispo', 11, 'samedi'),
('A2001', 'dispo', 12, 'samedi'),
('A2001', 'dispo', 13, 'samedi'),
('A2001', 'dispo', 14, 'samedi'),
('A2001', 'dispo', 15, 'samedi'),
('A2001', 'dispo', 16, 'samedi'),
('A2001', 'dispo', 17, 'samedi'),
('A2003', 'dispo', 8, 'lundi'),
('A2003', 'dispo', 9, 'lundi'),
('A2003', 'dispo', 10, 'lundi'),
('A2003', 'dispo', 11, 'lundi'),
('A2003', 'dispo', 12, 'lundi'),
('A2003', 'dispo', 13, 'lundi'),
('A2003', 'dispo', 14, 'lundi'),
('A2003', 'dispo', 15, 'lundi'),
('A2003', 'dispo', 16, 'lundi'),
('A2003', 'dispo', 17, 'lundi'),
('A2003', 'dispo', 8, 'mardi'),
('A2003', 'dispo', 9, 'mardi'),
('A2003', 'dispo', 10, 'mardi'),
('A2003', 'dispo', 11, 'mardi'),
('A2003', 'dispo', 12, 'mardi'),
('A2003', 'dispo', 13, 'mardi'),
('A2003', 'dispo', 14, 'mardi'),
('A2003', 'dispo', 15, 'mardi'),
('A2003', 'dispo', 16, 'mardi'),
('A2003', 'dispo', 17, 'mardi'),
('A2003', 'dispo', 8, 'mercredi'),
('A2003', 'dispo', 9, 'mercredi'),
('A2003', 'dispo', 10, 'mercredi'),
('A2003', 'dispo', 11, 'mercredi'),
('A2003', 'dispo', 12, 'mercredi'),
('A2003', 'dispo', 13, 'mercredi'),
('A2003', 'dispo', 14, 'mercredi'),
('A2003', 'dispo', 15, 'mercredi'),
('A2003', 'dispo', 16, 'mercredi'),
('A2003', 'dispo', 17, 'mercredi'),
('A2003', 'dispo', 8, 'jeudi'),
('A2003', 'dispo', 9, 'jeudi'),
('A2003', 'dispo', 10, 'jeudi'),
('A2003', 'dispo', 11, 'jeudi'),
('A2003', 'dispo', 12, 'jeudi'),
('A2003', 'dispo', 13, 'jeudi'),
('A2003', 'dispo', 14, 'jeudi'),
('A2003', 'dispo', 15, 'jeudi'),
('A2003', 'dispo', 16, 'jeudi'),
('A2003', 'dispo', 17, 'jeudi'),
('A2003', 'dispo', 8, 'vendredi'),
('A2003', 'dispo', 9, 'vendredi'),
('A2003', 'dispo', 10, 'vendredi'),
('A2003', 'dispo', 11, 'vendredi'),
('A2003', 'dispo', 12, 'vendredi'),
('A2003', 'dispo', 13, 'vendredi'),
('A2003', 'dispo', 14, 'vendredi'),
('A2003', 'dispo', 15, 'vendredi'),
('A2003', 'dispo', 16, 'vendredi'),
('A2003', 'dispo', 17, 'vendredi'),
('A2003', 'dispo', 8, 'samedi'),
('A2003', 'dispo', 9, 'samedi'),
('A2003', 'dispo', 10, 'samedi'),
('A2003', 'dispo', 11, 'samedi'),
('A2003', 'dispo', 12, 'samedi'),
('A2003', 'dispo', 13, 'samedi'),
('A2003', 'dispo', 14, 'samedi'),
('A2003', 'dispo', 15, 'samedi'),
('A2003', 'dispo', 16, 'samedi'),
('A2003', 'dispo', 17, 'samedi'),
('A2001', 'dispo', 8, 'lundi'),
('A2001', 'dispo', 9, 'lundi'),
('A2001', 'dispo', 10, 'lundi'),
('A2001', 'dispo', 11, 'lundi'),
('A2001', 'dispo', 12, 'lundi'),
('A2001', 'dispo', 13, 'lundi'),
('A2001', 'dispo', 14, 'lundi'),
('A2001', 'dispo', 15, 'lundi'),
('A2001', 'dispo', 16, 'lundi'),
('A2001', 'dispo', 17, 'lundi'),
('A2001', 'dispo', 8, 'mardi'),
('A2001', 'dispo', 9, 'mardi'),
('A2001', 'dispo', 10, 'mardi'),
('A2001', 'dispo', 11, 'mardi'),
('A2001', 'dispo', 12, 'mardi'),
('A2001', 'dispo', 13, 'mardi'),
('A2001', 'dispo', 14, 'mardi'),
('A2001', 'dispo', 15, 'mardi'),
('A2001', 'dispo', 16, 'mardi'),
('A2001', 'dispo', 17, 'mardi'),
('A2001', 'dispo', 8, 'mercredi'),
('A2001', 'dispo', 9, 'mercredi'),
('A2001', 'dispo', 10, 'mercredi'),
('A2001', 'dispo', 11, 'mercredi'),
('A2001', 'dispo', 12, 'mercredi'),
('A2001', 'dispo', 13, 'mercredi'),
('A2001', 'dispo', 14, 'mercredi'),
('A2001', 'dispo', 15, 'mercredi'),
('A2001', 'dispo', 16, 'mercredi'),
('A2001', 'dispo', 17, 'mercredi'),
('A2001', 'dispo', 8, 'jeudi'),
('A2001', 'dispo', 9, 'jeudi'),
('A2001', 'dispo', 10, 'jeudi'),
('A2001', 'dispo', 11, 'jeudi'),
('A2001', 'dispo', 12, 'jeudi'),
('A2001', 'dispo', 13, 'jeudi'),
('A2001', 'dispo', 14, 'jeudi'),
('A2001', 'dispo', 15, 'jeudi'),
('A2001', 'dispo', 16, 'jeudi'),
('A2001', 'dispo', 17, 'jeudi'),
('A2001', 'dispo', 8, 'vendredi'),
('A2001', 'dispo', 9, 'vendredi'),
('A2001', 'dispo', 10, 'vendredi'),
('A2001', 'dispo', 11, 'vendredi'),
('A2001', 'dispo', 12, 'vendredi'),
('A2001', 'dispo', 13, 'vendredi'),
('A2001', 'dispo', 14, 'vendredi'),
('A2001', 'dispo', 15, 'vendredi'),
('A2001', 'dispo', 16, 'vendredi'),
('A2001', 'dispo', 17, 'vendredi'),
('A2001', 'dispo', 8, 'samedi'),
('A2001', 'dispo', 9, 'samedi'),
('A2001', 'dispo', 10, 'samedi'),
('A2001', 'dispo', 11, 'samedi'),
('A2001', 'dispo', 12, 'samedi'),
('A2001', 'dispo', 13, 'samedi'),
('A2001', 'dispo', 14, 'samedi'),
('A2001', 'dispo', 15, 'samedi'),
('A2001', 'dispo', 16, 'samedi'),
('A2001', 'dispo', 17, 'samedi');

-- --------------------------------------------------------

--
-- Structure de la table `enseignant`
--

CREATE TABLE `enseignant` (
  `utilisateur_id` varchar(50) NOT NULL,
  `specialite` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `enseignant`
--

INSERT INTO `enseignant` (`utilisateur_id`, `specialite`) VALUES
('A2001', 'programmation'),
('A2003', 'devOps');

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `utilisateur_id` varchar(50) NOT NULL,
  `classe_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`utilisateur_id`, `classe_id`) VALUES
('48045ggloyi', 2);

-- --------------------------------------------------------

--
-- Structure de la table `gestionnaire`
--

CREATE TABLE `gestionnaire` (
  `utilisateur` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `gestionnaire`
--

INSERT INTO `gestionnaire` (`utilisateur`) VALUES
('222PP02');

-- --------------------------------------------------------

--
-- Structure de la table `module`
--

CREATE TABLE `module` (
  `id` int(11) NOT NULL,
  `nom_module` varchar(100) DEFAULT NULL,
  `volume_horaire` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `module`
--

INSERT INTO `module` (`id`, `nom_module`, `volume_horaire`) VALUES
(1, 'devOps', 20),
(2, 'Symfony', 20),
(3, 'Laravel', 20),
(4, 'fluter', 20);

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `id_receveur` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `lu` tinyint(1) DEFAULT NULL,
  `motif` varchar(100) DEFAULT NULL,
  `titre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE `salle` (
  `id` int(11) NOT NULL,
  `nom_salle` varchar(100) DEFAULT NULL,
  `capacite` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`id`, `nom_salle`, `capacite`) VALUES
(3, 'RC3', 50),
(6, 'RC4', 50),
(7, 'RC1', 50),
(8, 'RC2', 50);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `matricule` varchar(50) NOT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mdp` varchar(100) DEFAULT NULL,
  `date_naiss` date DEFAULT NULL,
  `lieu_naiss` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`matricule`, `prenom`, `nom`, `telephone`, `email`, `mdp`, `date_naiss`, `lieu_naiss`, `status`) VALUES
('222PP02', 'IBRAHMA', 'DIOP', '765493873', 'DIOP0087@gmail.com', 'default', '2024-08-13', 'dakar', 'gestionnaire'),
('48045ggloyi', 'modou', 'fall', '776543445', 'fall30@gmail.com', 'default', '2024-08-30', 'dakar', 'etudiant'),
('A2001', 'Amadou', 'Dieng', '760205850', 'Dieng0097@gmail.com', 'default', '2024-08-11', 'dakar', 'enseignant'),
('A2003', 'sidi', 'niang', '776837493', 'sidi0020@gmail.com', 'default', '2024-08-15', 'dakar', 'enseignant'),
('admin', 'admin', 'admin', '776456738', 'admin@admin.com', 'admin', '2024-08-13', 'kaolack', 'admin');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`utilisateur_id`);

--
-- Index pour la table `classe`
--
ALTER TABLE `classe`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `classe_module`
--
ALTER TABLE `classe_module`
  ADD PRIMARY KEY (`id_clm`),
  ADD KEY `id_classe` (`id_classe`),
  ADD KEY `id_module` (`id_module`),
  ADD KEY `id_prof` (`id_prof`);

--
-- Index pour la table `cours`
--
ALTER TABLE `cours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classe_id` (`classe_id`),
  ADD KEY `enseignant_id` (`enseignant_id`),
  ADD KEY `module_id` (`module_id`),
  ADD KEY `salle_id` (`salle_id`);

--
-- Index pour la table `demande_inscription`
--
ALTER TABLE `demande_inscription`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_classe` (`id_classe`);

--
-- Index pour la table `enseignant`
--
ALTER TABLE `enseignant`
  ADD PRIMARY KEY (`utilisateur_id`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`utilisateur_id`);

--
-- Index pour la table `gestionnaire`
--
ALTER TABLE `gestionnaire`
  ADD PRIMARY KEY (`utilisateur`);

--
-- Index pour la table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`matricule`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `classe`
--
ALTER TABLE `classe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `classe_module`
--
ALTER TABLE `classe_module`
  MODIFY `id_clm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `cours`
--
ALTER TABLE `cours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `demande_inscription`
--
ALTER TABLE `demande_inscription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `module`
--
ALTER TABLE `module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `salle`
--
ALTER TABLE `salle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD CONSTRAINT `administrateur_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`matricule`);

--
-- Contraintes pour la table `classe_module`
--
ALTER TABLE `classe_module`
  ADD CONSTRAINT `classe_module_ibfk_1` FOREIGN KEY (`id_classe`) REFERENCES `classe` (`id`),
  ADD CONSTRAINT `classe_module_ibfk_2` FOREIGN KEY (`id_module`) REFERENCES `module` (`id`),
  ADD CONSTRAINT `classe_module_ibfk_3` FOREIGN KEY (`id_prof`) REFERENCES `enseignant` (`utilisateur_id`);

--
-- Contraintes pour la table `cours`
--
ALTER TABLE `cours`
  ADD CONSTRAINT `cours_ibfk_1` FOREIGN KEY (`classe_id`) REFERENCES `classe` (`id`),
  ADD CONSTRAINT `cours_ibfk_2` FOREIGN KEY (`enseignant_id`) REFERENCES `enseignant` (`utilisateur_id`),
  ADD CONSTRAINT `cours_ibfk_3` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`),
  ADD CONSTRAINT `cours_ibfk_4` FOREIGN KEY (`salle_id`) REFERENCES `salle` (`id`);

--
-- Contraintes pour la table `demande_inscription`
--
ALTER TABLE `demande_inscription`
  ADD CONSTRAINT `demande_inscription_ibfk_1` FOREIGN KEY (`id_classe`) REFERENCES `classe` (`id`);

--
-- Contraintes pour la table `enseignant`
--
ALTER TABLE `enseignant`
  ADD CONSTRAINT `enseignant_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`matricule`);

--
-- Contraintes pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD CONSTRAINT `etudiant_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`matricule`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
