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


-- Listage de la structure de la base pour sigom
CREATE DATABASE IF NOT EXISTS `sigom` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `sigom`;

-- Listage de la structure de la table sigom. agents
CREATE TABLE IF NOT EXISTS `agents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `matricule` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fonction` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categorie` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `agents_matricule_unique` (`matricule`),
  KEY `agents_service_foreign` (`service`),
  KEY `agents_fonction_id_foreign` (`fonction`),
  KEY `agents_categorie_id_foreign` (`categorie`),
  CONSTRAINT `agents_categorie_id_foreign` FOREIGN KEY (`categorie`) REFERENCES `categories` (`nom_categorie`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `agents_fonction_id_foreign` FOREIGN KEY (`fonction`) REFERENCES `fonctions` (`nom_fonction`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `agents_service_foreign` FOREIGN KEY (`service`) REFERENCES `services` (`nom_service`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=382 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sigom.agents : ~372 rows (environ)
/*!40000 ALTER TABLE `agents` DISABLE KEYS */;
REPLACE INTO `agents` (`id`, `matricule`, `prenom`, `nom`, `fonction`, `categorie`, `service`, `created_at`, `updated_at`) VALUES
	(1, '20-304-BD', 'Boubacar', 'DEMBELE', 'DG', 'Catégorie A', 'SCRP', NULL, '2026-09-15 12:07:22'),
	(2, '10-012-ID', 'N\'Tji Idriss', 'DOUMBIA', NULL, NULL, NULL, NULL, NULL),
	(3, '14-285-AD', 'Alassane  Balobo', 'DICKO', NULL, NULL, NULL, NULL, NULL),
	(4, '10-004-ST', 'Seriba ', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(5, '11-135-MT', 'Mahamane Dedeou', 'TOURE', NULL, NULL, NULL, NULL, NULL),
	(6, '12-139-IG', 'Ibrahim', 'GUINDO', NULL, NULL, NULL, NULL, NULL),
	(7, '10-017-BD', 'Boubacar Bila ', 'DEMBELE', NULL, NULL, NULL, NULL, NULL),
	(8, '11-108-CC', 'Cheick Tidiane', 'COULIBALY', NULL, NULL, NULL, NULL, NULL),
	(9, '11-102-WT', 'DIARRAH Wandé Siragata', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(10, '12-204-FC', ' WADIDIE Founé', 'COULIBALY', NULL, NULL, NULL, NULL, NULL),
	(11, '10-049-AM', 'CISSE Aissata  ', 'MINTA', NULL, NULL, NULL, NULL, NULL),
	(12, '10-018-OK', 'Ousmane', 'KEITA', NULL, NULL, NULL, NULL, NULL),
	(13, '10-066-DS', 'ARBY Djeneba Nouhoum ', 'SIDIBE', NULL, NULL, NULL, NULL, NULL),
	(14, '10-084-HB', 'CISSE Haoussa', 'BA', NULL, NULL, NULL, NULL, NULL),
	(15, '10-090-HK', 'Hassan Aboubacar ', 'KANTE', NULL, NULL, NULL, NULL, NULL),
	(16, '10-021-AD', 'Aliou Mady ', 'DIALLO', NULL, NULL, NULL, NULL, NULL),
	(17, '10-006-KF', 'Kadidia Dite Bingui ', 'FANE', NULL, NULL, NULL, NULL, NULL),
	(18, '10-089-TD', 'DIAKITE Téné', 'DIALLO', NULL, NULL, NULL, NULL, NULL),
	(19, '10-075-DS', 'KASSAMBARA  Diarah ', 'SIDIBE', NULL, NULL, NULL, NULL, NULL),
	(20, '12-263-AT', 'KEITA Ahoua', 'TRAORE ', NULL, NULL, NULL, NULL, NULL),
	(21, '22-321-GC', 'Gabriel', 'COULIBALY', NULL, NULL, NULL, NULL, NULL),
	(22, '10-065-JK', 'Joseph ', 'KEÏTA', NULL, NULL, NULL, NULL, NULL),
	(23, '20-301-AB', 'Alou', 'BOCAR', NULL, NULL, NULL, NULL, NULL),
	(24, '12-272-SD', 'Sidiki', 'DIARRA', NULL, NULL, NULL, NULL, NULL),
	(33, '﻿10-002-SC', 'Seydou Tamba', 'CAMARA', NULL, NULL, NULL, NULL, NULL),
	(34, '16-291-BD', 'SIMA Binta N.N', 'DIARRA', NULL, NULL, NULL, NULL, NULL),
	(35, '22-408-MT', 'Moustaph', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(36, '10-029-MK', 'Mohamed', 'KONTHE', NULL, NULL, NULL, NULL, NULL),
	(37, '12-141-BS', 'Boubacar ', 'SOW', NULL, NULL, NULL, NULL, NULL),
	(38, '11-123-AF', 'Adama ', 'FOFANA', NULL, NULL, NULL, NULL, NULL),
	(39, '11-117-FT', 'SAMAKE Fanta   ', ' TRAORE ', NULL, NULL, NULL, NULL, NULL),
	(40, '10-079-OK', 'Oumar ', 'KEITA', NULL, NULL, NULL, NULL, NULL),
	(41, '12-150-IS', 'Idrissa ', 'SAMAKE', NULL, NULL, NULL, NULL, NULL),
	(42, '11-109-FC', 'Fousseyni', 'CAMARA', NULL, NULL, NULL, NULL, NULL),
	(43, '12-158-FB', 'Fanta', ' BATHILY', NULL, NULL, NULL, NULL, NULL),
	(44, '12-164-SD', 'KONTHE Sira Therese', ' DIAKITE', NULL, NULL, NULL, NULL, NULL),
	(45, '12-251-TD', 'Tiecoura', 'DOUMBIA', NULL, NULL, NULL, NULL, NULL),
	(46, '10-007-SH', 'Seydina Oumarou ', 'HAIDARA', NULL, NULL, NULL, NULL, NULL),
	(47, '12-146-MK', 'Mimouni Cheichna', 'Ben KATTRA ', NULL, NULL, NULL, NULL, NULL),
	(48, '22-310-ST', 'Sidy', 'TOUNKARA', NULL, NULL, NULL, NULL, NULL),
	(49, '10-022-FH\r\n', 'TRAORE Fatoumata Zaharaye', 'HAÏDARA', NULL, NULL, NULL, NULL, NULL),
	(50, '10-099-YN', 'Youssouf Sadibou ', 'NIANGADO', NULL, NULL, NULL, NULL, NULL),
	(51, '12-156-OT', 'Oumar', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(52, '10-072-AC', 'Aboubacar ', 'CISSE', NULL, NULL, NULL, NULL, NULL),
	(53, '10-069-SD', 'COULIBALY Sirantou Assimi', 'DEMBELE', NULL, NULL, NULL, NULL, NULL),
	(54, '11-110-AK', 'KEITA Alima', 'DOUCOURE', NULL, NULL, NULL, NULL, NULL),
	(55, '10-068-KS', 'Kola ', 'SOW', NULL, NULL, NULL, NULL, NULL),
	(56, '11-114-YD', 'Youba', 'DIAGOURAGA', NULL, NULL, NULL, NULL, NULL),
	(57, '15-286-OB', 'TANDIA Oumou ', 'BAH', NULL, NULL, NULL, NULL, NULL),
	(58, '19-296-PT', 'Pierre', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(59, '10-059-MK', 'KANE Marie Jeanne', 'KONATE', NULL, NULL, NULL, NULL, NULL),
	(60, '10-097-BT', 'Boubacar ', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(61, '10-028-AD', 'Aïchatou Y', 'DAO', NULL, NULL, NULL, NULL, NULL),
	(62, '10-052-AT', 'Abdoul Magid ', 'TOURE', NULL, NULL, NULL, NULL, NULL),
	(63, '10-056-AD', 'Amadou ', 'DIAWARA', NULL, NULL, NULL, NULL, NULL),
	(64, '10-054-FD', 'Fatoumata ', 'DIARRA', NULL, NULL, NULL, NULL, NULL),
	(65, '12-245-DD', 'TRAORE Djeneba', ' DIAWARA', NULL, NULL, NULL, NULL, NULL),
	(66, '10-055-MC', 'DOUMBIA Mariam ', 'COULIBALY', NULL, NULL, NULL, NULL, NULL),
	(67, '12-246-FT', 'SIDIBE Fatoumata', ' TOURE', NULL, NULL, NULL, NULL, NULL),
	(68, '10-058-FD', 'Fatoma', 'DIAWARA', NULL, NULL, NULL, NULL, NULL),
	(69, '22-354-MS', 'Mamadou', 'SAMAKE', NULL, NULL, NULL, NULL, NULL),
	(70, '22-362-BM', 'Bintou', 'MAIGA', NULL, NULL, NULL, NULL, NULL),
	(71, '22-356-MT', 'Mamadou Lassana', 'TOURE', NULL, NULL, NULL, NULL, NULL),
	(72, '10-051-DB', 'TOUNKARA Djeneba', 'BOCOUM', NULL, NULL, NULL, NULL, NULL),
	(73, '12-165-AS', 'Aboubacar ', ' SORA ', NULL, NULL, NULL, NULL, NULL),
	(74, '10-063-HT', 'DIAKITE  Hadja Hawa', 'TOURE', NULL, NULL, NULL, NULL, NULL),
	(75, '12-148-OK', 'BA Oumou Mai ', ' KONE', NULL, NULL, NULL, NULL, NULL),
	(76, '10-064-IT', 'Issa ', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(77, '10-057-OD', 'Oumar', 'DOUMBIA', NULL, NULL, NULL, NULL, NULL),
	(78, '22-403-IB', 'Ibrahima', 'TOURE', NULL, NULL, NULL, NULL, NULL),
	(79, '22-371-IM', 'Ismail Aguissa', 'MAIGA', NULL, NULL, NULL, NULL, NULL),
	(80, '22-401-MB', 'Mariam', 'BAMBA', NULL, NULL, NULL, NULL, NULL),
	(81, '10-005-ND', 'NTji', 'DIARRA', NULL, NULL, NULL, NULL, NULL),
	(82, '12-202-AD', 'TRAORE Aminata', 'DEMBELE', NULL, NULL, NULL, NULL, NULL),
	(83, '12-254-MK', 'Maimouna', 'KARAMBE', NULL, NULL, NULL, NULL, NULL),
	(84, '22-326-AK', 'Aissata', 'KEITA', NULL, NULL, NULL, NULL, NULL),
	(85, '10-095-KD', 'Kassim', 'DEMBELE', NULL, NULL, NULL, NULL, NULL),
	(86, '10-025-MC', 'Mory Bengaly', 'CAMARA', NULL, NULL, NULL, NULL, NULL),
	(87, '10-037-RD', 'Ramata ', 'DIARRA', NULL, NULL, NULL, NULL, NULL),
	(88, '12-152-AC', 'Aissata  Hamma', ' CISSE', NULL, NULL, NULL, NULL, NULL),
	(89, '12-153-MB', 'Moulaye Ismaila', 'BA', NULL, NULL, NULL, NULL, NULL),
	(90, '10-070-BC', 'Bintou', 'CISSE', NULL, NULL, NULL, NULL, NULL),
	(91, '10-044-DK', 'Djeneba ', 'KEITA', NULL, NULL, NULL, NULL, NULL),
	(92, '10-050-SB', 'Samba ', 'BA', NULL, NULL, NULL, NULL, NULL),
	(93, '22-369-AS', 'Aminata', 'SIDIBE', NULL, NULL, NULL, NULL, NULL),
	(94, '22-357-DS', 'Djouba', 'SANGARE', NULL, NULL, NULL, NULL, NULL),
	(95, '22-380-MG', 'Maimouna', 'GUINDO', NULL, NULL, NULL, NULL, NULL),
	(96, '10-024-YN', 'Youssouf ', 'NDIAYE', NULL, NULL, NULL, NULL, NULL),
	(97, '10-042-ST', 'Sylvie N', 'TOGO', NULL, NULL, NULL, NULL, NULL),
	(98, '10-036-YS', 'DOUCOURE Yaye Bella', 'SOW', NULL, NULL, NULL, NULL, NULL),
	(99, '11-104-HT', 'BARRY Hindy ', 'TANGARA', NULL, NULL, NULL, NULL, NULL),
	(100, '10-040-AK', 'DIAWARA  Alice ', 'KEITA', NULL, NULL, NULL, NULL, NULL),
	(101, '12-218-FF', 'DOUCOURE Fatoumata Tata ', 'FALL', NULL, NULL, NULL, NULL, NULL),
	(102, '12-154-MS', 'Modibo ', 'SANGARE', NULL, NULL, NULL, NULL, NULL),
	(103, '10-039-AD', 'SIDIBE Alimata ', 'DIARRA', NULL, NULL, NULL, NULL, NULL),
	(104, '12-256-AC', 'KANE Aminata', 'CISSE', NULL, NULL, NULL, NULL, NULL),
	(105, '22-372-AT', 'Asmaou', 'TANGARA', NULL, NULL, NULL, NULL, NULL),
	(106, '22-343-YT', 'Yamadou M', 'TOURE', NULL, NULL, NULL, NULL, NULL),
	(107, '22-344-AS', 'Kadidiatou Djimé', 'SISSOKO', NULL, NULL, NULL, NULL, NULL),
	(108, '22-377-KS', 'Korotoumou', 'SANOGO', NULL, NULL, NULL, NULL, NULL),
	(109, '22-376-BM', 'Boncana Djibrilla', 'MAIGA', NULL, NULL, NULL, NULL, NULL),
	(110, '22-390-FS', 'Fatoumata ', 'SANGARE', NULL, NULL, NULL, NULL, NULL),
	(111, '22-399-FM', 'Fadimata', 'MAIGA', NULL, NULL, NULL, NULL, NULL),
	(112, '22-378-KC', 'Kadidiatou Boureima', 'CISSE', NULL, NULL, NULL, NULL, NULL),
	(113, '22-352-BN', 'Bandjoukou', 'NDIAYE', NULL, NULL, NULL, NULL, NULL),
	(114, '22-346-MK', 'Mamadou Fadiala', 'KEITA', NULL, NULL, NULL, NULL, NULL),
	(115, '10-030-DB', 'Djibril ', 'BA', NULL, NULL, NULL, NULL, NULL),
	(116, '10-047-FD', 'Fadiala ', 'DEMBELE', NULL, NULL, NULL, NULL, NULL),
	(117, '10-033-SN', 'Sidi Yaya Joseph ', 'NIARE', NULL, NULL, NULL, NULL, NULL),
	(118, '12-163-SC', 'Sékou', 'CISSE', NULL, NULL, NULL, NULL, NULL),
	(119, '12-161-KO', 'Kalfallah Amaguiré ', 'ONGOIBA', NULL, NULL, NULL, NULL, NULL),
	(120, '12-209-AT', 'Aminata  K', 'TOURE', NULL, NULL, NULL, NULL, NULL),
	(121, '12-199-ND', 'Nana', 'DIAKITE', NULL, NULL, NULL, NULL, NULL),
	(122, '12-207-DS', 'Demba', 'SYLLA', NULL, NULL, NULL, NULL, NULL),
	(123, '12-283-RS', 'TRAORE  Rokiatou', 'SAM', NULL, NULL, NULL, NULL, NULL),
	(124, '10-038-MK', 'NIANG  Mah ', 'KONATE', NULL, NULL, NULL, NULL, NULL),
	(125, '12-159-NC', 'Nouhoum ', 'CISSE', NULL, NULL, NULL, NULL, NULL),
	(126, '12-219-AS', 'BABY Awa ', ' SANOGO  ', NULL, NULL, NULL, NULL, NULL),
	(127, '12-160-AT', 'Amadi Ibrahima Samba  ', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(128, '22-349-RT', 'Ramata ', 'TOURE', NULL, NULL, NULL, NULL, NULL),
	(129, '22-338-AC', 'Agnès', 'COULIBALY', NULL, NULL, NULL, NULL, NULL),
	(130, '22-341-MT', 'Moctar', 'TALL', NULL, NULL, NULL, NULL, NULL),
	(131, '22-353-IS', 'Idrissa ', 'SISSOKO', NULL, NULL, NULL, NULL, NULL),
	(132, '10-077-AT', 'LY  Aissata', ' THIAM', NULL, NULL, NULL, NULL, NULL),
	(133, '10-034-AT', 'Aminata ', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(134, '10-098-IC', 'Issa Baba ', 'CISSE', NULL, NULL, NULL, NULL, NULL),
	(135, '10-041-FD', 'BA Fatoumata ', 'DIAWARA', NULL, NULL, NULL, NULL, NULL),
	(136, '12-145-MD', 'Mamadou ', ' DIA', NULL, NULL, NULL, NULL, NULL),
	(137, '10-045-MF', 'Modibo ', 'FANE', NULL, NULL, NULL, NULL, NULL),
	(138, '10-031-AT', 'Ali Andiouro ', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(139, '10-035-LH', 'Lalla Aïcha ', 'HAÏDARA', NULL, NULL, NULL, NULL, NULL),
	(140, '10-043-AS', 'DIARRA Amineta A', 'SECK', NULL, NULL, NULL, NULL, NULL),
	(141, '22-358-AS', 'Adama ', 'SOUCKO', NULL, NULL, NULL, NULL, NULL),
	(142, '22-359-HD', 'Habibatou', 'DJIRE', NULL, NULL, NULL, NULL, NULL),
	(143, '22-379-MC', 'Modibo ', 'COULIBALY', NULL, NULL, NULL, NULL, NULL),
	(144, '22-383-AD', 'Aichetou', 'DIALLO', NULL, NULL, NULL, NULL, NULL),
	(145, '22-400-FM', 'Fatimata', 'MACINA', NULL, NULL, NULL, NULL, NULL),
	(146, '22-363-RS', 'Rokia', 'SISSOKO', NULL, NULL, NULL, NULL, NULL),
	(147, '10-014-MD', 'Dr Mamady ', 'DIAWARA', NULL, NULL, NULL, NULL, NULL),
	(148, '12-259-FD', 'DIAWARA Fatoumata M\'Barké', 'DOUMBIA', NULL, NULL, NULL, NULL, NULL),
	(149, '10-096-ST', 'Sidiki', 'TOURE', NULL, NULL, NULL, NULL, NULL),
	(150, '10-083-AD', 'Abdoulaye ', 'DIALLO', NULL, NULL, NULL, NULL, NULL),
	(151, '10-080-NK', 'N’Faly ', 'KONTAGA', NULL, NULL, NULL, NULL, NULL),
	(152, '12-149-AT', 'SIDIBE Aichatou Oumar', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(153, '11-105-BK', 'KEITA Bintou ', 'KOÏTE', NULL, NULL, NULL, NULL, NULL),
	(154, '12-143-AK', 'Amassagou Eric ', 'KENE', NULL, NULL, NULL, NULL, NULL),
	(155, '22-350-CC', 'Cheick Oumar', 'COULIBALY', NULL, NULL, NULL, NULL, NULL),
	(156, '22-325-SD', 'Souleymane ', 'DEMBELE', NULL, NULL, NULL, NULL, NULL),
	(157, '11-124-KN', 'DIALLO  Kadiatou ', 'NDIAYE', NULL, NULL, NULL, NULL, NULL),
	(158, '11-122-AC', 'Ali ', 'CISSE', NULL, NULL, NULL, NULL, NULL),
	(159, '10-074-AT', 'Alpha', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(160, '22-319-AT', 'Alamissa', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(161, '22-333-SO', 'Safiatou', 'OUATTARA', NULL, NULL, NULL, NULL, NULL),
	(162, '11-136-KS', 'COULIBALY  Kadidiatou Moussodié ', 'SANGARE', NULL, NULL, NULL, NULL, NULL),
	(163, '22-311-AD', 'Aleye', 'DIAH', NULL, NULL, NULL, NULL, NULL),
	(164, '12-247-BD', 'Bourama', 'DIABY', NULL, NULL, NULL, NULL, NULL),
	(165, '12-180-AT', 'Aminata', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(166, '12-252-IS', 'Ibrahima', 'SANTARA', NULL, NULL, NULL, NULL, NULL),
	(167, '12-153-KM', 'Koutoum', ' MAÏGA', NULL, NULL, NULL, NULL, NULL),
	(168, '12-243-IC', 'Ibrahima Sory', 'CAMARA', NULL, NULL, NULL, NULL, NULL),
	(169, '12-172-FK', 'Falaye', ' KONATE', NULL, NULL, NULL, NULL, NULL),
	(170, '12-174-AT', 'SIDIBE Assanatou Manda', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(171, '12-177-BD', ' Boubacar', ' DICKO', NULL, NULL, NULL, NULL, NULL),
	(172, '12-179-NK', ' Niagalé', ' KEITA', NULL, NULL, NULL, NULL, NULL),
	(173, '12-183-BS', 'Bintou', ' SANGARE', NULL, NULL, NULL, NULL, NULL),
	(174, '12-184-RT', 'DIARRA  Founé Rokiatou', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(175, '12-186-SD', 'Sadio', ' DEMBELE', NULL, NULL, NULL, NULL, NULL),
	(176, '12-187-MD', 'COULIBALY Maimouna', ' DIALLO', NULL, NULL, NULL, NULL, NULL),
	(177, '12-188-MC', 'Mariama', 'COULIBALY', NULL, NULL, NULL, NULL, NULL),
	(178, '12-189-AK', 'BASS  Aissata', ' KAYENTAO', NULL, NULL, NULL, NULL, NULL),
	(179, '12-190-DK', 'Dramane', 'KANTE', NULL, NULL, NULL, NULL, NULL),
	(180, '12-193-KB', 'Kadji Dite Anna', ' BOCOUM', NULL, NULL, NULL, NULL, NULL),
	(181, '12-194-SG', 'Sinaré', ' GUINDO ', NULL, NULL, NULL, NULL, NULL),
	(182, '12-195-HB', 'BAMBA Hawa ', 'BOCOUM', NULL, NULL, NULL, NULL, NULL),
	(183, '12-197-MK', 'Mantjita', ' KEITA', NULL, NULL, NULL, NULL, NULL),
	(184, '12-198-KD', 'Kadiatou', 'DOSSOU', NULL, NULL, NULL, NULL, NULL),
	(185, '12-200-BT', 'Boubacar Mamadou', 'TRAORE ', NULL, NULL, NULL, NULL, NULL),
	(186, '12-201-MM', 'Moussa', 'SOME', NULL, NULL, NULL, NULL, NULL),
	(187, '12-203-NB', 'SY  Nene Penda', ' BA', NULL, NULL, NULL, NULL, NULL),
	(188, '12-205-HT', 'Hamadoun', ' TOURE ', NULL, NULL, NULL, NULL, NULL),
	(189, '12-206-AK', 'Amidou', 'KEITA', NULL, NULL, NULL, NULL, NULL),
	(190, '12-208-MD', 'Mounkeila B', 'DIAMBEIDOU', NULL, NULL, NULL, NULL, NULL),
	(191, '12-210-AK', 'Aissetou', ' KOUYATE', NULL, NULL, NULL, NULL, NULL),
	(192, '12-213-MD', 'Mariam', ' DIALLO', NULL, NULL, NULL, NULL, NULL),
	(193, '12-214-SN', 'Souleymane ', ' NIAMBELE', NULL, NULL, NULL, NULL, NULL),
	(194, '12-216-KS', ' Kadidia', 'SIDIBE', NULL, NULL, NULL, NULL, NULL),
	(195, '12-181-SB', ' Sidi Mohamed', ' BARRY', NULL, NULL, NULL, NULL, NULL),
	(196, '12-231-FD', 'Fatoumata', ' DIARRASSOUBA', NULL, NULL, NULL, NULL, NULL),
	(197, '12-221-KT', 'Kadidiatou Boubacar', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(198, '12-222-BT', 'Boubacar Massa', 'TRAORE ', NULL, NULL, NULL, NULL, NULL),
	(199, '12-223-BD', 'Bakary', 'DIARRA', NULL, NULL, NULL, NULL, NULL),
	(200, '12-225-FC', 'Fatoumata Bintou', 'COULIBALY', NULL, NULL, NULL, NULL, NULL),
	(201, '12-227-MS', 'Capitaine Mamadou   ', 'SOUMANO', NULL, NULL, NULL, NULL, NULL),
	(202, '12-228-FT', ' Fatou', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(203, '12-229-AS', 'Aboudou', ' SIDIBE', NULL, NULL, NULL, NULL, NULL),
	(204, '12-230-ID', 'Yssouf', ' DIABATE', NULL, NULL, NULL, NULL, NULL),
	(205, '12-248-DD', 'Daouda', 'DOUMBIA', NULL, NULL, NULL, NULL, NULL),
	(206, '12-233-HT', 'Harouna', 'TANGARA', NULL, NULL, NULL, NULL, NULL),
	(207, '12-236-JD', 'Job', 'DIONE', NULL, NULL, NULL, NULL, NULL),
	(208, '12-237-YB', 'Youssouf', ' BERTHE', NULL, NULL, NULL, NULL, NULL),
	(209, '12-240-AC', 'Mme BAH Assanatou Kinty ', 'CISSE', NULL, NULL, NULL, NULL, NULL),
	(210, '12-241-MT', 'Mariam', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(211, '12-238-CD', 'Cheick Oumar', ' DIAMOUTENE', NULL, NULL, NULL, NULL, NULL),
	(212, '12-242-ND', 'Rokiatou Nene', 'DIAKITE', NULL, NULL, NULL, NULL, NULL),
	(213, '12-226-SD', 'Seydou', ' DAOU', NULL, NULL, NULL, NULL, NULL),
	(214, '14-284-AK', 'DOUMBIA Alimatou  Soumia', 'KEITA ', NULL, NULL, NULL, NULL, NULL),
	(215, '12-196-SB', 'Salamata Anna', ' BABY ', NULL, NULL, NULL, NULL, NULL),
	(216, '12-235-AT', 'Alhousna', 'TOURE', NULL, NULL, NULL, NULL, NULL),
	(217, '12-255-CD', 'Cheick Sidiya', 'DOUMBIA', NULL, NULL, NULL, NULL, NULL),
	(218, '12-239-HB', 'Hamidou', ' BAH', NULL, NULL, NULL, NULL, NULL),
	(219, '12-264-DK', 'Djonmousso', ' KANTE ', NULL, NULL, NULL, NULL, NULL),
	(220, '12-224-MG', 'Moustapha', 'GABA', NULL, NULL, NULL, NULL, NULL),
	(221, '12-178-YM', 'DOUMBIA Yah Maya', ' MAÏGA  ', NULL, NULL, NULL, NULL, NULL),
	(222, '12-258-DT', 'KANOUTE  Djeneba', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(223, '12-262-MS', 'Mamadou', 'SISSOKO', NULL, NULL, NULL, NULL, NULL),
	(224, '12-265-SC', 'Sekou Bassirou', 'CAMARA', NULL, NULL, NULL, NULL, NULL),
	(225, '12-260-OD', 'Ousmane', ' DOUMBIA', NULL, NULL, NULL, NULL, NULL),
	(226, '12-267-AT', 'Alima ', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(227, '12-268-FS', 'Fatoumata Dite Goundo', 'SIDIBE', NULL, NULL, NULL, NULL, NULL),
	(228, '22-335-MS', 'Mamadou Hamat', 'SIDIBE', NULL, NULL, NULL, NULL, NULL),
	(229, '22-324-MD', 'Malado', 'DIAKITE', NULL, NULL, NULL, NULL, NULL),
	(230, '22-381-FK', 'Fatoumata', 'KONE', NULL, NULL, NULL, NULL, NULL),
	(231, '22-339-ZK', 'Zeinabou Djibé', 'KEITA', NULL, NULL, NULL, NULL, NULL),
	(232, '22-345-KK', 'Korotoum', 'KEITA', NULL, NULL, NULL, NULL, NULL),
	(233, '22-322-MB', 'Modibo ', 'BORE', NULL, NULL, NULL, NULL, NULL),
	(234, '22-348-MT', 'Marietou', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(235, '22-347-AH', 'Hamidou', 'ATTAHER', NULL, NULL, NULL, NULL, NULL),
	(236, '22-351-MD', 'Moriba', 'DIAKITE', NULL, NULL, NULL, NULL, NULL),
	(237, '22-364-DD', 'Dambou', 'DIARRA', NULL, NULL, NULL, NULL, NULL),
	(238, '22-365-AM', 'Aissa', 'MAIGA', NULL, NULL, NULL, NULL, NULL),
	(239, '22-366-RB', 'Ramata ', 'BAMBA', NULL, NULL, NULL, NULL, NULL),
	(240, '22-385-YT', 'Youssouf Amadou', 'TALL', NULL, NULL, NULL, NULL, NULL),
	(241, '22-323-AD', 'Amina', 'DEMBELE', NULL, NULL, NULL, NULL, NULL),
	(242, '22-370-SC', 'Salimata', 'CISSE', NULL, NULL, NULL, NULL, NULL),
	(243, '22-393-AT', 'Aboulaye Sidi', 'TOURE', NULL, NULL, NULL, NULL, NULL),
	(244, '22-361-JK', 'Josephine', 'KONE', NULL, NULL, NULL, NULL, NULL),
	(245, '22-388-CS', 'Cheick Sidi Mohamed', 'SIMPARA', NULL, NULL, NULL, NULL, NULL),
	(246, '22-332-GN', 'Georges Oumar Cheick', 'NIAMBELE', NULL, NULL, NULL, NULL, NULL),
	(247, '22-391-FT', 'Fatoumata', 'TAPO', NULL, NULL, NULL, NULL, NULL),
	(248, '22-392-SC', 'Soumba Dite Fanta', 'CAMARA', NULL, NULL, NULL, NULL, NULL),
	(249, '22-395-HD', 'Haby Amadou', 'DIOP', NULL, NULL, NULL, NULL, NULL),
	(250, '22-396-FK', 'Fatoumata dite tata', 'KOUMARE', NULL, NULL, NULL, NULL, NULL),
	(251, '22-397-FG', 'Fadima', 'GUINDO', NULL, NULL, NULL, NULL, NULL),
	(252, '22-389-JN', 'Joseph Armand ', 'NIARE', NULL, NULL, NULL, NULL, NULL),
	(253, '22-375-AF', 'Awa Tenin', 'FANE', NULL, NULL, NULL, NULL, NULL),
	(254, '22-404-MS', 'Mariam ', 'SOUNTOURA', NULL, NULL, NULL, NULL, NULL),
	(255, '22-340-IT', 'Ina Fatoumata ', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(256, '22-405-BG', 'Bassidy', 'GOITA', NULL, NULL, NULL, NULL, NULL),
	(257, '22-342-AC', 'Aissata Kader ', 'CAMARA', NULL, NULL, NULL, NULL, NULL),
	(258, '11-130-YO', 'Youssouf ', 'OUATTARA', NULL, NULL, NULL, NULL, NULL),
	(259, '11-133-BT', 'El Hadji Belco ', 'TOURE', NULL, NULL, NULL, NULL, NULL),
	(260, '11-126-NC', 'BA Nayé ', 'CAMARA', NULL, NULL, NULL, NULL, NULL),
	(261, '11-125-AB', 'Adama ', 'BERTHE', NULL, NULL, NULL, NULL, NULL),
	(262, '11-127-MB', 'Mariame Dite Yama ', 'BOUARE', NULL, NULL, NULL, NULL, NULL),
	(263, '22-312-KD', 'Kadiatou', 'DIARRA', NULL, NULL, NULL, NULL, NULL),
	(264, '22-318-KK', 'Korotoumou', 'KONE', NULL, NULL, NULL, NULL, NULL),
	(265, '22-313-MD', 'Masseli', 'DIAKITE', NULL, NULL, NULL, NULL, NULL),
	(266, '22-314-MC', 'Mosséré', 'CAMARA', NULL, NULL, NULL, NULL, NULL),
	(267, '22-315-SN', 'Sané', 'NDIAYE', NULL, NULL, NULL, NULL, NULL),
	(268, '22-316-AK', 'Aichatou Louise', 'KONATE', NULL, NULL, NULL, NULL, NULL),
	(269, '22-317-RS', 'Rokiatou dite Founé', 'SACKO', NULL, NULL, NULL, NULL, NULL),
	(270, '10-093-AC', 'Amadou ', 'COULIBALY', NULL, NULL, NULL, NULL, NULL),
	(271, '12-249-MZ', 'Marie Noêlle ', 'ZERBO', NULL, NULL, NULL, NULL, NULL),
	(272, '10-008-AF', 'Amouzougan José', 'FOLLY', NULL, NULL, NULL, NULL, NULL),
	(273, '10-010-OD', 'CISSE Oumou ', 'DIALLO', NULL, NULL, NULL, NULL, NULL),
	(274, '11-138-YC', 'Yida', 'CISSE', NULL, NULL, NULL, NULL, NULL),
	(275, '12-215-BS', 'DOUMBIA Bintou ', 'SAMAKE', NULL, NULL, NULL, NULL, NULL),
	(276, '10-091-OO', 'KONARE Oumou', 'OUATTARA', NULL, NULL, NULL, NULL, NULL),
	(277, '22-327-KK', 'Karamoko Souleymane', 'KANE', NULL, NULL, NULL, NULL, NULL),
	(278, '22-328-MD', 'Moussa Mohamed Lamine', 'DIARRA', NULL, NULL, NULL, NULL, NULL),
	(279, '22-329-MD', 'Mariam', 'DIARRA', NULL, NULL, NULL, NULL, NULL),
	(280, '22-330-AC', 'Alphady', 'CISSE', NULL, NULL, NULL, NULL, NULL),
	(281, '10-009-SB', 'Souleymane ', 'BERTHE', NULL, NULL, NULL, NULL, NULL),
	(282, '10-094-OS', 'Ousmane Oussébé', 'SANOGO', NULL, NULL, NULL, NULL, NULL),
	(283, '10-092-MM', 'M’Bouille Sekou ', 'MAÏGA', NULL, NULL, NULL, NULL, NULL),
	(284, '12-140-TD', 'Taleb', 'DICKO', NULL, NULL, NULL, NULL, NULL),
	(285, '10-011-AK', 'Aïchata Bassirou ', 'KEÏTA', NULL, NULL, NULL, NULL, NULL),
	(286, '11-118-AS', 'Abdrahamane', 'SIMA', NULL, NULL, NULL, NULL, NULL),
	(287, '11-119-AT', 'Adou', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(288, '11-120-OK', 'Oumar ', 'KEITA', NULL, NULL, NULL, NULL, NULL),
	(289, '12-169-SK', 'Souleymane ', 'KONIPO', NULL, NULL, NULL, NULL, NULL),
	(290, '22-307-AC', 'Assitan', 'CISSE', NULL, NULL, 'SGPMEPC', NULL, '2023-10-19 01:06:26'),
	(291, '22-308-AF', 'Abdoul Karim ', 'FOMBA', NULL, NULL, NULL, NULL, NULL),
	(292, '22-309-MM', 'Mahamane Amar', 'MAIGA', NULL, NULL, 'SGPMEPC', NULL, NULL),
	(293, '10-076-MM', 'Mamadou', 'MBO', NULL, NULL, NULL, NULL, NULL),
	(294, '11-115-AD', 'MAIGA Agaichatou ', 'DICKO', NULL, NULL, NULL, NULL, NULL),
	(295, '10-078-KB', 'SIMPARA  Kadiatou', 'BOLLY', NULL, NULL, NULL, NULL, NULL),
	(296, '10-015-TS', 'Tiémoko', 'SANGARE', NULL, NULL, NULL, NULL, NULL),
	(297, '10-087-BC', 'Brehima ', 'COULIBALY', NULL, NULL, NULL, NULL, NULL),
	(298, '12-192-AL', 'KONE Astan', ' LY', NULL, NULL, NULL, NULL, NULL),
	(299, '10-061-KT', 'DIAKITE  Kadiatou ', 'TANGARA', NULL, NULL, NULL, NULL, NULL),
	(300, '11-121-NS', 'SINGARE  Nana', ' SANGARE', NULL, NULL, NULL, NULL, NULL),
	(301, '10-062-AS', 'Aly ', 'SIDIBE', NULL, NULL, NULL, NULL, NULL),
	(302, '12-170-BD', 'DRAME  Bintou  ', 'DRAME', NULL, NULL, NULL, NULL, NULL),
	(303, '22-387-MD', 'Mariam Aby', 'DEMBELE', NULL, NULL, NULL, NULL, NULL),
	(304, '22-337-MS', 'Mohamed ', 'SISSOUMA', NULL, NULL, NULL, NULL, NULL),
	(305, '12-151-MK', 'Moussa', ' KOUYATE', NULL, NULL, NULL, NULL, NULL),
	(306, '10-060-AT', 'TRAORE Adiaratou ', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(307, '11-113-MS', 'Moriba', 'SIDIBE', NULL, NULL, NULL, NULL, NULL),
	(308, '12-166-MT', 'Mohamed  Lamine', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(309, '12-244-FD', 'DEMBELE Fatoumata', ' DEMBELE', NULL, NULL, NULL, NULL, NULL),
	(310, '22-398-FH', 'Fanta Cherif ', 'HAIDARA', NULL, NULL, NULL, NULL, NULL),
	(311, '10-073-MT', 'Mahamadou', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(312, '12-155-AB', 'Aissata Bory', 'BARRY', NULL, NULL, NULL, NULL, NULL),
	(313, '10-032-NB', 'Nouhoum ', 'BAMANI', NULL, NULL, NULL, NULL, NULL),
	(314, '10-071-MB', 'DIARRA Maya ', 'BIDANIS', NULL, NULL, NULL, NULL, NULL),
	(315, '22-320-AM', 'Alpha Boubacar', 'MAIGA', NULL, NULL, NULL, NULL, NULL),
	(316, '22-331-KK', 'Kankou', 'KEITA', NULL, NULL, NULL, NULL, NULL),
	(317, '22-336-SD', 'Souleymane ', 'DEMBELE', NULL, NULL, NULL, NULL, NULL),
	(318, '10-026-MC', 'Mamadi', 'COULIBALY', NULL, NULL, NULL, NULL, NULL),
	(319, '10-088-AT', 'Alima ', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(320, '10-016-IK', 'Ibrahima ', 'KEÏTA ', NULL, NULL, NULL, NULL, NULL),
	(321, '11-116-AH', 'Aicha', 'HAIDARA', NULL, NULL, NULL, NULL, NULL),
	(322, '12-167-MT', 'Mohamed Fousseyni ', 'TOURE', NULL, NULL, NULL, NULL, NULL),
	(323, '10-081-DS', 'Dala ', 'SISSOKO', NULL, NULL, NULL, NULL, NULL),
	(324, '12-147-KK', 'DICKO  Kadiatou', 'KAMISSOKO', NULL, NULL, NULL, NULL, NULL),
	(325, '12-269-AD', 'Alassane  Tiécoura', 'DOUMBIA', NULL, NULL, NULL, NULL, NULL),
	(326, '12-270-YC', 'Younoussa', 'COULIBALY', NULL, NULL, NULL, NULL, NULL),
	(327, '12-274-BT', 'Bourama', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(328, '12-277-OG', 'Ousmane', 'GUINDO', NULL, NULL, NULL, NULL, NULL),
	(329, '12-278-DT', 'Djanki', ' TOUNKARA', NULL, NULL, NULL, NULL, NULL),
	(330, '22-386-SD', 'Sory', 'DEMBELE', NULL, NULL, NULL, NULL, NULL),
	(331, '22-394-MC', 'Mamadou Gille', 'COULIBALY', NULL, NULL, NULL, NULL, NULL),
	(332, '22-355-DD', 'Demba', 'DOUMBIA', NULL, NULL, NULL, NULL, NULL),
	(333, '22-384-ZS', 'Zoumana', 'SY', NULL, NULL, NULL, NULL, NULL),
	(334, '22-367-FC', 'Fatoumata', 'COULIBALY', NULL, NULL, NULL, NULL, NULL),
	(335, '22-406-ED', 'Emmanuel', 'DAO', NULL, NULL, NULL, NULL, NULL),
	(336, '22-407-NS', 'Nouhoum ', 'SANKARE', NULL, NULL, NULL, NULL, NULL),
	(338, '22-373-MD', 'Malick', 'DIABATE', NULL, NULL, NULL, NULL, NULL),
	(339, '22-374-FK', 'Fatoumata', 'KONE', NULL, NULL, NULL, NULL, NULL),
	(340, '12-162-JN', 'Jam', 'NDIAYE', NULL, NULL, NULL, NULL, NULL),
	(341, '10-053-OT', 'Ousmane Aliou ', 'THERA', NULL, NULL, NULL, NULL, NULL),
	(342, '10-082-HS', 'SISSOKO Hawa Dite Sokona ', 'SISSOKO', NULL, NULL, NULL, NULL, NULL),
	(343, '11-107-OT', 'SAGARA  Oumou ', ' TESSOUGUE', NULL, NULL, NULL, NULL, NULL),
	(344, '19-298-FO', 'ILBOUDO Fatou', 'OUATTARA', NULL, NULL, NULL, NULL, NULL),
	(345, '22-402-OS', 'Ousmane', 'SOW', NULL, NULL, NULL, NULL, NULL),
	(346, '22-368-MB', 'Mohamed Modibo ', 'BA', NULL, NULL, NULL, NULL, NULL),
	(347, '11-103-OD', ' DIALLO Oumou ', 'DIAKITE', NULL, NULL, NULL, NULL, NULL),
	(348, '10-067-FB', 'TRAORE  Fanta ', 'BARRY', NULL, NULL, NULL, NULL, NULL),
	(349, '12-157-AT', 'Albert Souleymane ', ' TRAORE', NULL, NULL, NULL, NULL, NULL),
	(350, '12-176-AD', 'FAROTA Assitan', 'DIALLO', NULL, NULL, NULL, NULL, NULL),
	(351, '12-142-BT', 'Boubacar Guidado', 'TOURE', NULL, NULL, NULL, NULL, NULL),
	(352, '22-382-TS', 'Toumani', 'SIDIBE', NULL, NULL, NULL, NULL, NULL),
	(353, '12-144-IB', 'Ibrahima', 'BOCOUM', NULL, NULL, NULL, NULL, NULL),
	(354, '10-086-YD', 'Yaya ', 'DOUMBIA', NULL, NULL, NULL, NULL, NULL),
	(355, '12-168-AD', 'Amadou Siré', 'DIAKITE', NULL, NULL, NULL, NULL, NULL),
	(356, '12-171-AD', 'Albin Dit Almamy', ' DIARRA', NULL, NULL, NULL, NULL, NULL),
	(357, '22-409-YM', 'Ladji', 'DJIRE', NULL, NULL, NULL, NULL, NULL),
	(358, '22-334-SK', 'Salimata', 'KEITA', NULL, NULL, NULL, NULL, NULL),
	(359, '12-191-KM', 'Koke', ' MARIKO', NULL, NULL, NULL, NULL, NULL),
	(360, '12-261-SS', 'Samba ', 'SANGARE', NULL, NULL, NULL, NULL, NULL),
	(361, '22-360-YK', 'Youma Aissata', 'KANOUTE', NULL, NULL, NULL, NULL, NULL),
	(362, '16-292-OK', 'Oumar ', 'KONARE', NULL, NULL, NULL, NULL, NULL),
	(363, '11-128-ON', 'Oumar ', 'NDIAYE', NULL, NULL, NULL, NULL, NULL),
	(364, '12-212-AD', 'SOUMAORO Assitan ', 'DIAWARA', NULL, NULL, NULL, NULL, NULL),
	(365, '12-175-KS', 'Karamoko Ibrahima', ' SIMAGA', NULL, NULL, NULL, NULL, NULL),
	(366, '12-271-MB', 'Modibo ', ' BAH', NULL, NULL, NULL, NULL, NULL),
	(367, '11-129-AB', 'Ahmed ', 'BABA', NULL, NULL, NULL, NULL, NULL),
	(368, '11-132-AM', 'Abdou ', 'MALLE', NULL, NULL, NULL, NULL, NULL),
	(369, '12-273-BD', 'Boubacar', 'DOUMBIA', NULL, NULL, NULL, NULL, NULL),
	(370, '12-280-BB', 'Brahima', 'BARRY', NULL, NULL, NULL, NULL, NULL),
	(371, '12-232-MB', 'Moussa Balla', ' BEYE', NULL, NULL, NULL, NULL, NULL),
	(372, '11-131-ID', 'Issa ', 'DIARRA', NULL, NULL, NULL, NULL, NULL),
	(373, '10-046-BK', 'Boubacar ', 'KONE', NULL, NULL, NULL, NULL, NULL),
	(374, '12-211-AS', 'Hamadoun', 'SIDIBE', NULL, NULL, NULL, NULL, NULL),
	(375, '14-286-MT', 'Mamadou', 'TRAORE', NULL, NULL, NULL, NULL, NULL),
	(376, '10-048-OB', 'Ousmane Souleymane', 'BA', NULL, NULL, NULL, NULL, NULL),
	(377, '12-250-AT', 'Aly ', 'TRAORE ', NULL, NULL, NULL, NULL, NULL),
	(378, '12-234-AD', 'Abdoulaye ', ' DIALLO', NULL, NULL, NULL, NULL, NULL),
	(379, '12-276-ZM', 'Zakaria Souleymane', 'MAÏGA', NULL, NULL, NULL, NULL, NULL),
	(380, '12-173-YD', ' Youssoufa Aboubacar ', 'DIALLO', NULL, NULL, NULL, NULL, NULL),
	(381, '11-112-MC', 'Mahamoudou', 'CISSE', NULL, NULL, NULL, NULL, NULL);
/*!40000 ALTER TABLE `agents` ENABLE KEYS */;

-- Listage de la structure de la table sigom. categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_categorie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_code_unique` (`code`),
  UNIQUE KEY `categories_nom_categorie_unique` (`nom_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sigom.categories : ~5 rows (environ)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
REPLACE INTO `categories` (`id`, `code`, `nom_categorie`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'A', 'Catégorie A', NULL, '2026-09-15 11:37:05', '2026-09-15 11:37:05'),
	(2, 'B', 'Catégorie B', NULL, '2026-09-15 11:37:05', '2026-09-15 11:37:05'),
	(3, 'C', 'Catégorie C', NULL, '2026-09-15 11:37:05', '2026-09-15 11:37:05'),
	(4, 'D', 'Catégorie D', NULL, '2026-09-15 11:37:05', '2026-09-15 11:37:05'),
	(5, 'E', 'Catégorie E', NULL, '2026-09-15 11:37:05', '2026-09-15 11:37:05');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Listage de la structure de la table sigom. directions
CREATE TABLE IF NOT EXISTS `directions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom_direction` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(355) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `directions_nom_direction_unique` (`nom_direction`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sigom.directions : ~9 rows (environ)
/*!40000 ALTER TABLE `directions` DISABLE KEYS */;
REPLACE INTO `directions` (`id`, `nom_direction`, `description`, `created_at`, `updated_at`) VALUES
	(1, '﻿DG', 'DIRECTION GENERALE ', NULL, NULL),
	(2, 'DAI', 'DIRECTION DE AUDIT INTERNE', NULL, NULL),
	(3, 'AC', 'AGENCE COMPTABLE', NULL, NULL),
	(4, 'DAOGD', 'DIRECTION DE L\'APPUI AUX ORGANISMES GESTIONNAIRES DELEGUES', NULL, '2023-10-16 22:21:53'),
	(5, 'DOCQS', 'DIRECTION DE L\'ORGANISATION ET DU CONTRÔLE DE LA QUALITE DES SOINS', NULL, '2023-10-16 22:22:19'),
	(6, 'DBCGOQ', 'DIRECTION DU BUDGET DU CONTRÔLE DE GESTION ET DE L\'ORGANISATION QUALITE', NULL, '2023-10-16 22:22:54'),
	(7, 'DAGRH', 'DIRECTION DE L\'ADMINISTRATION ET DE LA GESTION DES RESSOURCES HUMAINES', NULL, '2023-10-16 22:23:10'),
	(8, 'BUREAUX REGIONAUX', 'BUREAUX REGIONAUX', NULL, '2023-10-16 22:53:49'),
	(15, 'DSI', 'DIRECTION DES SYSTEMES D\'INFORMATION', NULL, '2023-10-16 22:28:22');
/*!40000 ALTER TABLE `directions` ENABLE KEYS */;

-- Listage de la structure de la table sigom. failed_jobs
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

-- Listage des données de la table sigom.failed_jobs : ~0 rows (environ)
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

-- Listage de la structure de la table sigom. fonctions
CREATE TABLE IF NOT EXISTS `fonctions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom_fonction` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fonctions_nom_fonction_unique` (`nom_fonction`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sigom.fonctions : ~0 rows (environ)
/*!40000 ALTER TABLE `fonctions` DISABLE KEYS */;
REPLACE INTO `fonctions` (`id`, `nom_fonction`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'CS', 'CHEF SERVICE', '2026-09-15 11:53:53', '2026-09-15 11:53:53'),
	(2, 'DG', 'DIECTEUR GENERAL', '2026-09-15 11:54:45', '2026-09-15 11:54:45');
/*!40000 ALTER TABLE `fonctions` ENABLE KEYS */;

-- Listage de la structure de la table sigom. migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sigom.migrations : ~18 rows (environ)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2023_09_20_120941_create_assures_table', 1),
	(6, '2023_09_20_121331_create_aps_table', 2),
	(7, '2023_09_20_121356_create_alds_table', 3),
	(8, '2023_09_20_121543_create_prescripteurs_table', 3),
	(9, '2023_09_20_121622_create_techniciens_table', 3),
	(10, '2023_09_20_121706_create_hopitals_table', 3),
	(11, '2023_09_20_121746_create_pharmacies_table', 3),
	(12, '2023_09_20_164046_create_prestations_table', 4),
	(13, '2023_09_21_120425_create_prestations_table', 5),
	(14, '2023_09_23_193842_create_prestations_table', 6),
	(15, '2023_09_24_183342_create_prestations_table', 7),
	(16, '2023_09_26_191307_create_profils_table', 8),
	(17, '2023_09_26_204805_create_users_table', 9),
	(18, '2023_09_26_205935_create_users_table', 10);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Listage de la structure de la table sigom. missions
CREATE TABLE IF NOT EXISTS `missions` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `numero` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `objet` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('nationale','internationale') COLLATE utf8mb4_unicode_ci NOT NULL,
  `pays_id` bigint(20) unsigned DEFAULT NULL,
  `region_id` bigint(20) unsigned DEFAULT NULL,
  `destination` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_depart` date NOT NULL,
  `date_retour` date NOT NULL,
  `duree` int(11) DEFAULT NULL,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'brouillon',
  `montant_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `missions_numero_unique` (`numero`),
  KEY `missions_pays_id_foreign` (`pays_id`),
  KEY `missions_region_id_foreign` (`region_id`),
  CONSTRAINT `missions_pays_id_foreign` FOREIGN KEY (`pays_id`) REFERENCES `pays` (`id`) ON DELETE SET NULL,
  CONSTRAINT `missions_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sigom.missions : ~2 rows (environ)
/*!40000 ALTER TABLE `missions` DISABLE KEYS */;
REPLACE INTO `missions` (`id`, `numero`, `objet`, `type`, `pays_id`, `region_id`, `destination`, `date_depart`, `date_retour`, `duree`, `statut`, `montant_total`, `created_at`, `updated_at`) VALUES
	(1, 'MIS-2026-001', 'Mission de supervision des structures de santé', 'nationale', 1, 2, 'Kayes', '2026-09-20', '2026-09-23', 4, 'brouillon', 0.00, NULL, NULL),
	(2, 'MIS-2026-002', 'Participation à une réunion internationale', 'internationale', 4, NULL, 'Paris', '2026-10-05', '2026-10-10', 6, 'brouillon', 0.00, NULL, NULL);
/*!40000 ALTER TABLE `missions` ENABLE KEYS */;

-- Listage de la structure de la table sigom. mission_agents
CREATE TABLE IF NOT EXISTS `mission_agents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mission_id` int(10) unsigned NOT NULL,
  `agent_id` int(10) unsigned NOT NULL,
  `nombre_jours` int(11) NOT NULL,
  `frais_journalier` decimal(15,2) NOT NULL DEFAULT '0.00',
  `indemnite_journaliere` decimal(15,2) NOT NULL DEFAULT '0.00',
  `taux_majoration` decimal(5,2) NOT NULL DEFAULT '0.00',
  `montant_majoration` decimal(15,2) NOT NULL DEFAULT '0.00',
  `montant_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mission_agents_mission_id_agent_id_unique` (`mission_id`,`agent_id`),
  KEY `mission_agents_agent_id_foreign` (`agent_id`),
  CONSTRAINT `mission_agents_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mission_agents_mission_id_foreign` FOREIGN KEY (`mission_id`) REFERENCES `missions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sigom.mission_agents : ~0 rows (environ)
/*!40000 ALTER TABLE `mission_agents` DISABLE KEYS */;
REPLACE INTO `mission_agents` (`id`, `mission_id`, `agent_id`, `nombre_jours`, `frais_journalier`, `indemnite_journaliere`, `taux_majoration`, `montant_majoration`, `montant_total`, `created_at`, `updated_at`) VALUES
	(1, 2, 5, 7, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL);
/*!40000 ALTER TABLE `mission_agents` ENABLE KEYS */;

-- Listage de la structure de la table sigom. password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sigom.password_reset_tokens : ~0 rows (environ)
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;

-- Listage de la structure de la table sigom. pays
CREATE TABLE IF NOT EXISTS `pays` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_iso` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zone_majoration_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pays_code_iso_unique` (`code_iso`),
  KEY `pays_zone_majoration_id_foreign` (`zone_majoration_id`),
  CONSTRAINT `pays_zone_majoration_id_foreign` FOREIGN KEY (`zone_majoration_id`) REFERENCES `zones_majoration` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sigom.pays : ~4 rows (environ)
/*!40000 ALTER TABLE `pays` DISABLE KEYS */;
REPLACE INTO `pays` (`id`, `nom`, `code_iso`, `zone_majoration_id`, `created_at`, `updated_at`) VALUES
	(1, 'Mali', 'MLI', 1, NULL, NULL),
	(2, 'Sénégal', 'SEN', 2, NULL, NULL),
	(3, 'Côte d\'Ivoire', 'CIV', 2, NULL, NULL),
	(4, 'France', 'FRA', 3, NULL, NULL);
/*!40000 ALTER TABLE `pays` ENABLE KEYS */;

-- Listage de la structure de la table sigom. personal_access_tokens
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

-- Listage des données de la table sigom.personal_access_tokens : ~0 rows (environ)
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;

-- Listage de la structure de la table sigom. profils
CREATE TABLE IF NOT EXISTS `profils` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom_profil` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profils_nom_profil_unique` (`nom_profil`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sigom.profils : ~3 rows (environ)
/*!40000 ALTER TABLE `profils` DISABLE KEYS */;
REPLACE INTO `profils` (`id`, `nom_profil`, `created_at`, `updated_at`) VALUES
	(1, 'Admin', NULL, NULL),
	(2, 'Contrôleur', '2023-09-26 20:47:15', '2023-09-26 20:47:15'),
	(3, 'Informatique', '2023-10-03 17:44:31', '2023-10-03 17:44:31');
/*!40000 ALTER TABLE `profils` ENABLE KEYS */;

-- Listage de la structure de la table sigom. regions
CREATE TABLE IF NOT EXISTS `regions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pays_id` bigint(20) unsigned NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `regions_pays_id_foreign` (`pays_id`),
  CONSTRAINT `regions_pays_id_foreign` FOREIGN KEY (`pays_id`) REFERENCES `pays` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sigom.regions : ~9 rows (environ)
/*!40000 ALTER TABLE `regions` DISABLE KEYS */;
REPLACE INTO `regions` (`id`, `pays_id`, `nom`, `code`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Bamako', 'BKO', NULL, NULL),
	(2, 1, 'Kayes', 'KYS', NULL, NULL),
	(3, 1, 'Koulikoro', 'KIK', NULL, NULL),
	(4, 1, 'Sikasso', 'SKS', NULL, NULL),
	(5, 1, 'Ségou', 'SEG', NULL, NULL),
	(6, 1, 'Mopti', 'MPT', NULL, NULL),
	(7, 1, 'Tombouctou', 'TOM', NULL, NULL),
	(8, 1, 'Gao', 'GAO', NULL, NULL),
	(9, 1, 'Kidal', 'KDL', NULL, NULL);
/*!40000 ALTER TABLE `regions` ENABLE KEYS */;

-- Listage de la structure de la table sigom. services
CREATE TABLE IF NOT EXISTS `services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom_service` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direction` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_nom_service_unique` (`nom_service`),
  KEY `services_direction_foreign` (`direction`),
  CONSTRAINT `services_direction_foreign` FOREIGN KEY (`direction`) REFERENCES `directions` (`nom_direction`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sigom.services : ~27 rows (environ)
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
REPLACE INTO `services` (`id`, `nom_service`, `description`, `direction`, `created_at`, `updated_at`) VALUES
	(6, '﻿SAGS', 'SERVICE ACTUARIAT ET GESTION STRATEGIQUE', '﻿DG', NULL, NULL),
	(7, 'SJC', 'SERVICE JURIDIQUE ET CONTENTIEUX', '﻿DG', NULL, NULL),
	(8, 'SCRP', 'SERVICE COMMUNICATION ET RELATIONS PUBLIQUES', '﻿DG', NULL, NULL),
	(9, 'SC', 'SERVICE COMPTABLILITE', 'AC', NULL, NULL),
	(10, 'SFT', 'SERVICE FINANCES ET TRESORERIE', 'AC', NULL, NULL),
	(11, 'SGA', 'SERVICE GESTION AMO', 'DAOGD', NULL, NULL),
	(12, 'SCR', 'SERVICE CONTROLE RECOUVREMENT', 'DAOGD', NULL, NULL),
	(13, 'SI', 'SERVICE IMMATRICULATION', 'DAOGD', NULL, NULL),
	(14, 'SCL', 'SERVICE CONTROLE LIQUIDATION', 'DAOGD', NULL, NULL),
	(15, 'SCNMC', 'SERVICE CONVENTIONNEMENT NORMES ET MATRISE DES COUTS', 'DOCQS', NULL, NULL),
	(16, 'SPS', 'SERVICE PRESTATIONS SANITAIRES', 'DOCQS', NULL, NULL),
	(17, 'SCME', 'SERVICE CONTROLE MEDICAL DES ETABLISSEMENTS', 'DOCQS', NULL, NULL),
	(18, 'SASRT', 'SERVICES ADMINISTRATION SYSTEMES RESEAUX ET TELEPHONIE', 'DSI', NULL, NULL),
	(19, 'SGPMEPC', 'SERVICE GESTION PARC MICRO ETUDES ET PRODUCTION DES CARTES', 'DSI', NULL, NULL),
	(20, 'SB', 'SERVICE BUDGET', 'DBCGOQ', NULL, NULL),
	(21, 'SCG', 'SERVICE CONTROLE DE GESTION', 'DBCGOQ', NULL, NULL),
	(22, 'SOQ', 'SERVICE ORGANISATION QUALITE', 'DBCGOQ', NULL, NULL),
	(23, 'SGRH', 'SERVICE GESTION DES RESSOURCES HUMAINES ', 'DAGRH', NULL, NULL),
	(24, 'SMG', 'SERVICE MOYENS GENERAUX', 'DAGRH', NULL, NULL),
	(25, 'SF', 'SERVICE FORMATION', 'DAGRH', NULL, NULL),
	(26, 'SAD', 'SERVICE ARCHIVES ET DOCUMENTATION', 'DAGRH', NULL, NULL),
	(37, 'BR KOULIKORO', 'BUREAU REGIONAL DE KOULIKORO', 'BUREAUX REGIONAUX', '2023-10-16 22:54:48', '2023-10-16 22:54:48'),
	(38, 'BR SIKASSO', 'BUREAU REGIONAL DE SIKASSO', 'BUREAUX REGIONAUX', '2023-10-16 22:56:14', '2023-10-16 22:56:14'),
	(39, 'BR SEGOU', 'BUREAU REGIONAL DE SEGOU', 'BUREAUX REGIONAUX', '2023-10-16 22:56:52', '2023-10-16 22:56:52'),
	(40, 'BR MOPTI', 'BUREAU REGIONAL DE MOPTI', 'BUREAUX REGIONAUX', '2023-10-16 22:57:40', '2023-10-16 22:57:40'),
	(41, 'PF GAO', 'POINT FOCAL REGIONAL DE GAO', 'BUREAUX REGIONAUX', '2023-10-16 22:58:58', '2023-10-16 22:58:58'),
	(42, 'PF TOMBOUCTOU', 'POINT FOCAL REGIONAL DE TOMBOUCTOU', 'BUREAUX REGIONAUX', '2023-10-16 22:59:47', '2023-10-16 22:59:47'),
	(43, 'DG', 'DIRECTION GENERAL', 'DG', '2026-09-15 12:16:34', '2026-09-15 12:16:34');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;

-- Listage de la structure de la table sigom. users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profil` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_profil_foreign` (`profil`),
  CONSTRAINT `users_profil_foreign` FOREIGN KEY (`profil`) REFERENCES `profils` (`nom_profil`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sigom.users : ~24 rows (environ)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
REPLACE INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `profil`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Administrateur', 'admin@canam.ml', '2023-09-26 20:29:24', '$2y$10$rInQuIbJmJqdCEYKovI0pO5905FQJ3cWws2plejHtYnENGgyeqGgu', 'Admin', 'pzBAiMQyrYib83QllXrhEaehlpypYE5J3UtsEAInd8iBcqz5BUNjvBnvaeQG', NULL, '2023-10-03 17:17:29'),
	(3, 'Dr SACKO ROKIATOU FOUNE', 'sfoune@canam.ml', NULL, '$2y$10$Y/YjnXzvzXWmh8FvxYoITuO5fB6rrXIBJs7xOsuz2u2.VbUdsXzTS', 'Contrôleur', 'JNkbGMc4VcgjQKd7x3ywACuzZ4GsxxjHTbYhaOJUNTvCd72rc8kfp6tvHmuX', '2023-09-26 21:37:30', '2023-10-12 06:50:02'),
	(4, 'Dr MARIAME DITE YAMA BOUARE ', 'b.mariame@canam.ml', NULL, '$2y$10$ZGUH7KrFLlf9GZtnLYiFRem5qK/TB7Yyqhjq3VyAck4byv1Bvyyiu', 'Contrôleur', NULL, '2023-09-27 06:52:29', '2023-10-09 06:56:12'),
	(5, 'Dr Mossere CAMARA', 'cmossere@canam.ml', NULL, '$2y$10$I86k/T.X3FXfNBflK7jWAe8y77Cl4pgeWTjXk8HaZBkwi1ZqyV3k.', 'Contrôleur', 'PYULQOCcfZxiKrAagXHI7RMOp7zMBZZSkuJTuPkOyS7POoF6EVRTTMMawXHn', '2023-09-27 06:55:46', '2023-09-27 06:55:46'),
	(6, 'Dr Naye CAMARA BA', 'cnaye@canam.ml', NULL, '$2y$10$uBuW.ZxYFl6w7TsioKj5ve3x1rTZWxzgdTQQR6wSA2PaSL7FkJQCi', 'Contrôleur', NULL, '2023-09-27 06:57:13', '2023-10-05 06:59:21'),
	(7, 'Dr AICHATOU LOUISE KONATE', 'kaichatou@canam.ml', NULL, '$2y$10$TYnrm2dEFo.Aqz9vsgwC/.k1kGIJFib3cZAibBRHSoMzpE8z/Ipuy', 'Contrôleur', 'IcZ3FuGEi8Rz6Jzko0isFNvfISMm5D3TnPt5AG4oTLJIQXEv0MZoqaWEaYbE', '2023-09-27 07:00:31', '2023-10-10 08:22:58'),
	(8, 'DR SANE N\'DIAYE', 'nsane@canam.ml', NULL, '$2y$10$vh1hh2ewkwvlAe5JQfgMYeYQ0tIHlOeEuSyiNZV9nT8kCE8XR6IeK', 'Contrôleur', '7JBbQAanDCCNT2s2WABrsN66hh1SkDzS1eM5tLaWAMUdCgZarPZLCUJqSiAb', '2023-09-27 07:01:55', '2023-10-05 06:59:54'),
	(9, 'DR KOROTOUMOU KONE', 'kkorotoumou@canam.ml', NULL, '$2y$10$4kP0jYqWq7BT53sw35Jgk.DxXiP566yYkutbEMAVNqyvZMWEvb5ne', 'Contrôleur', 'NZ4hQ2pCXbTrdgT8qPolCu7cX5Unzs9R6LBStcErlrdnCxV7CChVSwe4LiZA', '2023-09-27 07:04:27', '2023-10-05 07:00:40'),
	(10, 'Dr El Hadj Belco TOURE', 'tbelco@canam.ml', NULL, '$2y$10$OGjFNXC1zM29lPAdQiY7GuWE2FwTZQGHQ2VbZmIfBAOGY.oKg0OtS', 'Contrôleur', NULL, '2023-09-27 07:05:46', '2023-09-27 07:05:46'),
	(11, 'Dr Ali CISSE', 'cali@canam.ml', NULL, '$2y$10$lruqyf5vIlDQDplNFucI6uNk2Xm1tdnvUpERGIcPWS./KIbKFV5we', 'Contrôleur', NULL, '2023-09-27 07:06:37', '2023-09-27 07:06:37'),
	(13, 'Dr KADIATOU DIARRA', 'dkadiatou@canam.ml', NULL, '$2y$10$vMM0GZvweJruvrxYSvXZDOZu3cYehoXkS7mbyiWb8SX8WpwTTu4sS', 'Contrôleur', NULL, '2023-09-27 07:11:02', '2023-09-27 07:11:02'),
	(14, 'Dr N\'DIAYE Kadiatou DIALLO', 'nkadiatou@canam.ml', NULL, '$2y$10$sxLa0BFZcbb03EU34SzBCeZudb5uEPS01f5I3BWg/xs46A3h29MvW', 'Contrôleur', NULL, '2023-09-27 07:12:57', '2023-09-27 07:12:57'),
	(15, 'Dr Youssouf Baba OUATTARA', 'oyoussouf@canam.ml', NULL, '$2y$10$mI4nHtSl0mQKv1uj8oI7GuoXGhsPyde4B24Xqt1xSnJbqUz6/0dG.', 'Contrôleur', NULL, '2023-09-27 07:13:49', '2023-09-27 07:13:49'),
	(16, 'Dr Mamadi DIAWARA', 'dmamadi@canam.ml', NULL, '$2y$10$/hf8yvOiwwKhnxx2bOqd/e4h2kS7Ir4XSNBbCv4jz4bTrVNZXjedW', 'Contrôleur', NULL, '2023-09-27 07:14:47', '2023-09-27 07:14:47'),
	(17, 'Mme COULIBALY Kadiatou SANGARE', 'skadiatou@canam.ml', NULL, '$2y$10$jh32IHIgu26lXyU1iAufP.XIZeiCDmAm9OHipfpinDUynskhsPD9K', 'Contrôleur', NULL, '2023-09-27 07:15:57', '2023-10-03 22:48:42'),
	(18, 'Dr Adama BERTHE', 'badama@canam.ml', NULL, '$2y$10$N5uTFGCjNdI06NRPLwqAS.aqrNGBqGvjlMWT/W1O6rJKlQdbWLw26', 'Contrôleur', NULL, '2023-09-27 07:16:39', '2023-10-03 23:16:52'),
	(19, 'Dr Allaye DIA', 'dallaye@canam.ml', NULL, '$2y$10$FPOVYyxIZm0two/P3yXVvuXvYQFcXO93U2JvayvjlyaAlkGJaoOAO', 'Contrôleur', NULL, '2023-09-27 07:17:28', '2023-10-03 23:19:04'),
	(20, 'Mahamane Amar Maiga', 'mmahamaneamar@canam.ml', NULL, '$2y$10$JhLFNvL.XSrVq6IXAd92Sut1xC5s9ibESKcEo6dI3mcsSvLXReFsu', 'Informatique', NULL, '2023-10-03 17:45:17', '2026-03-06 11:19:38'),
	(21, 'Assitan CISSE', 'cassitan@canam.ml', NULL, '$2y$10$Voh7WHrzZdABvM89ERtLBepo0IBbc8QhlrpFaZacrhBpZjy.UZfyG', 'Informatique', NULL, '2023-10-04 13:18:13', '2023-10-05 06:45:07'),
	(22, 'Mme DIARRA Mariam DIARRA', 'dmariam@canam.ml', NULL, '$2y$10$tAwQDvRZZQTXq6NzFdSpvuDi/tE6sTgLLaboAuxJN7yKJu1cMeTNa', 'Informatique', NULL, '2023-10-04 13:20:07', '2023-10-04 13:20:07'),
	(23, 'Ousmane O SANOGO', 'sousmane@canam.ml', NULL, '$2y$10$l4aP5isJDY/LXLd3xXovC.xEht98ZdD1o2cGdvqYAJpnIbScsQSOq', 'Admin', NULL, '2023-10-05 06:38:54', '2023-10-05 06:38:54'),
	(24, 'Dr Oumar NDiaye', 'ndiayeoumar@canam.ml', NULL, '$2y$10$7ZRcEIQal9iNJoP0jbGQwu2M/Q2grhEuVsulIvBqFF3LLEf8Yhq0q', 'Contrôleur', '1br9l1Mv7jx6NIfiO8VSyW3ryX1Ubn2kvcSMd4IxjqsxZQnWw50grdyuQlkU', '2025-06-05 13:20:43', '2026-08-06 11:04:10'),
	(25, 'Issa DIARRA', 'dissa@canam.ml', NULL, '$2y$10$BYeH2s2V54Vf78Y8.Unfe.sb888P7XChHLdmirjYgHD5mYNk0a5fi', 'Contrôleur', NULL, '2026-07-20 08:17:41', '2026-07-20 08:17:41'),
	(26, 'Boubacar DIALLO', 'dboubacar@canam.ml', NULL, '$2y$10$ft1zELwE.Soq3ksyTJr7puYSNPlU89BrU.VaOzPxphgztOMg51A8m', 'Contrôleur', NULL, '2026-07-20 09:21:31', '2026-07-20 09:21:31');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Listage de la structure de la table sigom. zones_majoration
CREATE TABLE IF NOT EXISTS `zones_majoration` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `taux` decimal(5,2) NOT NULL DEFAULT '0.00',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `zones_majoration_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sigom.zones_majoration : ~3 rows (environ)
/*!40000 ALTER TABLE `zones_majoration` DISABLE KEYS */;
REPLACE INTO `zones_majoration` (`id`, `code`, `libelle`, `taux`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'ZONE_A', 'Zone A', 10.00, 'Majoration de 10%', '2026-09-14 15:39:20', '2026-09-14 15:39:20'),
	(2, 'ZONE_B', 'Zone B', 20.00, 'Majoration de 20%', '2026-09-14 15:39:20', '2026-09-14 15:39:20'),
	(3, 'ZONE_C', 'Zone C', 30.00, 'Majoration de 30%', '2026-09-14 15:39:20', '2026-09-14 15:39:20');
/*!40000 ALTER TABLE `zones_majoration` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
