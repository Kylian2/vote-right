-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : sam. 19 oct. 2024 à 11:13
-- Version du serveur : 10.3.39-MariaDB-1:10.3.39+maria~ubu2004
-- Version de PHP : 8.2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `voteright`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `COM_id_NB` bigint(20) NOT NULL,
  `COM_message_VC` varchar(250) DEFAULT NULL,
  `COM_proposition_NB` int(11) DEFAULT NULL,
  `COM_envoyeur_NB` int(11) DEFAULT NULL,
  `COM_moderateur_NB` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `detail_vote`
--

CREATE TABLE `detail_vote` (
  `DET_proposition_NB` int(11) NOT NULL,
  `DET_tour_NB` tinyint(4) NOT NULL,
  `DET_utilisateur_NB` int(11) NOT NULL,
  `DET_choix_VC` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `groupe`
--

CREATE TABLE `groupe` (
  `GRO_id_NB` int(11) NOT NULL,
  `GRO_nom_VC` varchar(150) DEFAULT NULL,
  `GRO_image_VC` varchar(50) DEFAULT NULL,
  `GRO_emoji_VC` varchar(5) DEFAULT NULL,
  `GRO_description_VC` text DEFAULT NULL,
  `GRO_budget_NB` float(12,2) DEFAULT NULL,
  `GRO_fraisfixes_NB` float(12,2) DEFAULT NULL,
  `GRO_createur_NB` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `groupe`
--

INSERT INTO `groupe` (`GRO_id_NB`, `GRO_nom_VC`, `GRO_image_VC`, `GRO_emoji_VC`, `GRO_description_VC`, `GRO_budget_NB`, `GRO_fraisfixes_NB`, `GRO_createur_NB`) VALUES
(1, 'Association des arts et traditions populaires', '100004.png', '1F389', NULL, 86000.00, 40420.00, 74),
(2, 'Mairie de Rennes – Programme des seniors actifs', '100003.png', '1F389', NULL, 32000.00, 11770.00, 4),
(3, 'Club de modélisme ferroviaire', '100004.png', '1F389', NULL, 105000.00, 42000.00, 23),
(4, 'Équipe de badminton de Montpellier', '100008.png', '1F389', NULL, 87000.00, 50460.00, 43),
(5, 'Club de tennis de table', '100007.png', '1F389', NULL, 44000.00, 29040.00, 63),
(6, 'Centre de loisirs du Chêne', '100008.png', '1F389', NULL, 112000.00, 64960.00, 31),
(7, 'Groupe de théâtre d’improvisation', '100004.png', '1F389', NULL, 69000.00, 15180.00, 23),
(8, 'Association des artisans d’art', '100009.png', '1F389', NULL, 36000.00, 3960.00, 6),
(9, 'Club Auto', '100009.png', '1F389', NULL, 108000.00, 62640.00, 44),
(10, 'Les amateurs de vin', '100009.png', '1F389', NULL, 60000.00, 13800.00, 89),
(11, 'Groupe de protection de l''environnement de Nice', '100003.png', '1F389', NULL, 27000.00, 17010.00, 47),
(12, 'Cercle des lecteurs passionnés', '100006.png', '1F389', NULL, 92000.00, 11040.00, 66),
(13, 'Marché de Noël de Strasbourg', '100005.png', '1F389', NULL, 111000.00, 53280.00, 97),
(14, 'Cercle des Nageurs', '100006.png', '1F389', NULL, 20000.00, 4200.00, 87),
(15, 'Comité des fêtes de la mairie de Lyon', '100009.png', '1F389', NULL, 101000.00, 56560.00, 58),
(16, 'Association des collectionneurs de timbres', '100006.png', '1F389', NULL, 10000.00, 2900.00, 73),
(17, 'Marathon d''Amsterdam', '100007.png', '1F389', NULL, 113000.00, 37290.00, 83),
(18, 'Mairie de Dourdan', '100009.png', '1F389', NULL, 108000.00, 42120.00, 70),
(19, 'École Jean Moulin', '100006.png', '1F389', NULL, 68000.00, 26520.00, 48),
(20, 'Comité des fêtes de la mairie de Lyon', '100008.png', '1F389', NULL, 111000.00, 68820.00, 21);

-- --------------------------------------------------------

--
-- Structure de la table `invitation`
--

CREATE TABLE `invitation` (
  `INV_id_NB` int(11) NOT NULL,
  `INV_code_VC` varchar(6) DEFAULT NULL,
  `INV_emission_DATE` date DEFAULT NULL,
  `INV_acceptation_DATE` date DEFAULT NULL,
  `INV_envoyeur_NB` int(11) DEFAULT NULL,
  `INV_destinataire_NB` int(11) DEFAULT NULL,
  `INV_groupe_NB` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `liste`
--

CREATE TABLE `liste` (
  `LIS_id_NB` int(11) NOT NULL,
  `LIS_possibilite_VC` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE `membre` (
  `MEM_utilisateur_NB` int(11) NOT NULL,
  `MEM_groupe_NB` int(11) NOT NULL,
  `MEM_role_NB` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `motif`
--

CREATE TABLE `motif` (
  `MOT_id_NB` tinyint(4) NOT NULL,
  `MOT_libelle_VC` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `proposition`
--

CREATE TABLE `proposition` (
  `PRO_id_NB` int(11) NOT NULL,
  `PRO_titre_VC` varchar(150) DEFAULT NULL,
  `PRO_description_TXT` text DEFAULT NULL,
  `PRO_creation_DATE` date DEFAULT NULL,
  `PRO_dureediscussion_NB` smallint(6) DEFAULT NULL,
  `PRO_nbdemande_NB` int(11) DEFAULT NULL,
  `PRO_localisation_VC` varchar(255) DEFAULT NULL,
  `PRO_budget_NB` float(12,2) DEFAULT NULL,
  `PRO_statut_VC` varchar(6) DEFAULT NULL,
  `PRO_initiateur_NB` int(11) DEFAULT NULL,
  `PRO_suppresseur_NB` int(11) DEFAULT NULL,
  `PRO_approuveur_NB` int(11) DEFAULT NULL,
  `PRO_groupe_NB` int(11) DEFAULT NULL,
  `PRO_theme_NB` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reaction_commentaire`
--

CREATE TABLE `reaction_commentaire` (
  `REC_commentaire_NB` bigint(20) NOT NULL,
  `REC_utilisateur_NB` int(11) NOT NULL,
  `REC_reaction_VC` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reaction_proposition`
--

CREATE TABLE `reaction_proposition` (
  `REP_utilisateur_NB` int(11) NOT NULL,
  `REP_proposition_NB` int(11) NOT NULL,
  `REP_reaction_VC` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `ROL_id_NB` tinyint(4) NOT NULL,
  `ROL_libelle_VC` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `signalement`
--

CREATE TABLE `signalement` (
  `SIG_utilisateur_NB` int(11) NOT NULL,
  `SIG_commentaire_NB` bigint(20) NOT NULL,
  `SIG_motif_NB` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `suffrage`
--

CREATE TABLE `suffrage` (
  `SUF_id_NB` tinyint(4) NOT NULL,
  `SUF_libelle_VC` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `theme`
--

CREATE TABLE `theme` (
  `THE_id_NB` smallint(6) NOT NULL,
  `THE_groupe_NB` int(11) NOT NULL,
  `THE_nom_VC` varchar(50) DEFAULT NULL,
  `THE_budget_NB` float(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `theme`
--

INSERT INTO `theme` (`THE_id_NB`, `THE_groupe_NB`, `THE_nom_VC`, `THE_budget_NB`) VALUES
(1, 1, 'Voyages', 30000.00),
(1, 2, 'Culture', 6000.00),
(1, 3, 'Sortie', 2000.00),
(1, 4, 'Matériel', 15000.00),
(1, 5, 'Matériel', 6000.00),
(1, 6, 'Sortie', 20000.00),
(1, 7, 'Sortie Culturelles', 7500.00),
(1, 8, 'Materiel', 20000.00),
(1, 9, 'Atelier Mecanique', 10000.00),
(1, 10, 'Visite', 4000.00),
(1, 11, 'Intervention', 3000.00),
(1, 12, 'Dédicace', 20000.00),
(1, 13, 'Organisation', 20000.00),
(1, 14, 'Compétition', 6000.00),
(1, 15, 'Organisation', 6000.00),
(1, 16, 'Exposition', 1000.00),
(1, 17, 'Organisation', 20000.00),
(1, 18, 'Aide Sociale', 12000.00),
(1, 19, 'Sortie', 4000.00),
(1, 20, 'Comité Entreprise', 20000.00),
(2, 1, 'Sorties', 5000.00),
(2, 2, 'Activité', 7000.00),
(2, 3, 'Activité Maquettage', 30000.00),
(2, 4, 'Compétition', 10000.00),
(2, 5, 'Compétition', 5000.00),
(2, 6, 'Activité Manuelle', 15000.00),
(2, 7, 'Deguisement', 4000.00),
(2, 8, 'Partenariats', 5000.00),
(2, 9, 'Rassemblement', 5000.00),
(2, 10, 'Dégustation', 20000.00),
(2, 11, 'Prévention', 5000.00),
(2, 12, 'Salon du Livre', 3000.00),
(2, 13, 'Bénévole', 12000.00),
(2, 14, 'Location', 7000.00),
(2, 15, 'Immobilier', 25000.00),
(2, 16, 'Rassemblement', 3000.00),
(2, 17, 'Publicité', 5000.00),
(2, 18, 'Animation', 10000.00),
(2, 19, 'Materiel', 20000.00),
(2, 20, 'Materiel', 10000.00),
(3, 1, 'Conférences', 3000.00),
(3, 2, 'Santé', 200.00),
(3, 4, 'Nutrition et Santé', 4000.00),
(3, 5, 'Sortie', 2000.00),
(3, 6, 'Fête', 4000.00),
(3, 9, 'Organisation', 25000.00),
(3, 10, 'Atelier', 6000.00),
(3, 12, 'Avantage', 45000.00),
(3, 13, 'Autorisation', 6000.00),
(3, 14, 'Santé', 2000.00),
(3, 15, 'Prestation', 10000.00),
(3, 17, 'Village exposant', 5000.00),
(3, 18, 'Entretien', 20000.00),
(3, 19, 'Animation', 6000.00),
(3, 20, 'Formation', 12000.00),
(4, 13, 'Musique', 5000.00),
(4, 17, 'Animation', 10000.00),
(4, 18, 'Projet', 22000.00),
(5, 13, 'Décoration', 12000.00),
(5, 17, 'Ravitaillement', 20000.00);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `UTI_id_NB` int(11) NOT NULL,
  `UTI_email_VC` varchar(150) DEFAULT NULL,
  `UTI_motdepasse_VC` varchar(255) DEFAULT NULL,
  `UTI_nom_VC` varchar(50) DEFAULT NULL,
  `UTI_prenom_VC` varchar(50) DEFAULT NULL,
  `UTI_adresse_VC` varchar(200) DEFAULT NULL,
  `UTI_codepostal_CH` char(6) DEFAULT NULL,
  `UTI_naissance_DATE` date DEFAULT NULL,
  `UTI_notiffrequence_CH` char(1) DEFAULT NULL,
  `UTI_notifproposition_NB` tinyint(1) DEFAULT NULL,
  `UTI_notifvote_NB` tinyint(1) DEFAULT NULL,
  `UTI_notifreaction_NB` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`UTI_id_NB`, `UTI_email_VC`, `UTI_nom_VC`, `UTI_prenom_VC`, `UTI_adresse_VC`, `UTI_codepostal_CH`, `UTI_naissance_DATE`, `UTI_notiffrequence_CH`, `UTI_notifproposition_NB`, `UTI_notifvote_NB`, `UTI_notifreaction_NB`) VALUES
(1, 'phamlett0@slate.com', 'Hamlett', 'Pacorro', '8677 Florence Center', '02360', '1958-12-27', 'H', 1, 1, 0),
(2, 'kelmes1@zimbio.com', 'Elmes', 'Kaspar', '6 Independence Avenue', '56400', '1978-04-10', 'H', 1, 0, 0),
(3, 'cramsted2@who.int', 'Ramsted', 'Cacilia', '647 6th Place', '65120', '1982-06-06', 'H', 0, 1, 0),
(4, 'jbarson3@istockphoto.com', 'Barson', 'Josias', '74377 Westport Junction', '31460', '1997-03-27', 'Q', 0, 1, 1),
(5, 'mnewlyn4@ocn.ne.jp', 'Newlyn', 'Mac', '58845 Stuart Road', '17137', '1976-05-16', 'H', 0, 1, 0),
(6, 'creina5@blogs.com', 'Reina', 'Colver', '44026 Bowman Place', '22110', '1980-02-15', 'H', 1, 1, 0),
(7, 'emanning6@ifeng.com', 'Manning', 'Emiline', '7 1st Street', '46340', '1975-03-25', 'H', 1, 1, 0),
(8, 'scatcheside7@technorati.com', 'Catcheside', 'Stephanus', '654 Village Way', '37520', '1953-09-06', 'Q', 1, 1, 1),
(9, 'dfrankcom8@ihg.com', 'Frankcom', 'Dolli', '06 Hintze Drive', '62180', '1951-07-11', 'H', 0, 1, 0),
(10, 'avousden9@reddit.com', 'Vousden', 'Ari', '7 Debra Pass', '11270', '1955-12-27', 'H', 0, 0, 0),
(11, 'fpattinsona@msu.edu', 'Pattinson', 'Frayda', '2034 Elka Hill', '88320', '1986-06-25', 'H', 0, 1, 1),
(12, 'laymesb@mayoclinic.com', 'Aymes', 'Linzy', '2 Petterle Park', '37290', '1999-11-29', 'Q', 1, 1, 0),
(13, 'cmartinovskyc@sogou.com', 'Martinovsky', 'Cristin', '7 Northland Pass', '69380', '1978-09-14', 'H', 1, 1, 1),
(14, 'ajozefiakd@soundcloud.com', 'Jozefiak', 'Allyn', '8958 Evergreen Point', '76340', '1981-11-05', 'H', 1, 1, 0),
(15, 'obransbye@mit.edu', 'Bransby', 'Oliy', '18 Rutledge Place', '33840', '1958-02-24', 'Q', 1, 1, 0),
(16, 'dwibberleyf@phpbb.com', 'Wibberley', 'Dierdre', '49958 Pearson Hill', '02140', '1950-11-28', 'H', 0, 1, 0),
(17, 'alambourng@prnewswire.com', 'Lambourn', 'Amata', '929 Esker Center', '50240', '1956-04-29', 'H', 0, 1, 1),
(18, 'alundbeckh@constantcontact.com', 'Lundbeck', 'Arabelle', '24 Judy Alley', '30770', '2004-05-27', 'H', 0, 1, 0),
(19, 'afawthorpei@goodreads.com', 'Fawthorpe', 'Agathe', '9 Namekagon Junction', '78930', '1999-10-06', 'Q', 1, 1, 0),
(20, 'hevisonj@cnbc.com', 'Evison', 'Helenelizabeth', '21751 Goodland Hill', '59670', '1994-12-02', 'H', 0, 0, 1),
(21, 'tknellk@netvibes.com', 'Knell', 'Timoteo', '73 Cardinal Avenue', '38330', '1994-06-18', 'Q', 0, 1, 0),
(22, 'egallandl@freewebs.com', 'Galland', 'Euell', '1 Daystar Plaza', '80700', '1964-04-28', 'Q', 1, 1, 1),
(23, 'semblemm@redcross.org', 'Emblem', 'Sonja', '4 5th Hill', '12330', '1970-09-01', 'H', 1, 1, 0),
(24, 'eendicottn@paypal.com', 'Endicott', 'Estrellita', '609 Melody Junction', '76560', '1968-01-03', 'H', 0, 0, 0),
(25, 'dpepperello@indiegogo.com', 'Pepperell', 'Devan', '4 Dahle Parkway', '64490', '1997-01-25', 'Q', 0, 1, 0),
(26, 'ccopelandp@intel.com', 'Copeland', 'Cord', '930 Petterle Lane', '22570', '1980-03-12', 'Q', 1, 1, 1),
(27, 'mrylandq@freewebs.com', 'Ryland', 'Maurice', '1 Packers Center', '24360', '1958-03-09', 'H', 1, 1, 0),
(28, 'bgaskillr@wsj.com', 'Gaskill', 'Brendin', '2 Basil Way', '30300', '2007-09-10', 'H', 1, 1, 0),
(29, 'ggoulbourns@youtu.be', 'Goulbourn', 'Grissel', '47 Oakridge Lane', '10210', '1981-06-21', 'Q', 0, 1, 0),
(30, 'atumiotot@blogtalkradio.com', 'Tumioto', 'Alvy', '9146 Elgar Street', '30500', '1968-04-14', 'H', 0, 1, 0),
(31, 'bburdessu@msu.edu', 'Burdess', 'Becki', '864 Di Loreto Center', '29410', '1978-11-13', 'Q', 0, 1, 0),
(32, 'pcharlsonv@aol.com', 'Charlson', 'Petunia', '246 Cardinal Plaza', '36400', '1997-10-06', 'H', 0, 1, 0),
(33, 'phattiffw@europa.eu', 'Hattiff', 'Pansie', '73 Ilene Road', '47360', '1970-09-12', 'H', 1, 1, 1),
(34, 'dbausorx@exblog.jp', 'Bausor', 'Daniella', '5346 Roxbury Place', '62380', '2007-07-02', 'H', 1, 1, 0),
(35, 'nmackrielly@csmonitor.com', 'Mackriell', 'Nappie', '47 Golf Course Drive', '62123', '1968-04-19', 'H', 1, 1, 0),
(36, 'dmattekz@epa.gov', 'Mattek', 'Darrell', '08 Autumn Leaf Junction', '33670', '1969-09-30', 'H', 1, 1, 0),
(37, 'kmackeague10@twitter.com', 'MacKeague', 'Kendal', '5101 Forster Road', '58300', '2003-09-26', 'H', 0, 1, 0),
(38, 'lklimshuk11@devhub.com', 'Klimshuk', 'Loise', '60836 Briar Crest Center', '02540', '1982-07-14', 'H', 0, 1, 0),
(39, 'dgoakes12@posterous.com', 'Goakes', 'Desirae', '09020 Crownhardt Way', '51500', '2004-03-31', 'H', 0, 1, 0),
(40, 'gbrusby13@redcross.org', 'Brusby', 'Gibb', '425 Killdeer Hill', '15210', '1998-05-13', 'Q', 1, 0, 0),
(41, 'awayland14@xing.com', 'Wayland', 'Allsun', '021 Harper Parkway', '26310', '1967-08-15', 'H', 1, 0, 0),
(42, 'ilamberton15@bizjournals.com', 'Lamberton', 'Isidora', '3579 Muir Hill', '15140', '2010-01-29', 'H', 0, 1, 0),
(43, 'nswettenham16@sitemeter.com', 'Swettenham', 'Nan', '6 Truax Way', '33550', '1986-12-30', 'H', 1, 0, 1),
(44, 'xsanchiz17@dot.gov', 'Sanchiz', 'Xaviera', '93 Nancy Point', '28120', '1956-01-23', 'H', 0, 1, 0),
(45, 'bbinton18@macromedia.com', 'Binton', 'Boone', '67373 Hayes Avenue', '77440', '1955-04-21', 'Q', 0, 1, 0),
(46, 'transom19@about.com', 'Ransom', 'Tim', '15859 Carpenter Road', '03150', '1964-10-02', 'H', 1, 1, 0),
(47, 'cdebernardis1a@naver.com', 'De Bernardis', 'Coletta', '579 Gerald Junction', '76110', '1957-02-07', 'H', 1, 0, 0),
(48, 'cpett1b@forbes.com', 'Pett', 'Cathrine', '369 Quincy Center', '65240', '1987-07-01', 'H', 1, 1, 0),
(49, 'lbarrabeale1c@illinois.edu', 'Barrabeale', 'Lelah', '364 John Wall Alley', '39320', '1982-06-10', 'Q', 1, 0, 0),
(50, 'aantcliff1d@woothemes.com', 'Antcliff', 'Aube', '92 Corben Alley', '62140', '2001-06-11', 'H', 1, 1, 0),
(51, 'balesio1e@addthis.com', 'Alesio', 'Billi', '8 Service Point', '29400', '1989-02-10', 'H', 0, 1, 0),
(52, 'clammenga1f@eepurl.com', 'Lammenga', 'Cornall', '24228 Riverside Trail', '43160', '1955-04-21', 'Q', 1, 0, 0),
(53, 'bpouton1g@spiegel.de', 'Pouton', 'Billie', '3 Goodland Parkway', '85410', '1967-04-22', 'Q', 1, 1, 0),
(54, 'fcasey1h@apache.org', 'Casey', 'Felicia', '36 Ruskin Pass', '38350', '1967-11-23', 'H', 1, 1, 0),
(55, 'rikins1i@barnesandnoble.com', 'Ikins', 'Rossy', '95223 Victoria Terrace', '24610', '1984-06-20', 'H', 1, 1, 0),
(56, 'aquarles1j@infoseek.co.jp', 'Quarles', 'Alexander', '37 Schiller Road', '50690', '1982-05-19', 'H', 1, 1, 0),
(57, 'vverma1k@nature.com', 'Verma', 'Vyky', '31347 Lotheville Center', '85450', '1958-09-17', 'H', 1, 0, 0),
(58, 'fcore1l@reference.com', 'Core', 'Fayth', '5 Northport Drive', '28270', '1954-03-12', 'Q', 0, 1, 0),
(59, 'kdulin1m@a8.net', 'Dulin', 'Kerianne', '644 Artisan Lane', '35340', '2002-05-23', 'H', 1, 1, 0),
(60, 'jbendan1n@addthis.com', 'Bendan', 'Jemie', '8 Dawn Point', '07200', '1984-11-03', 'Q', 0, 1, 0),
(61, 'dblackwell1o@ovh.net', 'Blackwell', 'Donaugh', '57 5th Court', '27730', '2006-12-07', 'Q', 1, 1, 0),
(62, 'jmobley1p@amazon.com', 'Mobley', 'Janek', '1694 5th Street', '34560', '2008-11-23', 'H', 1, 1, 0),
(63, 'jmcgrory1q@tmall.com', 'McGrory', 'Johnna', '1107 Buell Terrace', '35190', '1970-09-01', 'Q', 0, 1, 0),
(64, 'mgoodwin1r@wikimedia.org', 'Goodwin', 'Maren', '98 Burrows Crossing', '21320', '1988-07-06', 'Q', 0, 1, 1),
(65, 'deckery1s@marriott.com', 'Eckery', 'Donica', '74822 Fieldstone Park', '73590', '1979-01-01', 'H', 0, 1, 0),
(66, 'acorden1t@vkontakte.ru', 'Corden', 'Amelita', '8 Garrison Way', '51230', '2007-03-15', 'H', 1, 1, 1),
(67, 'xleggott1u@free.fr', 'Leggott', 'Xerxes', '9 Buena Vista Avenue', '51300', '1989-07-29', 'H', 0, 1, 1),
(68, 'cpetit1v@slideshare.net', 'Petit', 'Clarine', '65 Oakridge Court', '72540', '1997-12-28', 'H', 0, 0, 1),
(69, 'dpullman1w@digg.com', 'Pullman', 'Dewie', '5 Pleasure Place', '68210', '2000-01-18', 'H', 1, 1, 0),
(70, 'emckernon1x@businessweek.com', 'McKernon', 'Esteban', '790 Laurel Alley', '44770', '1956-08-27', 'Q', 1, 1, 0),
(71, 'iwinslett1y@symantec.com', 'Winslett', 'Ilise', '19393 Marquette Avenue', '08220', '2002-04-22', 'H', 0, 1, 0),
(72, 'dgors1z@eventbrite.com', 'Gors', 'Daryl', '0 Granby Alley', '06370', '1996-08-01', 'H', 1, 1, 0),
(73, 'htheodoris20@nytimes.com', 'Theodoris', 'Hannie', '370 Village Green Terrace', '88320', '1962-07-21', 'H', 1, 1, 0),
(74, 'sraimbauld21@cdbaby.com', 'Raimbauld', 'Sybilla', '887 Arkansas Plaza', '71350', '1980-01-17', 'H', 0, 1, 0),
(75, 'kwapples22@reverbnation.com', 'Wapples', 'Kakalina', '579 Larry Place', '81120', '1984-07-15', 'H', 0, 1, 1),
(76, 'lglancy23@1688.com', 'Glancy', 'Lorna', '092 Carey Street', '24320', '2009-05-03', 'H', 0, 1, 0),
(77, 'jrowthorne24@biglobe.ne.jp', 'Rowthorne', 'Justin', '0 Burning Wood Road', '21310', '2002-07-08', 'Q', 0, 1, 0),
(78, 'mcampagne25@jiathis.com', 'Campagne', 'Myrtie', '358 Mccormick Road', '26330', '1967-05-25', 'H', 0, 0, 0),
(79, 'grikel26@hc360.com', 'Rikel', 'Gordan', '7632 Dennis Street', '97230', '1987-10-23', 'H', 1, 1, 1),
(80, 'lbriton27@yellowbook.com', 'Briton', 'La verne', '03 Bunker Hill Point', '24300', '1972-01-28', 'Q', 1, 1, 0),
(81, 'pjachimiak28@squidoo.com', 'Jachimiak', 'Pall', '09908 Jenifer Parkway', '54170', '1960-06-20', 'H', 1, 0, 1),
(82, 'nducarne29@meetup.com', 'Ducarne', 'Noah', '957 Lyons Avenue', '14470', '1964-11-23', 'H', 0, 1, 0),
(83, 'rshingles2a@sphinn.com', 'Shingles', 'Rowe', '05 Reinke Pass', '10350', '2002-09-12', 'H', 1, 1, 0),
(84, 'jpretti2b@bravesites.com', 'Pretti', 'Julita', '2777 Londonderry Alley', '61350', '1994-09-26', 'H', 1, 0, 0),
(85, 'cropkins2c@hostgator.com', 'Ropkins', 'Christen', '990 Granby Way', '61110', '1996-03-17', 'H', 0, 1, 0),
(86, 'lcompston2d@theguardian.com', 'Compston', 'Leda', '683 Kinsman Circle', '24120', '1955-06-03', 'H', 0, 1, 0),
(87, 'mmouth2e@google.cn', 'Mouth', 'Mattias', '50 Acker Avenue', '24240', '1998-05-30', 'Q', 0, 0, 1),
(88, 'cternott2f@ucoz.com', 'Ternott', 'Clarabelle', '00814 Brentwood Way', '71580', '1994-05-13', 'Q', 0, 1, 0),
(89, 'atrevance2g@reference.com', 'Trevance', 'Ariel', '33 Golf Course Trail', '88390', '1973-02-11', 'H', 0, 1, 0),
(90, 'mhayhoe2h@time.com', 'Hayhoe', 'Madonna', '09 Grim Alley', '29400', '1996-02-14', 'H', 1, 1, 1),
(91, 'dadran2i@cpanel.net', 'Adran', 'Deborah', '077 Swallow Lane', '33850', '1983-01-18', 'H', 1, 1, 0),
(92, 'lshower2j@springer.com', 'Shower', 'Lonni', '909 Shasta Hill', '02880', '1974-06-04', 'H', 1, 1, 0),
(93, 'ckirgan2k@umn.edu', 'Kirgan', 'Cassey', '35 American Ash Terrace', '11360', '1992-09-03', 'H', 0, 1, 0),
(94, 'dbeddoe2l@storify.com', 'Beddoe', 'Donn', '993 Butterfield Drive', '50570', '1962-02-25', 'H', 1, 0, 0),
(95, 'kniese2m@w3.org', 'Niese', 'Kirbee', '660 Warbler Court', '32140', '2000-07-25', 'H', 1, 1, 0),
(96, 'kmariet2n@prnewswire.com', 'Mariet', 'Kristoforo', '42 Fair Oaks Point', '56320', '1996-04-20', 'Q', 0, 1, 1),
(97, 'hclemitt2o@meetup.com', 'Clemitt', 'Hope', '77 Jana Court', '20290', '1963-01-19', 'H', 0, 1, 0),
(98, 'amahomet2p@ycombinator.com', 'Mahomet', 'Ashlin', '5569 Green Ridge Hill', '39110', '1963-11-14', 'H', 1, 1, 0),
(99, 'ameader2q@technorati.com', 'Meader', 'Alfy', '42 Green Ridge Lane', '98884', '1960-07-16', 'Q', 1, 1, 0),
(100, 'cyukhin2r@freewebs.com', 'Yukhin', 'Clare', '889 Blaine Way', '36600', '1951-08-11', 'Q', 1, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `vote`
--

CREATE TABLE `vote` (
  `VOT_proposition_NB` int(11) NOT NULL,
  `VOT_tour_NB` tinyint(4) NOT NULL,
  `VOT_valide_BOOL` tinyint(1) DEFAULT NULL,
  `VOT_assesseur_NB` int(11) DEFAULT NULL,
  `VOT_liste_NB` int(11) DEFAULT NULL,
  `VOT_type_NB` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`COM_id_NB`),
  ADD KEY `FK_COM_proposition` (`COM_proposition_NB`),
  ADD KEY `FK_COM_envoyeur` (`COM_envoyeur_NB`),
  ADD KEY `FK_COM_moderateur` (`COM_moderateur_NB`);

--
-- Index pour la table `detail_vote`
--
ALTER TABLE `detail_vote`
  ADD PRIMARY KEY (`DET_proposition_NB`,`DET_tour_NB`,`DET_utilisateur_NB`),
  ADD KEY `FK_DET_utilisateur` (`DET_utilisateur_NB`);

--
-- Index pour la table `groupe`
--
ALTER TABLE `groupe`
  ADD PRIMARY KEY (`GRO_id_NB`),
  ADD KEY `FK_GRO_createur` (`GRO_createur_NB`);

--
-- Index pour la table `invitation`
--
ALTER TABLE `invitation`
  ADD PRIMARY KEY (`INV_id_NB`),
  ADD KEY `FK_INV_envoyeur` (`INV_envoyeur_NB`),
  ADD KEY `FK_INV_destinataire` (`INV_destinataire_NB`),
  ADD KEY `FK_INV_groupe` (`INV_groupe_NB`);

--
-- Index pour la table `liste`
--
ALTER TABLE `liste`
  ADD PRIMARY KEY (`LIS_id_NB`);

--
-- Index pour la table `membre`
--
ALTER TABLE `membre`
  ADD PRIMARY KEY (`MEM_utilisateur_NB`,`MEM_groupe_NB`),
  ADD KEY `FK_MEM_groupe` (`MEM_groupe_NB`),
  ADD KEY `FK_MEM_role` (`MEM_role_NB`);

--
-- Index pour la table `motif`
--
ALTER TABLE `motif`
  ADD PRIMARY KEY (`MOT_id_NB`);

--
-- Index pour la table `proposition`
--
ALTER TABLE `proposition`
  ADD PRIMARY KEY (`PRO_id_NB`),
  ADD KEY `FK_PRO_initiateur` (`PRO_initiateur_NB`),
  ADD KEY `FK_PRO_suppresseur` (`PRO_suppresseur_NB`),
  ADD KEY `FK_PRO_approuveur` (`PRO_approuveur_NB`),
  ADD KEY `FK_PRO_groupe` (`PRO_groupe_NB`),
  ADD KEY `FK_PRO_theme` (`PRO_theme_NB`);

--
-- Index pour la table `reaction_commentaire`
--
ALTER TABLE `reaction_commentaire`
  ADD PRIMARY KEY (`REC_commentaire_NB`,`REC_utilisateur_NB`),
  ADD KEY `FK_REC_utilisateur` (`REC_utilisateur_NB`);

--
-- Index pour la table `reaction_proposition`
--
ALTER TABLE `reaction_proposition`
  ADD PRIMARY KEY (`REP_utilisateur_NB`,`REP_proposition_NB`),
  ADD KEY `FK_REP_proposition` (`REP_proposition_NB`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`ROL_id_NB`);

--
-- Index pour la table `signalement`
--
ALTER TABLE `signalement`
  ADD PRIMARY KEY (`SIG_utilisateur_NB`,`SIG_commentaire_NB`,`SIG_motif_NB`),
  ADD KEY `FK_SIG_commentaire` (`SIG_commentaire_NB`),
  ADD KEY `FK_SIG_motif` (`SIG_motif_NB`);

--
-- Index pour la table `suffrage`
--
ALTER TABLE `suffrage`
  ADD PRIMARY KEY (`SUF_id_NB`);

--
-- Index pour la table `theme`
--
ALTER TABLE `theme`
  ADD PRIMARY KEY (`THE_id_NB`,`THE_groupe_NB`),
  ADD KEY `FK_THE_groupe` (`THE_groupe_NB`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`UTI_id_NB`);

--
-- Index pour la table `vote`
--
ALTER TABLE `vote`
  ADD PRIMARY KEY (`VOT_proposition_NB`,`VOT_tour_NB`),
  ADD KEY `FK_VOT_assesseur` (`VOT_assesseur_NB`),
  ADD KEY `FK_VOT_liste` (`VOT_liste_NB`),
  ADD KEY `FK_VOT_type` (`VOT_type_NB`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `COM_id_NB` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `groupe`
--
ALTER TABLE `groupe`
  MODIFY `GRO_id_NB` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `invitation`
--
ALTER TABLE `invitation`
  MODIFY `INV_id_NB` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `liste`
--
ALTER TABLE `liste`
  MODIFY `LIS_id_NB` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `motif`
--
ALTER TABLE `motif`
  MODIFY `MOT_id_NB` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `proposition`
--
ALTER TABLE `proposition`
  MODIFY `PRO_id_NB` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `ROL_id_NB` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `suffrage`
--
ALTER TABLE `suffrage`
  MODIFY `SUF_id_NB` tinyint(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `UTI_id_NB` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `FK_COM_envoyeur` FOREIGN KEY (`COM_envoyeur_NB`) REFERENCES `utilisateur` (`UTI_id_NB`),
  ADD CONSTRAINT `FK_COM_moderateur` FOREIGN KEY (`COM_moderateur_NB`) REFERENCES `utilisateur` (`UTI_id_NB`),
  ADD CONSTRAINT `FK_COM_proposition` FOREIGN KEY (`COM_proposition_NB`) REFERENCES `proposition` (`PRO_id_NB`);

--
-- Contraintes pour la table `detail_vote`
--
ALTER TABLE `detail_vote`
  ADD CONSTRAINT `FK_DET_utilisateur` FOREIGN KEY (`DET_utilisateur_NB`) REFERENCES `utilisateur` (`UTI_id_NB`),
  ADD CONSTRAINT `FK_DET_vote` FOREIGN KEY (`DET_proposition_NB`,`DET_tour_NB`) REFERENCES `vote` (`VOT_proposition_NB`, `VOT_tour_NB`);

--
-- Contraintes pour la table `groupe`
--
ALTER TABLE `groupe`
  ADD CONSTRAINT `FK_GRO_createur` FOREIGN KEY (`GRO_createur_NB`) REFERENCES `utilisateur` (`UTI_id_NB`);

--
-- Contraintes pour la table `invitation`
--
ALTER TABLE `invitation`
  ADD CONSTRAINT `FK_INV_destinataire` FOREIGN KEY (`INV_destinataire_NB`) REFERENCES `utilisateur` (`UTI_id_NB`),
  ADD CONSTRAINT `FK_INV_envoyeur` FOREIGN KEY (`INV_envoyeur_NB`) REFERENCES `utilisateur` (`UTI_id_NB`),
  ADD CONSTRAINT `FK_INV_groupe` FOREIGN KEY (`INV_groupe_NB`) REFERENCES `groupe` (`GRO_id_NB`);

--
-- Contraintes pour la table `membre`
--
ALTER TABLE `membre`
  ADD CONSTRAINT `FK_MEM_groupe` FOREIGN KEY (`MEM_groupe_NB`) REFERENCES `groupe` (`GRO_id_NB`),
  ADD CONSTRAINT `FK_MEM_role` FOREIGN KEY (`MEM_role_NB`) REFERENCES `role` (`ROL_id_NB`),
  ADD CONSTRAINT `FK_MEM_utilisateur` FOREIGN KEY (`MEM_utilisateur_NB`) REFERENCES `utilisateur` (`UTI_id_NB`);

--
-- Contraintes pour la table `proposition`
--
ALTER TABLE `proposition`
  ADD CONSTRAINT `FK_PRO_approuveur` FOREIGN KEY (`PRO_approuveur_NB`) REFERENCES `utilisateur` (`UTI_id_NB`),
  ADD CONSTRAINT `FK_PRO_groupe` FOREIGN KEY (`PRO_groupe_NB`) REFERENCES `groupe` (`GRO_id_NB`),
  ADD CONSTRAINT `FK_PRO_initiateur` FOREIGN KEY (`PRO_initiateur_NB`) REFERENCES `utilisateur` (`UTI_id_NB`),
  ADD CONSTRAINT `FK_PRO_suppresseur` FOREIGN KEY (`PRO_suppresseur_NB`) REFERENCES `utilisateur` (`UTI_id_NB`),
  ADD CONSTRAINT `FK_PRO_theme` FOREIGN KEY (`PRO_theme_NB`) REFERENCES `theme` (`THE_id_NB`);

--
-- Contraintes pour la table `reaction_commentaire`
--
ALTER TABLE `reaction_commentaire`
  ADD CONSTRAINT `FK_REC_commentaire` FOREIGN KEY (`REC_commentaire_NB`) REFERENCES `commentaire` (`COM_id_NB`),
  ADD CONSTRAINT `FK_REC_utilisateur` FOREIGN KEY (`REC_utilisateur_NB`) REFERENCES `utilisateur` (`UTI_id_NB`);

--
-- Contraintes pour la table `reaction_proposition`
--
ALTER TABLE `reaction_proposition`
  ADD CONSTRAINT `FK_REP_proposition` FOREIGN KEY (`REP_proposition_NB`) REFERENCES `proposition` (`PRO_id_NB`),
  ADD CONSTRAINT `FK_REP_utilisateur` FOREIGN KEY (`REP_utilisateur_NB`) REFERENCES `utilisateur` (`UTI_id_NB`);

--
-- Contraintes pour la table `signalement`
--
ALTER TABLE `signalement`
  ADD CONSTRAINT `FK_SIG_commentaire` FOREIGN KEY (`SIG_commentaire_NB`) REFERENCES `commentaire` (`COM_id_NB`),
  ADD CONSTRAINT `FK_SIG_motif` FOREIGN KEY (`SIG_motif_NB`) REFERENCES `motif` (`MOT_id_NB`),
  ADD CONSTRAINT `FK_SIG_utilisateur` FOREIGN KEY (`SIG_utilisateur_NB`) REFERENCES `utilisateur` (`UTI_id_NB`);

--
-- Contraintes pour la table `theme`
--
ALTER TABLE `theme`
  ADD CONSTRAINT `FK_THE_groupe` FOREIGN KEY (`THE_groupe_NB`) REFERENCES `groupe` (`GRO_id_NB`);

--
-- Contraintes pour la table `vote`
--
ALTER TABLE `vote`
  ADD CONSTRAINT `FK_VOT_assesseur` FOREIGN KEY (`VOT_assesseur_NB`) REFERENCES `utilisateur` (`UTI_id_NB`),
  ADD CONSTRAINT `FK_VOT_liste` FOREIGN KEY (`VOT_liste_NB`) REFERENCES `liste` (`LIS_id_NB`),
  ADD CONSTRAINT `FK_VOT_proposition` FOREIGN KEY (`VOT_proposition_NB`) REFERENCES `proposition` (`PRO_id_NB`),
  ADD CONSTRAINT `FK_VOT_type` FOREIGN KEY (`VOT_type_NB`) REFERENCES `suffrage` (`SUF_id_NB`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
