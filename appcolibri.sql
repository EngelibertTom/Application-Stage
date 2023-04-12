-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 20 avr. 2022 à 11:30
-- Version du serveur : 8.0.27
-- Version de PHP : 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `appcolibri`
--

-- --------------------------------------------------------

--
-- Structure de la table `adoption`
--

DROP TABLE IF EXISTS `adoption`;
CREATE TABLE IF NOT EXISTS `adoption` (
  `id` int NOT NULL AUTO_INCREMENT,
  `owner_id` int NOT NULL,
  `tree_id` int NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_EDDEB6A978B64A2` (`tree_id`),
  KEY `IDX_EDDEB6A97E3C61F9` (`owner_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_64C19C15E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`, `size`, `description`) VALUES
(2, 'Shito', '7.5 cm', 'Bonsaï miniature de 2,5 à 7,5 cm'),
(3, 'Mame', '7.5 - 13 cm', 'Sous-division des Shohin de 7,5cm à 15 cm'),
(4, 'Shohin', '13 - 20 cm', 'Petit bonsaï, de 15cm à 20cm'),
(5, 'Kifu', '20 - 40 cm', 'Bonsaï de 20 à 40cm'),
(7, 'Chuhin', '40 - 60 cm', 'Bonsaï de taille moyenne de 40cm à 60cm'),
(8, 'Daihin', '60 - 130 cm', 'Grand bonsaï de 60cm à 100cm');

-- --------------------------------------------------------

--
-- Structure de la table `column_row`
--

DROP TABLE IF EXISTS `column_row`;
CREATE TABLE IF NOT EXISTS `column_row` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `column_row`
--

INSERT INTO `column_row` (`id`, `name`) VALUES
(1, '1'),
(2, '2'),
(3, '3'),
(4, '4'),
(5, '5'),
(6, '6'),
(7, '7'),
(8, '8'),
(9, '9'),
(10, '10'),
(11, '11'),
(12, '12'),
(13, '13'),
(14, '14'),
(15, '15'),
(16, '16'),
(17, '17'),
(18, '18'),
(19, '19'),
(20, '20');

-- --------------------------------------------------------

--
-- Structure de la table `culture_table`
--

DROP TABLE IF EXISTS `culture_table`;
CREATE TABLE IF NOT EXISTS `culture_table` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `culture_table`
--

INSERT INTO `culture_table` (`id`, `name`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C');

-- --------------------------------------------------------

--
-- Structure de la table `culture_tables`
--

DROP TABLE IF EXISTS `culture_tables`;
CREATE TABLE IF NOT EXISTS `culture_tables` (
  `id` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `dead_tree`
--

DROP TABLE IF EXISTS `dead_tree`;
CREATE TABLE IF NOT EXISTS `dead_tree` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tree_id` int NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_ED10B91E78B64A2` (`tree_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `equipment`
--

DROP TABLE IF EXISTS `equipment`;
CREATE TABLE IF NOT EXISTS `equipment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `greenhouse`
--

DROP TABLE IF EXISTS `greenhouse`;
CREATE TABLE IF NOT EXISTS `greenhouse` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nursery_id` int NOT NULL,
  `location_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_DC68F11BF17958065E237E06` (`nursery_id`,`name`),
  KEY `IDX_DC68F11BF1795806` (`nursery_id`),
  KEY `IDX_DC68F11B64D218E` (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `greenhouse_culture_table`
--

DROP TABLE IF EXISTS `greenhouse_culture_table`;
CREATE TABLE IF NOT EXISTS `greenhouse_culture_table` (
  `greenhouse_id` int NOT NULL,
  `culture_table_id` int NOT NULL,
  PRIMARY KEY (`greenhouse_id`,`culture_table_id`),
  KEY `IDX_8F1D319438FCB0EB` (`greenhouse_id`),
  KEY `IDX_8F1D31941CD3B561` (`culture_table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `history_tree`
--

DROP TABLE IF EXISTS `history_tree`;
CREATE TABLE IF NOT EXISTS `history_tree` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tree_id` int NOT NULL,
  `user_id` int NOT NULL,
  `type_id` int NOT NULL,
  `date` date NOT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visible_public` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BC0B2EA078B64A2` (`tree_id`),
  KEY `IDX_BC0B2EA0A76ED395` (`user_id`),
  KEY `IDX_BC0B2EA0C54C8C93` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `leaf_type`
--

DROP TABLE IF EXISTS `leaf_type`;
CREATE TABLE IF NOT EXISTS `leaf_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_20E0B3F85E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `leaf_type`
--

INSERT INTO `leaf_type` (`id`, `name`) VALUES
(3, 'Caduque'),
(2, 'Marcescent'),
(1, 'Persistant');

-- --------------------------------------------------------

--
-- Structure de la table `location`
--

DROP TABLE IF EXISTS `location`;
CREATE TABLE IF NOT EXISTS `location` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nursery_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5E9E89CBF17958065E237E06` (`nursery_id`,`name`),
  KEY `IDX_5E9E89CBF1795806` (`nursery_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `location`
--

INSERT INTO `location` (`id`, `nursery_id`, `name`) VALUES
(1, 1, 'Accueil'),
(7, 1, 'Arboretum'),
(6, 1, 'Arboretum conservation'),
(5, 1, 'Culture'),
(2, 1, 'Green booster'),
(3, 1, 'Nurserie'),
(4, 1, 'Quarantaine');

-- --------------------------------------------------------

--
-- Structure de la table `lot`
--

DROP TABLE IF EXISTS `lot`;
CREATE TABLE IF NOT EXISTS `lot` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nursery_id` int NOT NULL,
  `recovery_type_id` int DEFAULT NULL,
  `qr_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `place` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recovery_cause` longtext COLLATE utf8mb4_unicode_ci,
  `age_recovery` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B81291BF1795806` (`nursery_id`),
  KEY `IDX_B81291B8C950F1C` (`recovery_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lot_picture`
--

DROP TABLE IF EXISTS `lot_picture`;
CREATE TABLE IF NOT EXISTS `lot_picture` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lot_id` int NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path_original` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BB5B09AAA8CBA5F7` (`lot_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lot_species`
--

DROP TABLE IF EXISTS `lot_species`;
CREATE TABLE IF NOT EXISTS `lot_species` (
  `lot_id` int NOT NULL,
  `species_id` int NOT NULL,
  PRIMARY KEY (`lot_id`,`species_id`),
  KEY `IDX_88FB131A8CBA5F7` (`lot_id`),
  KEY `IDX_88FB131B2A1D860` (`species_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `management_nursery`
--

DROP TABLE IF EXISTS `management_nursery`;
CREATE TABLE IF NOT EXISTS `management_nursery` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `nursery_id` int NOT NULL,
  `default_nursery` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E6D921E4A76ED395` (`user_id`),
  KEY `IDX_E6D921E4F1795806` (`nursery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `nursery`
--

DROP TABLE IF EXISTS `nursery`;
CREATE TABLE IF NOT EXISTS `nursery` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `nursery`
--

INSERT INTO `nursery` (`id`, `name`, `city`, `postal_code`, `email`, `phone`) VALUES
(1, 'Bonsaï la part du colibri', 'Provin', '59185', 'contact@bonsailapartducolibri.org', '06 83 63 50 08');

-- --------------------------------------------------------

--
-- Structure de la table `observation`
--

DROP TABLE IF EXISTS `observation`;
CREATE TABLE IF NOT EXISTS `observation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tree_id` int NOT NULL,
  `user_id` int NOT NULL,
  `date` date NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_C576DBE078B64A2` (`tree_id`),
  KEY `IDX_C576DBE0A76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `output_type`
--

DROP TABLE IF EXISTS `output_type`;
CREATE TABLE IF NOT EXISTS `output_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `output_type`
--

INSERT INTO `output_type` (`id`, `name`) VALUES
(1, 'Adoption'),
(2, 'Mort');

-- --------------------------------------------------------

--
-- Structure de la table `owner`
--

DROP TABLE IF EXISTS `owner`;
CREATE TABLE IF NOT EXISTS `owner` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_connection` datetime DEFAULT NULL,
  `postal_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_CF60E67CE7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pot_type`
--

DROP TABLE IF EXISTS `pot_type`;
CREATE TABLE IF NOT EXISTS `pot_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diameter` double NOT NULL,
  `height` double DEFAULT NULL,
  `capacity` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `recovery_type`
--

DROP TABLE IF EXISTS `recovery_type`;
CREATE TABLE IF NOT EXISTS `recovery_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D74662355E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `segment`
--

DROP TABLE IF EXISTS `segment`;
CREATE TABLE IF NOT EXISTS `segment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `species`
--

DROP TABLE IF EXISTS `species`;
CREATE TABLE IF NOT EXISTS `species` (
  `id` int NOT NULL AUTO_INCREMENT,
  `status_uicn_id` int DEFAULT NULL,
  `leaf_type_id` int DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latin_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recommended_substrate` longtext COLLATE utf8mb4_unicode_ci,
  `recommended_soil_moisture` int DEFAULT NULL,
  `recommended_acidity_min` int DEFAULT NULL,
  `recommended_acidity_max` int DEFAULT NULL,
  `fertilizer_need` int DEFAULT NULL,
  `flowering_month` int DEFAULT NULL,
  `recommended_exposure` int DEFAULT NULL,
  `validate` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_A50FF7125E237E06` (`name`),
  UNIQUE KEY `UNIQ_A50FF712A2A71819` (`latin_name`),
  KEY `IDX_A50FF712987C986A` (`status_uicn_id`),
  KEY `IDX_A50FF712B274F87B` (`leaf_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `species`
--

INSERT INTO `species` (`id`, `status_uicn_id`, `leaf_type_id`, `name`, `latin_name`, `recommended_substrate`, `recommended_soil_moisture`, `recommended_acidity_min`, `recommended_acidity_max`, `fertilizer_need`, `flowering_month`, `recommended_exposure`, `validate`) VALUES
(1, 12, 3, 'Buis', 'buxus', NULL, NULL, 5, 6, NULL, NULL, NULL, 1),
(3, 12, 3, 'Pin noir', 'Pinus nigra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(4, 12, 3, 'Genévrier', 'Juniperus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(5, 12, 3, 'Orme', 'Ulmus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(6, 12, 3, 'Erable', 'Acer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(7, 8, 3, 'Ginkgo biloba', 'Ginkgo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(8, 12, 3, 'Tilleul/Tilia', 'tilius', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(10, 12, 3, 'Cornouiller sanguin', 'Cornus sanguinea', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(11, 11, 3, 'Chêne', 'Quercus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(12, 12, 3, 'Épicéa commun', 'Picea abies', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(13, 12, 3, 'Sorbier', 'Sorbus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(14, 12, 3, 'Cognassier du Japon', 'Chaenomeles japonica', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(15, 12, 3, 'Mélèze', 'Larix decidua', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(16, 12, 3, 'Pin blanc', 'Pinus strobus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(17, 12, 3, 'Pin', 'Pinus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(18, 12, 3, 'Pin mugo', 'Pinus mugo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(19, 12, 3, 'If commun', 'Taxus baccata', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(20, 12, 3, 'Forsythia', 'Forsythia', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(21, 12, 3, 'Fusain d\'Europe', 'Euonymus europaeus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(22, 12, 3, 'Troëne', 'Ligustrum lucidum', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(23, 5, 3, 'Lilas', 'Lilas', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(24, 9, 3, 'Chèvrefeuille', 'Lonicera', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(25, 12, 3, 'Sapin blanc, sapin pectiné, sapin commun', 'Abies alba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(26, 12, 3, 'Aubépine', 'Crataegus', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(27, 12, 3, 'Sapin', 'Abies', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `status_uicn`
--

DROP TABLE IF EXISTS `status_uicn`;
CREATE TABLE IF NOT EXISTS `status_uicn` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E67FA6B25E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `status_uicn`
--

INSERT INTO `status_uicn` (`id`, `name`, `description`) VALUES
(1, 'EX', 'Éteint'),
(2, 'PE', 'Peut-être éteint'),
(3, 'EW', 'Éteint à l\'état sauvage'),
(4, 'PEW', 'Peut-être éteint à l\'état sauvage'),
(5, 'NE', 'Non évalué'),
(6, 'DD', 'Données insuffisantes'),
(7, 'CR', 'En danger critique d\'extinction'),
(8, 'EN', 'En danger'),
(9, 'VU', 'Vulnérable'),
(10, 'CD', 'Conservation Dependent'),
(11, 'NT', 'Quasi menacé'),
(12, 'LC', 'Préoccupation mineure');

-- --------------------------------------------------------

--
-- Structure de la table `style`
--

DROP TABLE IF EXISTS `style`;
CREATE TABLE IF NOT EXISTS `style` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_33BDB86A5E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `table_column`
--

DROP TABLE IF EXISTS `table_column`;
CREATE TABLE IF NOT EXISTS `table_column` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tree`
--

DROP TABLE IF EXISTS `tree`;
CREATE TABLE IF NOT EXISTS `tree` (
  `id` int NOT NULL AUTO_INCREMENT,
  `species_id` int DEFAULT NULL,
  `position_id` int DEFAULT NULL,
  `output_type_id` int DEFAULT NULL,
  `lot_id` int DEFAULT NULL,
  `nursery_id` int NOT NULL,
  `pot_type_id` int DEFAULT NULL,
  `greenhouse_id` int DEFAULT NULL,
  `culture_table_id` int DEFAULT NULL,
  `segment_id` int DEFAULT NULL,
  `table_column_id` int DEFAULT NULL,
  `column_row_id` int DEFAULT NULL,
  `qr_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age_recovery` int DEFAULT NULL,
  `output` tinyint(1) NOT NULL,
  `height` double DEFAULT NULL,
  `nebari_diameter` double DEFAULT NULL,
  `trunk_diameter` double DEFAULT NULL,
  `status` int NOT NULL,
  `working_year` int DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B73E5EDCB2A1D860` (`species_id`),
  KEY `IDX_B73E5EDCDD842E46` (`position_id`),
  KEY `IDX_B73E5EDC1C4E0B75` (`output_type_id`),
  KEY `IDX_B73E5EDCA8CBA5F7` (`lot_id`),
  KEY `IDX_B73E5EDCF1795806` (`nursery_id`),
  KEY `IDX_B73E5EDCCC209CBC` (`pot_type_id`),
  KEY `IDX_B73E5EDC38FCB0EB` (`greenhouse_id`),
  KEY `IDX_B73E5EDC1CD3B561` (`culture_table_id`),
  KEY `IDX_B73E5EDCDB296AAD` (`segment_id`),
  KEY `IDX_B73E5EDC38CDE12F` (`table_column_id`),
  KEY `IDX_B73E5EDC2395CC3D` (`column_row_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tree_category`
--

DROP TABLE IF EXISTS `tree_category`;
CREATE TABLE IF NOT EXISTS `tree_category` (
  `tree_id` int NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`tree_id`,`category_id`),
  KEY `IDX_6580F2BA78B64A2` (`tree_id`),
  KEY `IDX_6580F2BA12469DE2` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tree_growth`
--

DROP TABLE IF EXISTS `tree_growth`;
CREATE TABLE IF NOT EXISTS `tree_growth` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tree_id` int NOT NULL,
  `date` date NOT NULL,
  `height` double DEFAULT NULL,
  `nebari_diameter` double DEFAULT NULL,
  `trunk_diameter` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2080760778B64A2` (`tree_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tree_picture`
--

DROP TABLE IF EXISTS `tree_picture`;
CREATE TABLE IF NOT EXISTS `tree_picture` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tree_id` int NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `path_original` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DC5CED9478B64A2` (`tree_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tree_style`
--

DROP TABLE IF EXISTS `tree_style`;
CREATE TABLE IF NOT EXISTS `tree_style` (
  `tree_id` int NOT NULL,
  `style_id` int NOT NULL,
  PRIMARY KEY (`tree_id`,`style_id`),
  KEY `IDX_8BBF9E0F78B64A2` (`tree_id`),
  KEY `IDX_8BBF9E0FBACD6074` (`style_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tree_work`
--

DROP TABLE IF EXISTS `tree_work`;
CREATE TABLE IF NOT EXISTS `tree_work` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `tree_id` int NOT NULL,
  `work_id` int NOT NULL,
  `date` date NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci,
  `todo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_ECCD02B7A76ED395` (`user_id`),
  KEY `IDX_ECCD02B778B64A2` (`tree_id`),
  KEY `IDX_ECCD02B7BB3453DB` (`work_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_history_tree`
--

DROP TABLE IF EXISTS `type_history_tree`;
CREATE TABLE IF NOT EXISTS `type_history_tree` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_color` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `roles`, `active`) VALUES
(1, 'lucas', 'lucas0000.milow@gmail.com', 'Eti36Ru/pWG6WfoIPiDFUBxUuyvgMA4L8+LLuGbGyqV9ATuT9brCWPchBqX5vFTF+DgntacecW+sSGD+GZts2A==', 'a:1:{i:0;s:16:\"ROLE_SUPER_ADMIN\";}', 1);

-- --------------------------------------------------------

--
-- Structure de la table `user_nursery`
--

DROP TABLE IF EXISTS `user_nursery`;
CREATE TABLE IF NOT EXISTS `user_nursery` (
  `user_id` int NOT NULL,
  `nursery_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`nursery_id`),
  KEY `IDX_C5530D44A76ED395` (`user_id`),
  KEY `IDX_C5530D44F1795806` (`nursery_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `work`
--

DROP TABLE IF EXISTS `work`;
CREATE TABLE IF NOT EXISTS `work` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `adoption`
--
ALTER TABLE `adoption`
  ADD CONSTRAINT `FK_EDDEB6A978B64A2` FOREIGN KEY (`tree_id`) REFERENCES `tree` (`id`),
  ADD CONSTRAINT `FK_EDDEB6A97E3C61F9` FOREIGN KEY (`owner_id`) REFERENCES `owner` (`id`);

--
-- Contraintes pour la table `dead_tree`
--
ALTER TABLE `dead_tree`
  ADD CONSTRAINT `FK_ED10B91E78B64A2` FOREIGN KEY (`tree_id`) REFERENCES `tree` (`id`);

--
-- Contraintes pour la table `greenhouse`
--
ALTER TABLE `greenhouse`
  ADD CONSTRAINT `FK_DC68F11B64D218E` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`),
  ADD CONSTRAINT `FK_DC68F11BF1795806` FOREIGN KEY (`nursery_id`) REFERENCES `nursery` (`id`);

--
-- Contraintes pour la table `greenhouse_culture_table`
--
ALTER TABLE `greenhouse_culture_table`
  ADD CONSTRAINT `FK_8F1D31941CD3B561` FOREIGN KEY (`culture_table_id`) REFERENCES `culture_table` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_8F1D319438FCB0EB` FOREIGN KEY (`greenhouse_id`) REFERENCES `greenhouse` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `history_tree`
--
ALTER TABLE `history_tree`
  ADD CONSTRAINT `FK_BC0B2EA078B64A2` FOREIGN KEY (`tree_id`) REFERENCES `tree` (`id`),
  ADD CONSTRAINT `FK_BC0B2EA0A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_BC0B2EA0C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `type_history_tree` (`id`);

--
-- Contraintes pour la table `location`
--
ALTER TABLE `location`
  ADD CONSTRAINT `FK_5E9E89CBF1795806` FOREIGN KEY (`nursery_id`) REFERENCES `nursery` (`id`);

--
-- Contraintes pour la table `lot`
--
ALTER TABLE `lot`
  ADD CONSTRAINT `FK_B81291B8C950F1C` FOREIGN KEY (`recovery_type_id`) REFERENCES `recovery_type` (`id`),
  ADD CONSTRAINT `FK_B81291BF1795806` FOREIGN KEY (`nursery_id`) REFERENCES `nursery` (`id`);

--
-- Contraintes pour la table `lot_picture`
--
ALTER TABLE `lot_picture`
  ADD CONSTRAINT `FK_BB5B09AAA8CBA5F7` FOREIGN KEY (`lot_id`) REFERENCES `lot` (`id`);

--
-- Contraintes pour la table `lot_species`
--
ALTER TABLE `lot_species`
  ADD CONSTRAINT `FK_88FB131A8CBA5F7` FOREIGN KEY (`lot_id`) REFERENCES `lot` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_88FB131B2A1D860` FOREIGN KEY (`species_id`) REFERENCES `species` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `management_nursery`
--
ALTER TABLE `management_nursery`
  ADD CONSTRAINT `FK_E6D921E4A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_E6D921E4F1795806` FOREIGN KEY (`nursery_id`) REFERENCES `nursery` (`id`);

--
-- Contraintes pour la table `observation`
--
ALTER TABLE `observation`
  ADD CONSTRAINT `FK_C576DBE078B64A2` FOREIGN KEY (`tree_id`) REFERENCES `tree` (`id`),
  ADD CONSTRAINT `FK_C576DBE0A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `species`
--
ALTER TABLE `species`
  ADD CONSTRAINT `FK_A50FF712987C986A` FOREIGN KEY (`status_uicn_id`) REFERENCES `status_uicn` (`id`),
  ADD CONSTRAINT `FK_A50FF712B274F87B` FOREIGN KEY (`leaf_type_id`) REFERENCES `leaf_type` (`id`);

--
-- Contraintes pour la table `tree`
--
ALTER TABLE `tree`
  ADD CONSTRAINT `FK_B73E5EDC1C4E0B75` FOREIGN KEY (`output_type_id`) REFERENCES `output_type` (`id`),
  ADD CONSTRAINT `FK_B73E5EDC1CD3B561` FOREIGN KEY (`culture_table_id`) REFERENCES `culture_table` (`id`),
  ADD CONSTRAINT `FK_B73E5EDC2395CC3D` FOREIGN KEY (`column_row_id`) REFERENCES `column_row` (`id`),
  ADD CONSTRAINT `FK_B73E5EDC38CDE12F` FOREIGN KEY (`table_column_id`) REFERENCES `table_column` (`id`),
  ADD CONSTRAINT `FK_B73E5EDC38FCB0EB` FOREIGN KEY (`greenhouse_id`) REFERENCES `greenhouse` (`id`),
  ADD CONSTRAINT `FK_B73E5EDCA8CBA5F7` FOREIGN KEY (`lot_id`) REFERENCES `lot` (`id`),
  ADD CONSTRAINT `FK_B73E5EDCB2A1D860` FOREIGN KEY (`species_id`) REFERENCES `species` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_B73E5EDCCC209CBC` FOREIGN KEY (`pot_type_id`) REFERENCES `pot_type` (`id`),
  ADD CONSTRAINT `FK_B73E5EDCDB296AAD` FOREIGN KEY (`segment_id`) REFERENCES `segment` (`id`),
  ADD CONSTRAINT `FK_B73E5EDCDD842E46` FOREIGN KEY (`position_id`) REFERENCES `column_row` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_B73E5EDCF1795806` FOREIGN KEY (`nursery_id`) REFERENCES `nursery` (`id`);

--
-- Contraintes pour la table `tree_category`
--
ALTER TABLE `tree_category`
  ADD CONSTRAINT `FK_6580F2BA12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_6580F2BA78B64A2` FOREIGN KEY (`tree_id`) REFERENCES `tree` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `tree_growth`
--
ALTER TABLE `tree_growth`
  ADD CONSTRAINT `FK_2080760778B64A2` FOREIGN KEY (`tree_id`) REFERENCES `tree` (`id`);

--
-- Contraintes pour la table `tree_picture`
--
ALTER TABLE `tree_picture`
  ADD CONSTRAINT `FK_DC5CED9478B64A2` FOREIGN KEY (`tree_id`) REFERENCES `tree` (`id`);

--
-- Contraintes pour la table `tree_style`
--
ALTER TABLE `tree_style`
  ADD CONSTRAINT `FK_8BBF9E0F78B64A2` FOREIGN KEY (`tree_id`) REFERENCES `tree` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_8BBF9E0FBACD6074` FOREIGN KEY (`style_id`) REFERENCES `style` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `tree_work`
--
ALTER TABLE `tree_work`
  ADD CONSTRAINT `FK_ECCD02B778B64A2` FOREIGN KEY (`tree_id`) REFERENCES `tree` (`id`),
  ADD CONSTRAINT `FK_ECCD02B7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_ECCD02B7BB3453DB` FOREIGN KEY (`work_id`) REFERENCES `work` (`id`);

--
-- Contraintes pour la table `user_nursery`
--
ALTER TABLE `user_nursery`
  ADD CONSTRAINT `FK_C5530D44A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_C5530D44F1795806` FOREIGN KEY (`nursery_id`) REFERENCES `nursery` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
