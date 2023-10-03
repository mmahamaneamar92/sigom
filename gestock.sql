-- --------------------------------------------------------
-- Hôte :                        localhost
-- Version du serveur:           5.7.24 - MySQL Community Server (GPL)
-- SE du serveur:                Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Listage de la structure de la base pour gestock
CREATE DATABASE IF NOT EXISTS `gestock` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `gestock`;

-- Listage de la structure de la table gestock. failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table gestock.failed_jobs : ~0 rows (environ)
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

-- Listage de la structure de la table gestock. materiels
CREATE TABLE IF NOT EXISTS `materiels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `materiels_designation_unique` (`designation`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table gestock.materiels : ~11 rows (environ)
/*!40000 ALTER TABLE `materiels` DISABLE KEYS */;
INSERT INTO `materiels` (`id`, `designation`, `created_at`, `updated_at`) VALUES
	(2, 'ID Bag', '2023-09-09 23:44:31', '2023-09-11 23:22:41'),
	(3, 'Batteries', '2023-09-10 00:10:21', '2023-09-11 23:09:12'),
	(9, 'Câble Réseau', '2023-09-11 14:46:58', '2023-09-11 23:06:03'),
	(13, 'BSA1', '2023-09-11 17:15:50', '2023-09-11 17:15:50'),
	(14, 'Carte Mémoire', '2023-09-11 23:05:20', '2023-09-11 23:05:20'),
	(15, 'Caméra', '2023-09-11 23:13:14', '2023-09-11 23:13:14'),
	(16, 'Suprema', '2023-09-14 08:32:33', '2023-09-14 08:32:33'),
	(17, 'BSA', '2023-09-15 11:08:17', '2023-09-15 11:08:17'),
	(18, 'Souris', '2023-09-15 11:13:41', '2023-09-15 11:13:41'),
	(19, 'Clavier', '2023-09-15 11:14:55', '2023-09-15 11:14:55'),
	(20, 'CHECK ID', '2023-09-19 13:47:47', '2023-09-19 13:47:47');
/*!40000 ALTER TABLE `materiels` ENABLE KEYS */;

-- Listage de la structure de la table gestock. migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table gestock.migrations : ~12 rows (environ)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2023_09_07_092412_create_materiel_table', 2),
	(6, '2023_09_09_233945_create_materiel_table', 3),
	(7, '2023_09_13_115327_create_table_users_table_materiel_table', 4),
	(8, '2023_09_13_123905_create_table_users_table_materiel_table', 5),
	(9, '2023_09_13_130748_create_table_users_table_materiel_table', 6),
	(10, '2023_09_14_074734_create_table_users_table_materiel_table', 7),
	(11, '2023_09_14_075237_create_table_users_table_materiel_table', 8),
	(12, '2023_09_14_120518_create_table_users_table_materiel_table', 9),
	(13, '2023_09_15_152542_create_table_users_table_materiel_table', 10),
	(14, '2023_09_18_093436_create_reparations_table', 11);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Listage de la structure de la table gestock. password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table gestock.password_reset_tokens : ~0 rows (environ)
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
	('mmahamaneamar@gmail.com', '$2y$10$34KkXQtKUIkx9F/rSwgxVO4ZrawvHNdg/OmfIpFQIefmpF5Dk.cdK', '2023-09-11 21:36:04');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;

-- Listage de la structure de la table gestock. personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table gestock.personal_access_tokens : ~0 rows (environ)
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;

-- Listage de la structure de la table gestock. reparations
CREATE TABLE IF NOT EXISTS `reparations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `materiels_designation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantite` int(11) NOT NULL,
  `commentaire` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reparations_materiels_designation_foreign` (`materiels_designation`),
  CONSTRAINT `reparations_materiels_designation_foreign` FOREIGN KEY (`materiels_designation`) REFERENCES `materiels` (`designation`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table gestock.reparations : ~2 rows (environ)
/*!40000 ALTER TABLE `reparations` DISABLE KEYS */;
INSERT INTO `reparations` (`id`, `materiels_designation`, `quantite`, `commentaire`, `created_at`, `updated_at`) VALUES
	(1, 'BSA1', 5, 'Venue de Tombouctou pour réparation/ réparer le 18-09-2023', '2023-09-18 10:28:51', '2023-09-18 10:46:31'),
	(4, 'Câble Réseau', 183, 'Pour Recâbler les bâtiments', '2023-09-19 14:13:55', '2023-09-19 14:13:55');
/*!40000 ALTER TABLE `reparations` ENABLE KEYS */;

-- Listage de la structure de la table gestock. users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table gestock.users : ~4 rows (environ)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(4, 'Mahamane Amar MAIGA', 'mmahamaneamar@canam.ml', NULL, '$2y$10$KIKq/efif2ZdsoJu1aNuW.qznHojFI/RQb1dEf1cwAdlqWv8y2wZa', NULL, '2023-09-12 00:53:21', '2023-09-17 21:07:06'),
	(5, 'Admin', 'admin@canam.ml', NULL, '$2y$10$97uG8ADRlRq.OogcqX0BOusJtXicdw/N4ZaAHf7HrBCw3NcoAhocC', NULL, '2023-09-12 08:11:31', '2023-09-12 08:11:31'),
	(6, 'Marie Zerbo', 'sdsi@canam.ml', NULL, '$2y$10$h3jIllYVtHpCYen5v66U.O7qnodx5Op28jy7D03Bm//U.og/VQpWe', NULL, '2023-09-19 15:11:52', '2023-09-19 15:11:52'),
	(7, 'Mariam DIARRA', 'dmariam@canam.ml', NULL, '$2y$10$OT0iB0pNLJRpyJ372xsXs.Q0G4iENhpkIhVE9pOQieB6ymYvotEFW', NULL, '2023-09-19 15:27:39', '2023-09-19 15:27:39');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Listage de la structure de la table gestock. user_materiels
CREATE TABLE IF NOT EXISTS `user_materiels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `users_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `materiels_designation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantite` int(11) NOT NULL,
  `commentaire` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_materiels_users_email_foreign` (`users_email`),
  KEY `user_materiels_materiels_designation_foreign` (`materiels_designation`),
  CONSTRAINT `user_materiels_materiels_designation_foreign` FOREIGN KEY (`materiels_designation`) REFERENCES `materiels` (`designation`) ON DELETE CASCADE,
  CONSTRAINT `user_materiels_users_email_foreign` FOREIGN KEY (`users_email`) REFERENCES `users` (`email`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table gestock.user_materiels : ~11 rows (environ)
/*!40000 ALTER TABLE `user_materiels` DISABLE KEYS */;
INSERT INTO `user_materiels` (`id`, `type`, `users_email`, `materiels_designation`, `quantite`, `commentaire`, `created_at`, `updated_at`) VALUES
	(2, 'Entrer', 'mmahamaneamar@canam.ml', 'ID Bag', 13, 'Testssssss', '2023-09-15 15:49:40', '2023-09-15 15:49:40'),
	(3, 'Sortie', 'admin@canam.ml', 'ID Bag', 18, 'XXXXXX', '2023-09-15 16:24:29', '2023-09-15 16:24:29'),
	(4, 'Sortie', 'mmahamaneamar@canam.ml', 'Batteries', 18, 'sssss', '2023-09-15 17:22:44', '2023-09-17 19:52:27'),
	(5, 'Sortie', 'mmahamaneamar@canam.ml', 'Batteries', 1, 'GGGGGG', '2023-09-15 17:22:56', '2023-09-17 19:44:47'),
	(6, 'Sortie', 'admin@canam.ml', 'BSA1', -13, 'sssss', '2023-09-17 19:41:32', '2023-09-17 19:41:32'),
	(7, 'Entrer', 'mmahamaneamar@canam.ml', 'BSA1', 30, 'XXXXXX', '2023-09-17 20:37:51', '2023-09-17 20:37:51'),
	(9, 'Entrer', 'mmahamaneamar@canam.ml', 'Carte Mémoire', 250, 'Commande reçue pour 2023', '2023-09-18 08:52:45', '2023-09-18 08:52:45'),
	(10, 'Sortie', 'mmahamaneamar@canam.ml', 'Carte Mémoire', -75, 'Stock utilisé pour rendre opérationnel les bornes', '2023-09-18 08:53:50', '2023-09-18 08:53:50'),
	(11, 'Entrer', 'mmahamaneamar@canam.ml', 'ID Bag', 12, 'dddddddddddddddddddddddd', '2023-09-19 13:04:38', '2023-09-19 13:04:38'),
	(12, 'Entrer', 'mmahamaneamar@canam.ml', 'CHECK ID', 19, 'Pour besoin de prestation', '2023-09-19 13:50:01', '2023-09-19 13:50:01'),
	(16, 'Entrer', 'dmariam@canam.ml', 'ID Bag', 12, 'fffffffffffffffffffffffffff', '2023-09-19 15:44:28', '2023-09-19 15:44:28');
/*!40000 ALTER TABLE `user_materiels` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
