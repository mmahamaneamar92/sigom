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


-- Listage de la structure de la base pour galdap
CREATE DATABASE IF NOT EXISTS `galdap` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `galdap`;

-- Listage de la structure de la table galdap. alds
CREATE TABLE IF NOT EXISTS `alds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code_ald` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation_ald` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix_rembousement_ald` int(11) NOT NULL,
  `prix_public_ald` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_ald` (`code_ald`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table galdap.alds : ~0 rows (environ)
/*!40000 ALTER TABLE `alds` DISABLE KEYS */;
/*!40000 ALTER TABLE `alds` ENABLE KEYS */;

-- Listage de la structure de la table galdap. aps
CREATE TABLE IF NOT EXISTS `aps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code_ap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation_ap` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix_rembousement_ap` int(11) NOT NULL,
  `prix_public_ap` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_ap` (`code_ap`)
) ENGINE=InnoDB AUTO_INCREMENT=500 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table galdap.aps : ~116 rows (environ)
/*!40000 ALTER TABLE `aps` DISABLE KEYS */;
INSERT INTO `aps` (`id`, `code_ap`, `designation_ap`, `prix_rembousement_ap`, `prix_public_ap`, `created_at`, `updated_at`) VALUES
	(1, 'PTMS428', 'EFFORTIL IO mg/ml injectable, boite de 6', 139650, 199500, '2023-09-27 18:46:53', '2023-09-27 18:46:53'),
	(2, 'PTM5759', 'GRAM-DENK 1 mg/ mL solution à diluer pour injection ou perfusion, boîte de 5', 25858, 36940, '2023-09-28 11:45:59', '2023-09-28 11:45:59'),
	(384, 'PTM5837', 'IMUREL 50 mg comprimé boite de 100', 22225, 31750, NULL, NULL),
	(385, 'PTM2573', 'LYRICA 50 mg gélule, boîte de 84', 32613, 46590, NULL, NULL),
	(386, 'PTM6059', 'LYRICA 75 mg gélule, boîte de 56', 25200, 36000, NULL, NULL),
	(387, 'PTM6060', 'LYRICA 75 mg gélule, boîte de 84', 34440, 49200, NULL, NULL),
	(388, 'PTM6271', 'NIMOTOP 10mg/50ml ', 31500, 45000, NULL, NULL),
	(389, 'PTM6272', 'NIMOTOP 30mg comprimé, erfusion flacon de 50 ml boite de 30', 15330, 21900, NULL, NULL),
	(390, 'PTM6296', 'NovoMix@ 30 FlexPen@ 100 UI/ml Suspension injectable en stylo pré-rempli, boîte de 5 x 3 ml', 29243, 41775, NULL, NULL),
	(391, 'PTM6297', 'NovoRapid@ Flexpen@ 100U1/ ml solution injectable en stylo pré-rempli, boîte de 5X3 ml', 29243, 41775, NULL, NULL),
	(392, 'PTM6346', 'OMNIPAQUE 300mg flacon de 100 ml', 36890, 52700, NULL, NULL),
	(393, 'PTM6347', 'OMNIPAQUE 300mg flacon de 50 ml', 17640, 25200, NULL, NULL),
	(394, 'PTM6349', 'OMNIPAQUE 350mg flacon de IOO ml', 35700, 51000, NULL, NULL),
	(395, 'PTM6350', 'OMNIPAQUE 350mg flacon de 50 ml', 23100, 33000, NULL, NULL),
	(396, 'PTM3195', 'PENTASA 1 g suppositoire, boîte de 15', 25130, 35900, NULL, NULL),
	(397, 'PTM3196', 'PENTASA 1 g/100 ml suspension rectale, boîte de 5 flacons', 23170, 33100, NULL, NULL),
	(398, 'PTM3197', 'PENTASA 500 mg comprimé, boîte de 10', 28326, 40465, NULL, NULL),
	(399, 'vrM6895', 'TEGELINE 50mg/ml perfusion, flacon de 100 ml', 187831, 268330, NULL, NULL),
	(400, 'PTM7058', 'UMUI.INE NPH 100 UI/m1 solution injectable, flacon de 10 ml', 28350, 40500, NULL, NULL),
	(401, 'PTM7059', 'UMULINE Profil 30 100 Ullml suspension injectable, flacon de 10 ml', 28350, 40500, NULL, NULL),
	(402, 'PTM7060', 'UMULINE Rapide 100 Ul/ml solution injectable, nacon de 10 ml', 28350, 40500, NULL, NULL),
	(403, 'PTM4369', 'ZAXTER IG injectable, boite de 1', 21018, 30025, NULL, NULL),
	(404, 'PTM4370', 'ZAXTER 500mg injectable, boite de 2', 11421, 16315, NULL, NULL),
	(405, 'PCM0001', 'VIS A CORTICALE STANDARD 4.5', 4515, 6450, NULL, NULL),
	(406, 'PCM0002', 'VIS A CORTICALE STANDARD 3.5', 5880, 8400, NULL, NULL),
	(407, 'PCM0003', 'VIS A CORTICALE STANDARD 2.7', 7140, 10200, NULL, NULL),
	(408, 'PCM0004', 'VIS A SPONGIEUSE STANDARD 6.5', 9310, 13300, NULL, NULL),
	(409, 'PCM0005', 'VIS A SPONGIEUSE STANDARD', 9310, 13300, NULL, NULL),
	(410, 'PCM0006', 'VIS A SPONGIEUSE STANDARD MALLEOLAIRE', 9730, 13900, NULL, NULL),
	(411, 'PCM0007', 'VIS COULISSANTE 1175-110', 50050, 71500, NULL, NULL),
	(412, 'PCM0008', 'RONDELLES POUR VIS A SPONGIEUSE', 3710, 5300, NULL, NULL),
	(413, 'PCM0009', 'PLAQUES AO STANDARD : PLAQUES DROITES LARGES', 80990, 115700, NULL, NULL),
	(414, 'PCM0010', 'PLAQUES AO STANDARD : PLAQUES DROITES ETROITES', 34440, 49200, NULL, NULL),
	(415, 'PCM0011', 'PLAQUES AO STANDARD : PLAQtES SPECIALES POUR ZONE METAPHYSOEPIPHYSAIRE : EN T', 64330, 91900, NULL, NULL),
	(416, 'PCM0012', 'PLAQUES AO STANDARD : PLAQUES SPECIAL-ES POUR ZONE METAPHYSOEPIPHYSAIRE : EN L', 64330, 91900, NULL, NULL),
	(417, 'PCM0013', 'PLAQUES AO STANDARD : PLAQUES SPECIALES POUR ZONE METAPHVSOEPIPHYSAIRE : EN Y', 64330, 91900, NULL, NULL),
	(418, 'PCM0014', 'PLAQUES AO STANDARD : PLAQUE- CUILLERE', 93590, 133700, NULL, NULL),
	(419, 'PCM0015', 'PLAQUES AO STANDARD : PLAQUE TREUE', 68880, 98400, NULL, NULL),
	(420, 'PCM0016', 'PLAQUES DCP 6-12 TROUS', 58240, 83200, NULL, NULL),
	(421, 'PCM0017', 'PLAQUES COUDEES : LAME-PLAQUE CONDVLIENNES .A900', 75250, 107500, NULL, NULL),
	(422, 'PCM0018', 'PLAQUES COUDEES : LAME-PLAQUE CONDYLIENNES A950', 75250, 107500, NULL, NULL),
	(423, 'PCM0019', 'PLAQUES COUDEES : LAME-PLAQUES A 1300', 75250, 107500, NULL, NULL),
	(424, 'PCM0020', 'PLAQUES COUDEES : LAME-PLAQUES « COL DE CYGNE »', 85330, 121900, NULL, NULL),
	(425, 'PCM0021', 'VIS-PLAQUES A COMPRESSION DYNAMI UE DHS', 7070, 10100, NULL, NULL),
	(426, 'PCM0022', 'CLOU CENTROMEDULLAIRE POUR TIBIA', 311570, 445100, NULL, NULL),
	(427, 'PCM0023', 'CLOU CENTROMEDULLAIRE POUR TIBIA POUR FEMUR', 324100, 463000, NULL, NULL),
	(428, 'PCM0024', 'BROCHE DE KIRCHNER 1.2', 3990, 5700, NULL, NULL),
	(429, 'PCM0025', 'BROCHE DE KIRCHNER 1.5', 3990, 5700, NULL, NULL),
	(430, 'PCM0026', 'BROCHE DE KIRCHNER 1.8', 3990, 5700, NULL, NULL),
	(431, 'PCM0027', 'BROCHE DE KIRCHNER', 3990, 5700, NULL, NULL),
	(434, 'PCM0028', 'PROTHESE TOTALE DE LA HANCHE : CUPULE 50 A 62', 459900, 657000, NULL, NULL),
	(435, 'PCM0029', 'PROTHESE TOTALE DE LA HANCHE : INSERT EN POLYETHYLENE 50 A 62', 114240, 163200, NULL, NULL),
	(436, 'PCM0030', 'PROTHESE TOTALE DE LA HANCHE : TIGE FEMORALE 1 A 8', 474740, 678200, NULL, NULL),
	(437, 'PCM0031', 'PROTHESE TOTALE DE LA HANCHE : TETE FEMORALE 28/-3.5 ; 28/0 ; 281+3.5 ; 281+8', 140980, 201400, NULL, NULL),
	(438, 'PCM0032', 'PROTHESE INTERMEDIAIRE (CERVICO- CEPHALIQUE) : CUPULE BLINDEE 40 à 56', 459900, 657000, NULL, NULL),
	(439, 'PCM0033', 'PROTHESE INTERMEDIAIRE (CERVICO- CEPHALIQUE) : TIGE FEMORALE 1 à 8', 474740, 678200, NULL, NULL),
	(440, 'PCM0034', 'PROTHESE INTERMEDIAIRE(CERVICO ENCEPHALIQUE) : TETE FEMORALE 28/- 3.5 ; 28/0 ; 281+3.5 ; 281+8', 140980, 201400, NULL, NULL),
	(441, 'PCM0035', 'CUPULE BIPOLAIRE 56', 232050, 331500, NULL, NULL),
	(442, 'PCM0036', 'VIS A CUPULE L25-45 MM', 16520, 23600, NULL, NULL),
	(443, 'PCM0037', 'FOND DE COTYLE METALLIQUE POUR CUPULE 50-62', 173950, 248500, NULL, NULL),
	(444, 'PCM0038', 'KIT COMPLET APPLICATEUR CIMENT', 36470, 52100, NULL, NULL),
	(445, 'PCM0039', 'UNITE DE LAVAGE TRAUMATOLOGIE', 97300, 139000, NULL, NULL),
	(446, 'PCM0040', 'CENTRALISEUR TIGE1-8', 29190, 41700, NULL, NULL),
	(447, 'PCM0041', 'RESTRICTEUR TIGE1-8', 29190, 41700, NULL, NULL),
	(448, 'PCM0042', 'CIMENT OSSEUX HAUTE VISCOSITE 1.00', 68600, 98000, NULL, NULL),
	(449, 'PCM0043', 'CIMENT OSSEUX BASSE VISCOSITE 3.00', 68600, 98000, NULL, NULL),
	(450, 'PCM0044', 'CIMENT OSSEtx HAUTE VISCOSITE + GENTAMICINE 1.00', 86450, 123500, NULL, NULL),
	(451, 'PCM0045', 'CIMENT OSSEUX BASSE VISCOSITE + GENTAMICINE 3MV', 86450, 123500, NULL, NULL),
	(452, 'PCM0046', 'CIMENT OSSEUX MOYENNE VISCOSITE + GENTAMICINE', 86450, 123500, NULL, NULL),
	(453, 'PCM0047', 'SERINGUE A BOUT LONG', 22330, 31900, NULL, NULL),
	(454, 'PCM0048', 'BOLE ET SPATULE', 12950, 18500, NULL, NULL),
	(455, 'PCM0049', 'KIT VACUUM', 134050, 191500, NULL, NULL),
	(456, 'PCM0050', 'OBTURATEU\'R', 68600, 98000, NULL, NULL),
	(457, 'PCM0051', 'DIAPHYSAIRE SOUPLE BIODEGRADABLE', 68600, 98000, NULL, NULL),
	(458, 'PCM0096', 'INSTRUMENT KIT COMPLET', 577500, 825000, NULL, NULL),
	(459, 'PCM0052', 'SUBSTITUT OSSEUXPHOSPHOCALCIQ', 308910, 441300, NULL, NULL),
	(460, 'PCM0053', 'INSTRUMENT KIT DE MIXAGE', 226520, 323600, NULL, NULL),
	(461, 'PCM0054', 'INSTRUMENT KIT D\'INJECTION', 219660, 313800, NULL, NULL),
	(462, 'PCM0055', 'GEL NANOPARTICULES HYDROXYPATITE', 199080, 284400, NULL, NULL),
	(463, 'PCM0059', 'EPONGES COLLAGENES HEMOSTATIQUES', 339815, 485450, NULL, NULL),
	(464, 'PCM0060', 'SUBSTITUT COLLAGENE + OSSEUXOSTEOCONDUCTE UR RESORBABLE PHOSPHATE CALCIUM', 186550, 266500, NULL, NULL),
	(465, 'PCM0061', 'PROTHESE TEMPORAIRE', 1201210, 1716100, NULL, NULL),
	(466, 'PCM0062', 'PROTHESE DE MOORE O 37-55 MM', 274750, 392500, NULL, NULL),
	(467, 'PCM0063', 'PROTHESES DE THOMSON 0 38-55 MM', 201390, 287700, NULL, NULL),
	(468, 'PCM0064', 'MATERIEL D\'OSTEOSYNTHESE CERVICAL', 252000, 360000, NULL, NULL),
	(469, 'PCM0065', 'MATERIEL D\'OSTEOSYNTHESE DORSAL', 336000, 480000, NULL, NULL),
	(470, 'PCM0066', 'MATERIEL D\'OSTEOSYNTHESE LOMBAIRE', 336000, 480000, NULL, NULL),
	(471, 'PCM0067', 'MATERIEL D\'OSTEOSYNTHESE DORSOLOMBAIRE', 336000, 480000, NULL, NULL),
	(472, 'PCM0068', 'Consultation CNAOM', 336000, 1500, NULL, NULL),
	(473, 'PCM0069', 'PROTHESE FEMORALE', 336000, 250000, NULL, NULL),
	(474, 'PCM0070', 'PROTHESE TIBIALE', 336000, 120000, NULL, NULL),
	(475, 'PCM0071', 'CORSET', 336000, 100000, NULL, NULL),
	(476, 'PCM0072', 'PROTHESE MEMBRE SUPERIEUR(TH)', 336000, 300000, NULL, NULL),
	(477, 'PCM0073', 'PROTHESE MEMBRE SUPERIEUR AVEC GANT', 336000, 500000, NULL, NULL),
	(478, 'PCM0074', 'GANT ESTHETIQUE', 336000, 200000, NULL, NULL),
	(479, 'PCM0075', 'PROTHESE DE DEARTICULATION DE LA HANCHE PROTHESE CANADIENNE', 336000, 300000, NULL, NULL),
	(480, 'PCM0076', 'APPAREIL DE SOUTIEN+ RELEVEUR (KAFO)', 336000, 50000, NULL, NULL),
	(481, 'PCM0077', 'APPAREIL DE SOUTIEN+CHAUSSURE ORTHOPETIQUE', 336000, 55000, NULL, NULL),
	(482, 'PCM0078', 'RELEVEUR PP ENFANT(AFO)', 336000, 15000, NULL, NULL),
	(483, 'PCM0079', 'RELEVEUR PP ADULTE(AFO)', 336000, 15000, NULL, NULL),
	(484, 'PCM0080', 'COLIER ', 336000, 20000, NULL, NULL),
	(485, 'PCM0081', 'ATTELLE PALMAIRE ENFANT', 336000, 10000, NULL, NULL),
	(486, 'PCM0082', 'ATTELLE PALMAIRE ADULTE', 336000, 10000, NULL, NULL),
	(487, 'PCM0083', 'APPAREIL DE DECHARGE (ENFANT)', 336000, 30000, NULL, NULL),
	(488, 'PCM0084', 'APPAREIL DE DECHARGE (ADULTE)', 336000, 30000, NULL, NULL),
	(489, 'PCM0085', 'ORTHOPROTHESE (PROTHESE CONGENITALE) SANS GENOUX', 336000, 60000, NULL, NULL),
	(490, 'PCM0086', 'ORTHOPROTHESE (PROTHESE CONGENITALE) AVEC GENOUX', 336000, 60000, NULL, NULL),
	(491, 'PCM0087', 'PROTHESE DE DESARTICULATION DU PIED CHOPART', 336000, 60000, NULL, NULL),
	(492, 'PCM0088', 'CEINTURE (BRETTELLE)', 336000, 5000, NULL, NULL),
	(493, 'PCM0089', 'GRAND APPAREIL DE MARCHE(GAM) REGARNITURE', 336000, 10000, NULL, NULL),
	(494, 'PCM0090', 'CORSET, SANGLE ET AUTRES', 336000, 10000, NULL, NULL),
	(495, 'PCM0091', 'CHAUSSURE ORTHOPEDIQUE', 336000, 10000, NULL, NULL),
	(496, 'PCM0092', 'EMBOUT EN PP POUR LES CANNES', 336000, 1000, NULL, NULL),
	(497, 'PCM0093', 'REGARNITURE DE L’APPAREIL AVEC VELCRO', 336000, 10000, NULL, NULL),
	(498, 'PCM0094', 'ATTELLE STEENBECK', 336000, 10000, NULL, NULL),
	(499, 'PCM0095', 'CARNET DE SUIVI PIED BOT', 336000, 1000, NULL, NULL);
/*!40000 ALTER TABLE `aps` ENABLE KEYS */;

-- Listage de la structure de la table galdap. assures
CREATE TABLE IF NOT EXISTS `assures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `numero_assurer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ogd` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_assurer` (`numero_assurer`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table galdap.assures : ~2 rows (environ)
/*!40000 ALTER TABLE `assures` DISABLE KEYS */;
INSERT INTO `assures` (`id`, `numero_assurer`, `prenom`, `nom`, `tel`, `ogd`, `created_at`, `updated_at`) VALUES
	(1, '1010101010010', 'Mahamane Amar', 'MAIGA', '78859432', 'INPS', '2023-09-24 23:07:30', '2023-10-04 20:04:48'),
	(2, '1951330100004', 'Nafo', 'KONE', '20202121', 'CMSS', '2023-09-25 10:55:35', '2023-09-25 10:55:35'),
	(3, '2981910100445', 'Sane', 'N\'DIAYE', '76435694', 'INPS', '2023-09-25 11:29:33', '2023-10-04 20:04:10');
/*!40000 ALTER TABLE `assures` ENABLE KEYS */;

-- Listage de la structure de la table galdap. failed_jobs
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

-- Listage des données de la table galdap.failed_jobs : ~0 rows (environ)
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

-- Listage de la structure de la table galdap. hopitals
CREATE TABLE IF NOT EXISTS `hopitals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code_hopital` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_hopital` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_hopital` (`code_hopital`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table galdap.hopitals : ~0 rows (environ)
/*!40000 ALTER TABLE `hopitals` DISABLE KEYS */;
INSERT INTO `hopitals` (`id`, `code_hopital`, `nom_hopital`, `created_at`, `updated_at`) VALUES
	(1, '11111', 'Gabriel Touré', '2023-09-24 23:20:36', '2023-09-26 00:23:00');
/*!40000 ALTER TABLE `hopitals` ENABLE KEYS */;

-- Listage de la structure de la table galdap. migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table galdap.migrations : ~15 rows (environ)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
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

-- Listage de la structure de la table galdap. password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table galdap.password_reset_tokens : ~0 rows (environ)
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;

-- Listage de la structure de la table galdap. personal_access_tokens
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

-- Listage des données de la table galdap.personal_access_tokens : ~0 rows (environ)
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;

-- Listage de la structure de la table galdap. pharmacies
CREATE TABLE IF NOT EXISTS `pharmacies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code_pharmacie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_pharmacie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_pharmacie` (`code_pharmacie`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table galdap.pharmacies : ~0 rows (environ)
/*!40000 ALTER TABLE `pharmacies` DISABLE KEYS */;
INSERT INTO `pharmacies` (`id`, `code_pharmacie`, `nom_pharmacie`, `created_at`, `updated_at`) VALUES
	(1, '66666', 'Bougiba', '2023-09-24 23:23:50', '2023-09-26 00:27:34');
/*!40000 ALTER TABLE `pharmacies` ENABLE KEYS */;

-- Listage de la structure de la table galdap. prescripteurs
CREATE TABLE IF NOT EXISTS `prescripteurs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code_prescripteur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom_prescripteur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_prescripteur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_prescripteur` (`code_prescripteur`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table galdap.prescripteurs : ~0 rows (environ)
/*!40000 ALTER TABLE `prescripteurs` DISABLE KEYS */;
INSERT INTO `prescripteurs` (`id`, `code_prescripteur`, `prenom_prescripteur`, `nom_prescripteur`, `created_at`, `updated_at`) VALUES
	(1, '00002', 'Oumar', 'COULIBALY', '2023-09-24 23:36:56', '2023-09-26 00:35:01');
/*!40000 ALTER TABLE `prescripteurs` ENABLE KEYS */;

-- Listage de la structure de la table galdap. prestations
CREATE TABLE IF NOT EXISTS `prestations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `prescripteur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assure` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nature_examen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `renseignement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `technicien` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provenance` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pharmacie` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `feuille` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `controleur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ap1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ap2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ap3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ap4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ap5` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ap6` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ap7` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ap8` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ap9` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ap10` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type5` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type6` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type7` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type8` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type9` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type10` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantite1` int(11) NOT NULL,
  `quantite2` int(11) DEFAULT NULL,
  `quantite3` int(11) DEFAULT NULL,
  `quantite4` int(11) DEFAULT NULL,
  `quantite5` int(11) DEFAULT NULL,
  `quantite6` int(11) DEFAULT NULL,
  `quantite7` int(11) DEFAULT NULL,
  `quantite8` int(11) DEFAULT NULL,
  `quantite9` int(11) DEFAULT NULL,
  `quantite10` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `prestations_assure_foreign` (`assure`),
  KEY `prestations_prescripteur_foreign` (`prescripteur`),
  KEY `prestations_technicien_foreign` (`technicien`),
  KEY `prestations_provenance_foreign` (`provenance`),
  KEY `prestations_pharmacie_foreign` (`pharmacie`),
  KEY `prestations_controleur_foreign` (`controleur`),
  KEY `prestations_ap1_foreign` (`ap1`),
  KEY `prestations_ap2_foreign` (`ap2`),
  KEY `prestations_ap3_foreign` (`ap3`),
  KEY `prestations_ap4_foreign` (`ap4`),
  KEY `prestations_ap5_foreign` (`ap5`),
  KEY `prestations_ap6_foreign` (`ap6`),
  KEY `prestations_ap7_foreign` (`ap7`),
  KEY `prestations_ap8_foreign` (`ap8`),
  KEY `prestations_ap9_foreign` (`ap9`),
  KEY `prestations_ap10_foreign` (`ap10`),
  CONSTRAINT `prestations_ap10_foreign` FOREIGN KEY (`ap10`) REFERENCES `aps` (`code_ap`) ON DELETE CASCADE,
  CONSTRAINT `prestations_ap1_foreign` FOREIGN KEY (`ap1`) REFERENCES `aps` (`code_ap`) ON DELETE CASCADE,
  CONSTRAINT `prestations_ap2_foreign` FOREIGN KEY (`ap2`) REFERENCES `aps` (`code_ap`) ON DELETE CASCADE,
  CONSTRAINT `prestations_ap3_foreign` FOREIGN KEY (`ap3`) REFERENCES `aps` (`code_ap`) ON DELETE CASCADE,
  CONSTRAINT `prestations_ap4_foreign` FOREIGN KEY (`ap4`) REFERENCES `aps` (`code_ap`) ON DELETE CASCADE,
  CONSTRAINT `prestations_ap5_foreign` FOREIGN KEY (`ap5`) REFERENCES `aps` (`code_ap`) ON DELETE CASCADE,
  CONSTRAINT `prestations_ap6_foreign` FOREIGN KEY (`ap6`) REFERENCES `aps` (`code_ap`) ON DELETE CASCADE,
  CONSTRAINT `prestations_ap7_foreign` FOREIGN KEY (`ap7`) REFERENCES `aps` (`code_ap`) ON DELETE CASCADE,
  CONSTRAINT `prestations_ap8_foreign` FOREIGN KEY (`ap8`) REFERENCES `aps` (`code_ap`) ON DELETE CASCADE,
  CONSTRAINT `prestations_ap9_foreign` FOREIGN KEY (`ap9`) REFERENCES `aps` (`code_ap`) ON DELETE CASCADE,
  CONSTRAINT `prestations_assure_foreign` FOREIGN KEY (`assure`) REFERENCES `assures` (`numero_assurer`) ON DELETE CASCADE,
  CONSTRAINT `prestations_controleur_foreign` FOREIGN KEY (`controleur`) REFERENCES `users` (`email`) ON DELETE CASCADE,
  CONSTRAINT `prestations_pharmacie_foreign` FOREIGN KEY (`pharmacie`) REFERENCES `pharmacies` (`code_pharmacie`) ON DELETE CASCADE,
  CONSTRAINT `prestations_prescripteur_foreign` FOREIGN KEY (`prescripteur`) REFERENCES `prescripteurs` (`code_prescripteur`) ON DELETE CASCADE,
  CONSTRAINT `prestations_provenance_foreign` FOREIGN KEY (`provenance`) REFERENCES `hopitals` (`code_hopital`) ON DELETE CASCADE,
  CONSTRAINT `prestations_technicien_foreign` FOREIGN KEY (`technicien`) REFERENCES `techniciens` (`code_technicien`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table galdap.prestations : ~4 rows (environ)
/*!40000 ALTER TABLE `prestations` DISABLE KEYS */;
INSERT INTO `prestations` (`id`, `date`, `prescripteur`, `assure`, `nature_examen`, `renseignement`, `technicien`, `provenance`, `pharmacie`, `feuille`, `controleur`, `ap1`, `ap2`, `ap3`, `ap4`, `ap5`, `ap6`, `ap7`, `ap8`, `ap9`, `ap10`, `type1`, `type2`, `type3`, `type4`, `type5`, `type6`, `type7`, `type8`, `type9`, `type10`, `quantite1`, `quantite2`, `quantite3`, `quantite4`, `quantite5`, `quantite6`, `quantite7`, `quantite8`, `quantite9`, `quantite10`, `created_at`, `updated_at`) VALUES
	(1, '2023-10-01', '00002', '2981910100445', NULL, 'HTA', NULL, '11111', NULL, '12345', 'admin@canam.ml', 'PTMS428', 'PTM5759', 'PTMS428', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Boite', 'Flacon', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-10-01 20:20:01', '2023-10-01 20:20:01'),
	(4, '2023-10-01', '00002', '2981910100445', 'HTA', 'HTA', '222', '11111', '66666', '12345', 'admin@canam.ml', 'PTM5759', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Flacon', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-10-01 17:34:10', '2023-10-01 17:34:10'),
	(10, '2023-09-27', '00002', '1951330100004', NULL, 'HTA', NULL, '11111', NULL, '12345', 'sfoune@canam.ml', 'PTMS428', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Boite', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-09-27 18:47:45', '2023-09-27 18:47:45'),
	(12, '2023-10-01', '00002', '1951330100004', NULL, 'HTA', NULL, '11111', NULL, '2324', 'admin@canam.ml', 'PTMS428', 'PTM5759', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Boite', 'Flacon', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-10-01 20:21:33', '2023-10-01 20:21:33');
/*!40000 ALTER TABLE `prestations` ENABLE KEYS */;

-- Listage de la structure de la table galdap. profils
CREATE TABLE IF NOT EXISTS `profils` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom_profil` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profils_nom_profil_unique` (`nom_profil`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table galdap.profils : ~2 rows (environ)
/*!40000 ALTER TABLE `profils` DISABLE KEYS */;
INSERT INTO `profils` (`id`, `nom_profil`, `created_at`, `updated_at`) VALUES
	(1, 'Admin', NULL, NULL),
	(2, 'Contrôleur', '2023-09-26 22:47:15', '2023-09-26 22:47:15'),
	(3, 'Informatique', '2023-10-03 19:44:31', '2023-10-03 19:44:31');
/*!40000 ALTER TABLE `profils` ENABLE KEYS */;

-- Listage de la structure de la table galdap. techniciens
CREATE TABLE IF NOT EXISTS `techniciens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code_technicien` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom_technicien` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_technicien` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_technicien` (`code_technicien`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table galdap.techniciens : ~0 rows (environ)
/*!40000 ALTER TABLE `techniciens` DISABLE KEYS */;
INSERT INTO `techniciens` (`id`, `code_technicien`, `prenom_technicien`, `nom_technicien`, `created_at`, `updated_at`) VALUES
	(1, '222', 'Ousmane', 'TOURE', '2023-09-24 23:44:45', '2023-09-26 00:40:52');
/*!40000 ALTER TABLE `techniciens` ENABLE KEYS */;

-- Listage de la structure de la table galdap. users
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table galdap.users : ~18 rows (environ)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `profil`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Administrateur', 'admin@canam.ml', '2023-09-26 22:29:24', '$2y$10$rInQuIbJmJqdCEYKovI0pO5905FQJ3cWws2plejHtYnENGgyeqGgu', 'Admin', NULL, NULL, '2023-10-03 19:17:29'),
	(3, 'Dr Foune SACKO', 'sfoune@canam.ml', NULL, '$2y$10$Y/YjnXzvzXWmh8FvxYoITuO5fB6rrXIBJs7xOsuz2u2.VbUdsXzTS', 'Contrôleur', NULL, '2023-09-26 23:37:30', '2023-09-26 23:37:30'),
	(4, 'Dr MARIAME DITE YAMA BOUARE ', 'b.mariame@canam.ml', NULL, '$2y$10$TmDKE0YO8JsCGQmSAS9gL.gc7MKrFCR0diQb8m84bBgAQ1awURi9C', 'Contrôleur', NULL, '2023-09-27 08:52:29', '2023-09-27 08:52:29'),
	(5, 'Dr Mossere CAMARA', 'cmossere@canam.ml', NULL, '$2y$10$I86k/T.X3FXfNBflK7jWAe8y77Cl4pgeWTjXk8HaZBkwi1ZqyV3k.', 'Contrôleur', NULL, '2023-09-27 08:55:46', '2023-09-27 08:55:46'),
	(6, 'Dr Naye CAMARA BA', 'cnaye@canam.ml', NULL, '$2y$10$cW.Iwb6hzMAIQSmHZgQF9ux7c0hU3cqfcA1lqKAuwpFrP9jzC1RRy', 'Contrôleur', NULL, '2023-09-27 08:57:13', '2023-10-04 15:20:54'),
	(7, 'Dr AICHATOU LOUISE KONATE', 'kaichatou@canam.ml', NULL, '$2y$10$UhfJ8A7wDHkcXYZY34jQ.O6ylEgC6u.P4jMUMDErzN0HJ9WdRNDfG', 'Contrôleur', NULL, '2023-09-27 09:00:31', '2023-09-27 09:00:31'),
	(8, 'DR SANE N\'DIAYE', 'nsane@canam.ml', NULL, '$2y$10$fZaAb4vX6d95/4IaEj00weTz931kyxxYLOop.XRtOgL1Wpp4PDv42', 'Contrôleur', NULL, '2023-09-27 09:01:55', '2023-09-27 09:01:55'),
	(9, 'DR KOROTOUMOU KONE', 'kkorotoumou@canam.ml', NULL, '$2y$10$rYNggaxg1jxYZ9mBWmKaE.TBkdWcKQRHT5RIpysCXfGbYRAM/JUt2', 'Contrôleur', NULL, '2023-09-27 09:04:27', '2023-09-27 09:04:27'),
	(10, 'Dr El Hadj Belco TOURE', 'tbelco@canam.ml', NULL, '$2y$10$OGjFNXC1zM29lPAdQiY7GuWE2FwTZQGHQ2VbZmIfBAOGY.oKg0OtS', 'Contrôleur', NULL, '2023-09-27 09:05:46', '2023-09-27 09:05:46'),
	(11, 'Dr Ali CISSE', 'cali@canam.ml', NULL, '$2y$10$lruqyf5vIlDQDplNFucI6uNk2Xm1tdnvUpERGIcPWS./KIbKFV5we', 'Contrôleur', NULL, '2023-09-27 09:06:37', '2023-09-27 09:06:37'),
	(13, 'Dr KADIATOU DIARRA', 'dkadiatou@canam.ml', NULL, '$2y$10$vMM0GZvweJruvrxYSvXZDOZu3cYehoXkS7mbyiWb8SX8WpwTTu4sS', 'Contrôleur', NULL, '2023-09-27 09:11:02', '2023-09-27 09:11:02'),
	(14, 'Dr N\'DIAYE Kadiatou DIALLO', 'nkadiatou@canam.ml', NULL, '$2y$10$sxLa0BFZcbb03EU34SzBCeZudb5uEPS01f5I3BWg/xs46A3h29MvW', 'Contrôleur', NULL, '2023-09-27 09:12:57', '2023-09-27 09:12:57'),
	(15, 'Dr Youssouf Baba OUATTARA', 'oyoussouf@canam.ml', NULL, '$2y$10$mI4nHtSl0mQKv1uj8oI7GuoXGhsPyde4B24Xqt1xSnJbqUz6/0dG.', 'Contrôleur', NULL, '2023-09-27 09:13:49', '2023-09-27 09:13:49'),
	(16, 'Dr Mamadi DIAWARA', 'dmamadi@canam.ml', NULL, '$2y$10$/hf8yvOiwwKhnxx2bOqd/e4h2kS7Ir4XSNBbCv4jz4bTrVNZXjedW', 'Contrôleur', NULL, '2023-09-27 09:14:47', '2023-09-27 09:14:47'),
	(17, 'Mme COULIBALY Kadiatou SANGARE', 'skadiatou@canam.ml', NULL, '$2y$10$jh32IHIgu26lXyU1iAufP.XIZeiCDmAm9OHipfpinDUynskhsPD9K', 'Contrôleur', NULL, '2023-09-27 09:15:57', '2023-10-04 00:48:42'),
	(18, 'Dr Adama BERTHE', 'badama@canam.ml', NULL, '$2y$10$N5uTFGCjNdI06NRPLwqAS.aqrNGBqGvjlMWT/W1O6rJKlQdbWLw26', 'Contrôleur', NULL, '2023-09-27 09:16:39', '2023-10-04 01:16:52'),
	(19, 'Dr Allaye DIA', 'dallaye@canam.ml', NULL, '$2y$10$FPOVYyxIZm0two/P3yXVvuXvYQFcXO93U2JvayvjlyaAlkGJaoOAO', 'Contrôleur', NULL, '2023-09-27 09:17:28', '2023-10-04 01:19:04'),
	(20, 'Mahamane Amar Maiga', 'mmahamaneamar@canam.ml', NULL, '$2y$10$NVe2204OxqNe.ovY098l9OuVoP/6EV1A75WfaerltXx0vSWR/vG9y', 'Informatique', NULL, '2023-10-03 19:45:17', '2023-10-03 19:45:17'),
	(21, 'Assitan CISSE', 'cassitan@canam.ml', NULL, '$2y$10$Nnura9iqFZeGjddDhsnudeCqwXEvatwng0atkpjRHgXuszzrSymCi', 'Informatique', NULL, '2023-10-04 15:18:13', '2023-10-04 15:18:13'),
	(22, 'Mme DIARRA Mariam DIARRA', 'dmariam@canam.ml', NULL, '$2y$10$tAwQDvRZZQTXq6NzFdSpvuDi/tE6sTgLLaboAuxJN7yKJu1cMeTNa', 'Informatique', NULL, '2023-10-04 15:20:07', '2023-10-04 15:20:07');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
