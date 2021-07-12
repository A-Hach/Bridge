-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 21 mai 2020 à 05:19
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP : 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bridge`
--
CREATE DATABASE IF NOT EXISTS `bridge` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bridge`;

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `aroundWorldFriends` (IN `person` INT)  BEGIN
	SELECT c.name AS country, COUNT(*) AS members_count from profiles p INNER JOIN users u ON p.owner_id = u.id INNER JOIN countries c ON u.country_id = c.id where p.id in((SELECT receiver_id FROM invitations WHERE sender_id = person AND state = 1) union (SELECT sender_id FROM invitations WHERE receiver_id = person AND state = 1)) GROUP BY c.name;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getMonthlyData` (IN `days` INT)  BEGIN
	DECLARE counter INT;
    DECLARE oldDate DATE;
    SET counter = 0;
    SET oldDate = ADDDATE(ADDDATE(CURRENT_DATE(), INTERVAL 1 DAY), INTERVAL -days DAY);
    
    -- Create temporary table
    CREATE TEMPORARY TABLE specData(date DATE, members INT);
    
    data_loop: LOOP
        IF counter = days THEN
        	LEAVE data_loop;
        END IF;
        
        INSERT INTO specData VALUES(oldDate, (SELECT COUNT(*) FROM users WHERE DATE(registration_date) = oldDate));
        SET counter = counter + 1;
        SET oldDate = ADDDATE(oldDate, INTERVAL 1 DAY);
  	END LOOP data_loop;
    SELECT * FROM specData;
    DROP table specData;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getNotifications` (IN `userID` INT)  BEGIN
	(SELECT n.post_id, u.username, nt.submessage, nt.icon, n.notification_date, pr.main_picture FROM notifications n INNER JOIN notifications_types nt ON n.type_id = nt.id INNER JOIN posts p ON p.id = n.post_id INNER JOIN profiles pr ON n.participant_id = pr.id INNER JOIN users u ON pr.owner_id = u.id WHERE nt.id NOT IN (1, 5) AND p.author_id IN (SELECT CASE WHEN sender_id <> userID THEN sender_id ELSE receiver_id END AS id FROM invitations WHERE (sender_id = userID OR receiver_id = userID) AND state = 1 AND n.notification_date > (SELECT friendship_date FROM invitations WHERE (sender_id = userID AND receiver_id = n.participant_id) OR (sender_id = n.participant_id AND receiver_id = userID) AND state = 1))
UNION ALL

SELECT n.post_id, u.username, nt.submessage, nt.icon, n.notification_date, pr.main_picture FROM notifications n INNER JOIN notifications_types nt ON n.type_id = nt.id INNER JOIN posts p ON p.id = n.post_id INNER JOIN profiles pr ON n.participant_id = pr.id INNER JOIN users u ON pr.owner_id = u.id WHERE n.participant_id <> userID AND nt.id IN (1, 5) AND p.author_id = userID AND n.notification_date > (SELECT friendship_date FROM invitations WHERE (sender_id = userID AND receiver_id = n.participant_id) OR (sender_id = n.participant_id AND receiver_id = userID) AND state = 1)) ORDER BY notification_date DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sevenDaysData` (IN `days` INT, IN `profile` INT)  BEGIN
	DECLARE counter INT;
    DECLARE oldDate DATE;
    SET counter = 0;
    SET oldDate = ADDDATE(ADDDATE(CURRENT_DATE(), INTERVAL 1 DAY), INTERVAL -days DAY);
    
    -- Create temporary table
    CREATE TEMPORARY TABLE specData(date DATE, members INT);
    
    data_loop: LOOP
        IF counter = days THEN
        	LEAVE data_loop;
        END IF;
        
        INSERT INTO specData VALUES(oldDate, (SELECT COUNT(*) FROM reactions WHERE DATE(reaction_date) = oldDate AND post_id IN (SELECT id FROM posts WHERE author_id = profile)));
        SET counter = counter + 1;
        SET oldDate = ADDDATE(oldDate, INTERVAL 1 DAY);
  	END LOOP data_loop;
    SELECT * FROM specData;
    DROP table specData;
END$$

--
-- Fonctions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `friendship_state` (`me` INT, `person` INT) RETURNS INT(11) BEGIN
	-- My profile
    IF me = person THEN
    	RETURN -2;
    END IF;
    
    -- Does not exists
    IF NOT EXISTS (SELECT * FROM invitations WHERE (sender_id = me AND receiver_id = person) OR (sender_id = person AND receiver_id = me)) THEN
    	RETURN -1;
    END IF;
    
    -- Exists and he is my friend (state = 1)
    IF EXISTS (SELECT * FROM invitations WHERE state = 1 AND ((sender_id = me AND receiver_id = person) OR (sender_id = person AND receiver_id = me))) THEN
    	RETURN 0;
    END IF;
    
    -- Exists and he is not my friend (state = 0) AND I AM THE SENDER
    IF EXISTS (SELECT * FROM invitations WHERE state = 0 AND sender_id = me AND receiver_id = person) THEN
   		RETURN 1;
    END IF;
    
    -- Exists and he is not my friend (state = 0) AND I AM THE RECEIEVER
    IF EXISTS (SELECT * FROM invitations WHERE state = 0 AND receiver_id = me AND sender_id = person) THEN
   		RETURN 2;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) NOT NULL,
  `text` text DEFAULT NULL,
  `comment_date` datetime NOT NULL DEFAULT current_timestamp(),
  `commentator_id` bigint(20) DEFAULT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `media` varchar(255) DEFAULT NULL,
  `media_type` varchar(255) DEFAULT 'text'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `text`, `comment_date`, `commentator_id`, `post_id`, `media`, `media_type`) VALUES
(1, 'OMG!!! Le chef de Bridge a partagé ma photo! Merci beaucoup', '2020-05-20 18:39:20', 8, 5, NULL, 'text'),
(2, '@buffon  parce que vous êtes un grand gardien de but :)', '2020-05-20 18:40:35', 1, 5, NULL, 'text');

--
-- Déclencheurs `comments`
--
DELIMITER $$
CREATE TRIGGER `after_comments_added` AFTER INSERT ON `comments` FOR EACH ROW BEGIN
    UPDATE posts SET total_comments = total_comments + 1 WHERE id = NEW.post_id;
   
    INSERT INTO notifications(id, notification_date, participant_id, type_id, post_id) VALUES (NULL, NEW.comment_date, NEW.commentator_id, 1, NEW.post_id);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_comments_deleted` AFTER DELETE ON `comments` FOR EACH ROW BEGIN
    UPDATE posts SET total_comments = total_comments - 1 WHERE id = OLD.post_id;
    
    DELETE FROM notifications WHERE notification_date = OLD.comment_date AND participant_id = OLD.commentator_id AND post_id = OLD.post_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `flag` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `countries`
--

INSERT INTO `countries` (`id`, `name`, `flag`) VALUES
(1, 'Maurice', 'mauritius.svg'),
(2, 'Autriche', 'austria.svg'),
(3, 'Oman', 'oman.svg'),
(4, 'Éthiopie', 'ethiopia.svg'),
(5, 'Tanzanie', 'tanzania.svg'),
(6, 'Nicaragua', 'nicaragua.svg'),
(7, 'Estonie', 'estonia.svg'),
(8, 'Ouganda', 'uganda.svg'),
(9, 'Slovénie', 'slovenia.svg'),
(10, 'Zimbabwe', 'zimbabwe.svg'),
(11, 'Sao Tomé-et-Principe', 'sao-tome-and-prince.svg'),
(12, 'Italie', 'italy.svg'),
(13, 'El Salvador', 'el-salvador.svg'),
(14, 'Népal', 'nepal.svg'),
(15, 'Liban', 'lebanon.svg'),
(16, 'Irak', 'iraq.svg'),
(17, 'Syrie', 'syria.svg'),
(18, 'Honduras', 'honduras.svg'),
(19, 'Qatar', 'qatar.svg'),
(20, 'Comores', 'comoros.svg'),
(21, 'Corée du Nord', 'north-korea.svg'),
(22, 'Zambie', 'zambia.svg'),
(23, 'Chine', 'china.svg'),
(24, 'Luxembourg', 'luxembourg.svg'),
(25, 'Jamaïque', 'jamaica.svg'),
(26, 'Cap-Vert', 'cape-verde.svg'),
(27, 'Monaco', 'monaco.svg'),
(28, 'Bhoutan', 'bhutan.svg'),
(29, 'Paraguay', 'paraguay.svg'),
(30, 'Andorre', 'andorra.svg'),
(31, 'Micronésie', 'micronesia.svg'),
(32, 'Tunisie', 'tunisia.svg'),
(33, 'Mauritanie', 'mauritania.svg'),
(34, 'kosovo', 'kosovo.svg'),
(35, 'Ghana', 'ghana.svg'),
(36, 'Burundi', 'burundi.svg'),
(37, 'Myanmar', 'myanmar.svg'),
(38, 'Gabon', 'gabon.svg'),
(39, 'Bénin', 'benin.svg'),
(40, 'Namibie', 'namibia.svg'),
(41, 'Japon', 'japan.svg'),
(42, 'Lituanie', 'lithuania.svg'),
(43, 'Érythrée', 'eritrea.svg'),
(44, 'Kenya', 'kenya.svg'),
(45, 'Djibouti', 'djibouti.svg'),
(46, 'Tuvalu', 'tuvalu.svg'),
(47, 'Serbie', 'serbia.svg'),
(48, 'Togo', 'togo.svg'),
(49, 'Kazakhstan', 'kazakhstan.svg'),
(50, 'Suriname', 'suriname.svg'),
(51, 'Jordanie', 'jordan.svg'),
(52, 'Belize', 'belize.svg'),
(53, 'Iselande', 'icland.svg'),
(54, 'Somalie', 'somalia.svg'),
(55, 'Barbade', 'barbados.svg'),
(56, 'Nigeria', 'nigeria.svg'),
(57, 'Pologne', 'poland'),
(58, 'Uruguay', 'uruguay.svg'),
(59, 'Sint Maarten', 'sint-maarten.svg'),
(60, 'Burkina Faso', 'burkina-faso.svg'),
(61, 'Slovaquie', 'slovakia.svg'),
(62, 'Sierra Leone', 'sierra-leone.svg'),
(63, 'Corée du Sud', 'south-korea.svg'),
(64, 'Mozambique', 'mozambique.svg'),
(65, 'Saint-Marin', 'san-marino.svg'),
(66, 'Albanie', 'albania.svg'),
(67, 'Pakistan', 'pakistan.svg'),
(68, 'Équateur', 'ecuador.svg'),
(69, 'Cameroun', 'cameroon.svg'),
(70, 'Panama', 'panama.svg'),
(71, 'Koweït', 'koweit.svg'),
(72, 'Roumanie', 'romania.svg'),
(73, 'Hongrie', 'hungary.svg'),
(74, 'Angola', 'angola.svg'),
(75, 'Nouvelle-Zélande', 'new-zealand.svg'),
(76, 'Espagne', 'spain.svg'),
(77, 'Chili', 'chile.svg'),
(78, 'Arabie Saoudite', 'saudi-arabia.svg'),
(79, 'Iran', 'iran.svg'),
(80, 'Bahreïn', 'bahrain.svg'),
(81, 'Norvège', 'norway.svg'),
(82, 'Algérie', 'algeria.svg'),
(83, 'Ukraine', 'ukraine.svg'),
(84, 'Belgique', 'belgium.svg'),
(85, 'Croatie', 'croatia.svg'),
(86, 'Angleterre', 'england.svg'),
(87, 'Égypte', 'egypt.svg'),
(88, 'Allemagne', 'germany.svg'),
(89, 'Maroc', 'morocco.svg'),
(90, 'Royaume-Uni', 'united-kingdom.svg'),
(91, 'Brésil', 'brazil.svg'),
(92, 'Mexique', 'mexico.svg'),
(93, 'Russie', 'russia.svg'),
(94, 'Canada', 'canada.svg'),
(95, 'Portugal', 'portugal.svg'),
(96, 'Argentine', 'argentina.svg'),
(97, 'France', 'france.svg'),
(98, 'Suède', 'sweden.svg');

-- --------------------------------------------------------

--
-- Structure de la table `invitations`
--

CREATE TABLE `invitations` (
  `id` bigint(20) NOT NULL,
  `request_date` datetime NOT NULL DEFAULT current_timestamp(),
  `friendship_date` datetime DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT 0,
  `sender_id` bigint(20) DEFAULT NULL,
  `receiver_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `invitations`
--

INSERT INTO `invitations` (`id`, `request_date`, `friendship_date`, `state`, `sender_id`, `receiver_id`) VALUES
(1, '2020-05-20 18:09:06', '2020-05-20 18:11:58', 1, 1, 2),
(2, '2020-05-20 18:09:14', '2020-05-20 18:42:20', 1, 1, 4),
(4, '2020-05-20 18:09:29', '2020-05-20 18:22:54', 1, 1, 7),
(5, '2020-05-20 18:09:47', '2020-05-20 18:14:17', 1, 1, 3),
(6, '2020-05-20 18:11:06', '2020-05-20 18:23:59', 1, 1, 8),
(7, '2020-05-20 18:12:03', '2020-05-20 18:18:20', 1, 2, 5),
(8, '2020-05-20 18:12:09', '2020-05-20 18:24:00', 1, 2, 8),
(9, '2020-05-20 18:12:13', '2020-05-20 18:42:21', 1, 2, 4),
(10, '2020-05-20 18:14:23', NULL, 0, 3, 2),
(11, '2020-05-20 18:15:56', '2020-05-20 18:42:23', 1, 3, 4),
(12, '2020-05-20 18:16:00', '2020-05-20 18:22:58', 1, 3, 7),
(13, '2020-05-20 18:16:32', '2020-05-20 18:19:46', 1, 4, 6),
(14, '2020-05-20 18:16:37', '2020-05-20 18:22:56', 1, 4, 7),
(15, '2020-05-20 18:16:42', NULL, 0, 4, 8),
(16, '2020-05-20 18:19:53', '2020-05-20 18:49:42', 1, 6, 1),
(17, '2020-05-20 18:19:58', NULL, 0, 6, 2),
(18, '2020-05-20 18:20:05', '2020-05-20 18:24:03', 1, 6, 8),
(19, '2020-05-20 18:21:00', NULL, 0, 6, 5),
(20, '2020-05-20 18:23:23', NULL, 0, 7, 2),
(21, '2020-05-20 18:23:28', NULL, 0, 7, 6),
(22, '2020-05-20 18:24:13', NULL, 0, 8, 7),
(23, '2020-05-20 18:50:15', '2020-05-20 18:50:40', 1, 1, 5);

--
-- Déclencheurs `invitations`
--
DELIMITER $$
CREATE TRIGGER `after_invitations_deleted` AFTER DELETE ON `invitations` FOR EACH ROW BEGIN
	IF OLD.state = 1 THEN
		UPDATE profiles SET total_friends = total_friends - 1 WHERE id = OLD.sender_id OR id = OLD.receiver_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_invitations_updated` AFTER UPDATE ON `invitations` FOR EACH ROW BEGIN
    IF OLD.state <> NEW.state THEN
IF NEW.state = 1 THEN
    	UPDATE profiles SET total_friends = total_friends + 1 WHERE id = NEW.sender_id OR id = NEW.receiver_id;
    ELSE
    	UPDATE profiles SET total_friends = total_friends - 1 WHERE id = NEW.sender_id OR id = NEW.receiver_id;
    END IF;

END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `levels`
--

CREATE TABLE `levels` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `min_value` bigint(20) DEFAULT NULL,
  `badge` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `levels`
--

INSERT INTO `levels` (`id`, `name`, `min_value`, `badge`) VALUES
(1, 'Anteater', 1, 'anteater.svg'),
(2, 'Bear', 3, 'bear.svg'),
(3, 'Beaver', 6, 'beaver.svg'),
(4, 'Cat', 9, 'cat.svg'),
(5, 'Chicken', 15, 'chicken.svg'),
(6, 'Cow', 20, 'cow.svg'),
(7, 'Crow', 28, 'crow.svg'),
(8, 'Elephant', 38, 'elephant.svg'),
(9, 'Fox', 40, 'fox.svg'),
(10, 'Giraffe', 50, 'giraffe.svg'),
(11, 'Hedgehog', 62, 'hedgehog.svg'),
(12, 'Hippopotamus', 74, 'hippopotamus.svg'),
(13, 'Horse', 84, 'horse.svg'),
(14, 'Kangaroo', 98, 'kangaroo.svg'),
(15, 'koala', 110, 'koala.svg'),
(16, 'Leopard', 125, 'leopard.svg'),
(17, 'Lion', 140, 'lion.svg'),
(18, 'Panda', 160, 'panda.svg'),
(19, 'Rabbit', 180, 'rabbit.svg');

-- --------------------------------------------------------

--
-- Structure de la table `links`
--

CREATE TABLE `links` (
  `id` bigint(20) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `social_media_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `links`
--

INSERT INTO `links` (`id`, `username`, `social_media_id`, `user_id`) VALUES
(1, 'itshassannacer', 1, 1),
(2, 'itshassannacer', 4, 1),
(3, 'itshassannacer', 2, 1),
(4, 'hassanaitnacer', 5, 1),
(5, 'itshassannacer', 3, 1),
(6, 'andresiniesta8', 4, 3),
(7, 'leomessi', 4, 4),
(8, 'neymarjr', 4, 5),
(9, 'cristiano', 1, 7),
(10, 'cristiano', 4, 7);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) NOT NULL,
  `notification_date` datetime NOT NULL DEFAULT current_timestamp(),
  `participant_id` bigint(20) DEFAULT NULL,
  `type_id` bigint(20) DEFAULT NULL,
  `post_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `notification_date`, `participant_id`, `type_id`, `post_id`) VALUES
(1, '2020-05-20 18:30:56', 8, 2, 1),
(2, '2020-05-20 18:30:59', 8, 5, 1),
(3, '2020-05-20 18:31:30', 2, 5, 1),
(4, '2020-05-20 18:34:14', 2, 4, 2),
(5, '2020-05-20 18:34:35', 2, 5, 2),
(6, '2020-05-20 18:34:46', 8, 5, 2),
(7, '2020-05-20 18:34:54', 8, 4, 3),
(8, '2020-05-20 18:35:14', 8, 4, 4),
(9, '2020-05-20 18:36:29', 8, 5, 4),
(10, '2020-05-20 18:36:32', 8, 5, 3),
(11, '2020-05-20 18:38:08', 2, 4, 5),
(13, '2020-05-20 18:38:34', 8, 5, 5),
(14, '2020-05-20 18:39:20', 8, 1, 5),
(15, '2020-05-20 18:39:49', 1, 5, 2),
(16, '2020-05-20 18:39:52', 1, 5, 4),
(17, '2020-05-20 18:39:56', 1, 5, 5),
(18, '2020-05-20 18:40:35', 1, 1, 5),
(19, '2020-05-20 18:41:26', 1, 4, 6),
(20, '2020-05-20 18:41:31', 1, 5, 6),
(21, '2020-05-20 18:43:34', 4, 3, 7),
(22, '2020-05-20 18:43:43', 4, 5, 7),
(23, '2020-05-20 18:43:55', 2, 5, 7),
(24, '2020-05-20 18:43:59', 2, 5, 6),
(25, '2020-05-20 18:44:12', 1, 5, 7),
(26, '2020-05-20 18:47:23', 5, 6, 8),
(27, '2020-05-20 18:47:35', 5, 5, 8),
(28, '2020-05-20 18:49:15', 6, 4, 9),
(29, '2020-05-20 18:49:18', 6, 5, 9);

-- --------------------------------------------------------

--
-- Structure de la table `notifications_types`
--

CREATE TABLE `notifications_types` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `submessage` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `notifications_types`
--

INSERT INTO `notifications_types` (`id`, `name`, `submessage`, `icon`) VALUES
(1, 'adding comment', 'a laissé un commentaire sur votre activité.', 'comment-icon.svg'),
(2, 'adding text', 'a écrit un texte.', 'text-icon.svg'),
(3, 'adding video', 'a publié une vidéo.', 'video-icon.svg'),
(4, 'adding image', 'a publié une photo.', 'image-icon.svg'),
(5, 'adding fire', 'a flammé à votre activité.', 'fire-icon.svg'),
(6, 'adding audio', 'a publié un audio.', 'audio-icon.svg');

-- --------------------------------------------------------

--
-- Structure de la table `play_lists`
--

CREATE TABLE `play_lists` (
  `id` bigint(20) NOT NULL,
  `action_date` datetime NOT NULL DEFAULT current_timestamp(),
  `author_id` bigint(20) DEFAULT NULL,
  `post_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) NOT NULL,
  `text` text DEFAULT NULL,
  `media` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `posting_date` datetime NOT NULL DEFAULT current_timestamp(),
  `total_reactions` bigint(20) DEFAULT 0,
  `total_comments` bigint(20) DEFAULT 0,
  `total_views` bigint(20) DEFAULT 0,
  `author_id` bigint(20) DEFAULT NULL,
  `media_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `text`, `media`, `tags`, `posting_date`, `total_reactions`, `total_comments`, `total_views`, `author_id`, `media_type`) VALUES
(1, 'Hello I\'m Buffon an Italien goalkeeper, this is my first activity on Bridge! #Buffon', NULL, 'Bridge, FirstActivity', '2020-05-01 18:54:26', 2, 0, 0, 8, 'text'),
(2, 'Learn HTML, CSS and JS in a month!', 'xwzc5lj5py_1589999654.png', 'CSS, HTML, JS, Programming', '2020-05-02 18:34:14', 3, 0, 0, 2, 'image'),
(3, NULL, '6wt_0ad1za_1589999694.jpg', 'Football, Buffon, Goalkeeper', '2020-05-04 18:34:54', 1, 0, 0, 8, 'image'),
(4, 'Look at me Hahahaha!', 'lia28zi83z_1589999714.jpg', 'Football, Goalkeeper', '2020-05-05 18:35:14', 2, 0, 0, 8, 'image'),
(5, 'Do you know Buffon, look at him go and send him a friendship request @buffon', '5rge4v6zbp_1589999888.jpg', 'Mention, Share, Football', '2020-05-06 18:38:08', 2, 2, 5, 2, 'image'),
(6, 'Do you love programming and Web Design?', 'ydhy0fa5of_1590000086.jpg', 'Programming, Code, CSS', '2020-05-08 18:41:26', 2, 0, 0, 1, 'image'),
(7, 'Look at this shooooot, is that not awesome? He is @aitnacer', 'sq7hs24w4j_1590000214.mp4', 'Video, Football, Shoot, Ait Nacer', '2020-05-13 18:43:34', 3, 0, 1, 4, 'video'),
(8, 'In love with this song!', 'g_8b513t4r_1590000443.mp3', 'Song, Music, Ayo Teo, Rolex', '2020-05-14 18:47:23', 1, 0, 0, 5, 'audio'),
(9, 'Welcome to my page.', '0_7mhzzz8v_1590000555.jpg', 'Pique, Football, Stadium', '2020-05-15 18:49:15', 1, 0, 0, 6, 'image');

--
-- Déclencheurs `posts`
--
DELIMITER $$
CREATE TRIGGER `after_insert_posts` AFTER INSERT ON `posts` FOR EACH ROW BEGIN
    DECLARE notification INT DEFAULT 2;
	UPDATE profiles SET total_posts = total_posts + 1 WHERE id = NEW.author_id;
    
    IF NEW.media_type = 'image' THEN SET notification = 4;
    ELSEIF NEW.media_type = 'video' THEN SET notification = 3;
    ELSEIF NEW.media_type = 'audio' THEN SET notification = 6;
    ELSE SET notification = 2;
    END IF;
    
    
    INSERT INTO notifications(id, notification_date, participant_id, type_id, post_id) VALUES (NULL, NEW.posting_date, NEW.author_id, notification, NEW.id);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_posts_deleted` AFTER DELETE ON `posts` FOR EACH ROW UPDATE profiles SET total_posts = total_posts - 1, total_reactions = total_reactions - OLD.total_reactions WHERE id = OLD.author_id
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_total_reactions_updated` AFTER UPDATE ON `posts` FOR EACH ROW BEGIN
    IF OLD.total_reactions <> NEW.total_reactions THEN
        IF OLD.total_reactions > NEW.total_reactions THEN
        	UPDATE profiles SET total_reactions = total_reactions - 1 WHERE id = NEW.author_id;
        ELSEIF OLD.total_reactions < NEW.total_reactions THEN
        	UPDATE profiles SET total_reactions = total_reactions + 1 WHERE id = NEW.author_id;
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `profiles`
--

CREATE TABLE `profiles` (
  `id` bigint(20) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `main_picture` varchar(255) NOT NULL DEFAULT 'l06PscvOAGw8xvmu5BNi.jpg',
  `cover_picture` varchar(255) NOT NULL DEFAULT 'yed6um4zKmorgyzPPS1O.jpg',
  `bio` varchar(255) NOT NULL DEFAULT 'Bonjour à tous les Bridgeurs! Je commence à utiliser Bridge.',
  `state` int(11) NOT NULL DEFAULT 1,
  `confidentiality` int(11) NOT NULL DEFAULT 1,
  `personnalities` varchar(255) DEFAULT '',
  `last_activity_date` datetime NOT NULL DEFAULT current_timestamp(),
  `total_views` bigint(20) DEFAULT 0,
  `total_posts` bigint(20) DEFAULT 0,
  `total_friends` bigint(20) DEFAULT 0,
  `total_reactions` bigint(20) DEFAULT 0,
  `owner_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `profiles`
--

INSERT INTO `profiles` (`id`, `display_name`, `main_picture`, `cover_picture`, `bio`, `state`, `confidentiality`, `personnalities`, `last_activity_date`, `total_views`, `total_posts`, `total_friends`, `total_reactions`, `owner_id`) VALUES
(1, 'Hassan Ait Nacer', 'i6yi_aw6ej_1589998133.jpg', 'ku04poi3mm_1589998133.jpg', 'Je suis chef de Bridge, bienvenue!', 0, 1, 'Sport, Education, Programmation, Chef', '2020-05-21 01:14:06', 3, 1, 7, 2, 1),
(2, 'Ayoub Hach', '2t1qygqtlq_1589998423.jpg', '76gunp89d9_1589998423.jpg', 'Je suis chef de Bridge, bienvenue!', 0, 0, 'Education, Bridge, Chef', '2020-05-20 18:58:45', 5, 2, 4, 5, 2),
(3, 'Andres Iniesta', 'jhjb_w3ion_1589998534.jpeg', 'xvle_b4f2n_1589998534.jpg', 'Bonjour je suis un footballeur!', 0, 1, 'Football, Sport', '2020-05-20 18:16:02', 2, 0, 3, 0, 3),
(4, 'Leo Messi', 'i0vitjx5s5_1589998669.jpg', '1_7pryi3p__1589998669.jpg', 'Je suis un footballeur de F.C Barcelona d\'Espagne!', 0, 0, 'Barcelona, FCB', '2020-05-20 19:04:37', 5, 1, 5, 3, 4),
(5, 'Neymar Da Silva', '_h2f7bnij0_1589998759.jpg', '80at7z8b81_1589998759.jpg', 'I\'m Neymar Junior.', 0, 1, 'Sport, Football', '2020-05-20 18:50:43', 6, 1, 2, 1, 5),
(6, 'Gerard Pique', 'zbg3xpvkqd_1589998850.jpg', '5l5uegnmhi_1589998850.jpg', 'Do you know me? Are you sure?', 1, 0, 'Barcelona, Sport, Football', '2020-05-20 18:49:34', 6, 1, 3, 1, 6),
(7, 'Cristiano Ronaldo', 'ib_zsufz_u_1589998964.jpg', 'n7l8evy169_1589998964.jpg', 'Knowen by CR7, I\'m a Footballer', 0, 1, 'Sport, Football, Workout, GYM', '2020-05-20 18:23:33', 5, 0, 3, 0, 7),
(8, 'Gianluigi Buffon', 't3_6h4whup_1589999132.jpg', 'gu9qnroz1w_1589999132.jpg', 'Italien goalkeeper!', 0, 0, 'Goal, Goalkeeper, Gardien, Football', '2020-05-20 18:39:35', 5, 3, 3, 5, 8);

--
-- Déclencheurs `profiles`
--
DELIMITER $$
CREATE TRIGGER `after_profile_reactions_updated` AFTER UPDATE ON `profiles` FOR EACH ROW BEGIN
	DECLARE new_level_id INT DEFAULT 1;
    IF OLD.total_reactions <> NEW.total_reactions THEN
    	UPDATE users SET level = FLOOR((NEW.total_reactions / 4)) + 1 WHERE id = NEW.owner_id;
        SELECT MAX(id) INTO new_level_id FROM levels WHERE min_value <= (SELECT level FROM users WHERE id = NEW.owner_id);
        UPDATE users SET level_id = new_level_id WHERE id = NEW.owner_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `public_messages`
--

CREATE TABLE `public_messages` (
  `id` bigint(20) NOT NULL,
  `text` text DEFAULT NULL,
  `sending_date` datetime NOT NULL DEFAULT current_timestamp(),
  `sender_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `public_messages`
--

INSERT INTO `public_messages` (`id`, `text`, `sending_date`, `sender_id`) VALUES
(1, 'Bonjour tous le monde! Je suis Hassan le chef de Bridge.', '2020-05-20 18:57:38', 1),
(2, 'Bonjour moi aussi, si quelqu\'un de vous a une question, vous pouvez le poser dans la page FAQ!', '2020-05-20 18:58:28', 2),
(3, 'Oui bien sûr, tu es un bon chef, c\'est bien de travailler avec vous!', '2020-05-20 19:00:13', 1),
(4, 'Si j\'écris un élément HTML, il s\'affichera sous forme de texte ou de code HTML?', '2020-05-20 19:03:26', 4),
(5, '<h1>Bonjour Leo Messi</h1>', '2020-05-20 19:03:53', 1),
(6, 'I get it! Thank you', '2020-05-20 19:04:10', 4),
(7, 'Bonjour je suis Gerard Pique!', '2020-05-21 01:17:43', 6);

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) NOT NULL,
  `question` text DEFAULT '',
  `date` datetime DEFAULT current_timestamp(),
  `name` varchar(255) NOT NULL DEFAULT 'Inconnue',
  `author_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `reactions`
--

CREATE TABLE `reactions` (
  `id` bigint(20) NOT NULL,
  `reaction_date` datetime NOT NULL DEFAULT current_timestamp(),
  `reactor_id` bigint(20) DEFAULT NULL,
  `post_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `reactions`
--

INSERT INTO `reactions` (`id`, `reaction_date`, `reactor_id`, `post_id`) VALUES
(1, '2020-05-20 18:30:59', 8, 1),
(2, '2020-05-20 18:31:30', 2, 1),
(3, '2020-05-20 18:34:35', 2, 2),
(4, '2020-05-19 18:34:46', 8, 2),
(5, '2020-05-19 18:36:29', 8, 4),
(6, '2020-05-19 18:36:32', 8, 3),
(8, '2020-05-19 18:38:34', 8, 5),
(9, '2020-05-19 18:39:49', 1, 2),
(10, '2020-05-19 18:39:52', 1, 4),
(11, '2020-05-18 18:39:56', 1, 5),
(12, '2020-05-18 18:41:31', 1, 6),
(13, '2020-05-18 18:43:43', 4, 7),
(14, '2020-05-18 18:43:55', 2, 7),
(15, '2020-05-17 18:43:59', 2, 6),
(16, '2020-05-17 18:44:12', 1, 7),
(17, '2020-05-16 18:47:35', 5, 8),
(18, '2020-05-15 18:49:18', 6, 9);

--
-- Déclencheurs `reactions`
--
DELIMITER $$
CREATE TRIGGER `after_reactions_added` AFTER INSERT ON `reactions` FOR EACH ROW BEGIN
    UPDATE posts SET total_reactions = total_reactions + 1 WHERE id = NEW.post_id;
   
    INSERT INTO notifications(id, notification_date, participant_id, type_id, post_id) VALUES (NULL, NEW.reaction_date, NEW.reactor_id, 5, NEW.post_id);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_reactions_deleted` AFTER DELETE ON `reactions` FOR EACH ROW BEGIN
    UPDATE posts SET total_reactions = total_reactions - 1 WHERE id = OLD.post_id;
    
    DELETE FROM notifications WHERE notification_date = OLD.reaction_date AND post_id = OLD.post_id AND participant_id = OLD.reactor_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `badge` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `badge`) VALUES
(1, 'Admin', 'admin-badge.svg'),
(2, 'User', 'user-badge.svg');

-- --------------------------------------------------------

--
-- Structure de la table `social_media`
--

CREATE TABLE `social_media` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `main_url` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `social_media`
--

INSERT INTO `social_media` (`id`, `name`, `main_url`, `favicon`) VALUES
(1, 'Facebook', 'http://www.facebook.com/', 'facebook.svg'),
(2, 'Snapchat', 'http://www.snapchat.com/add/', 'snapchat.svg'),
(3, 'YouTube', 'http://www.youtube.com/', 'youtube.svg'),
(4, 'Instagram', 'http://www.instagram.com/', 'instagram.svg'),
(5, 'LinkedIn', 'http://www.linkedin.com/in/', 'linkedin.svg');

-- --------------------------------------------------------

--
-- Structure de la table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) NOT NULL,
  `text` text DEFAULT '',
  `date` datetime DEFAULT current_timestamp(),
  `visible` int(11) NOT NULL DEFAULT 0,
  `author_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `last_password` varchar(255) DEFAULT NULL,
  `password_update_date` datetime NOT NULL DEFAULT current_timestamp(),
  `date_of_birth` datetime DEFAULT NULL,
  `place_of_birth` varchar(255) DEFAULT NULL,
  `sexe` varchar(255) DEFAULT NULL,
  `registration_date` datetime NOT NULL DEFAULT current_timestamp(),
  `country_id` bigint(20) DEFAULT NULL,
  `level_id` bigint(20) DEFAULT NULL,
  `role_id` bigint(20) DEFAULT NULL,
  `level` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `password`, `last_password`, `password_update_date`, `date_of_birth`, `place_of_birth`, `sexe`, `registration_date`, `country_id`, `level_id`, `role_id`, `level`) VALUES
(1, 'Hassan', 'Ait Nacer', 'aitnacer', 'aitnacer@gmail.com', '$2y$10$f2iLcQl4F8oAQLv5s9ozKusvgMKFUAceSA//ppsp6U7sHwP6p9s0i', NULL, '2020-05-20 17:37:22', NULL, 'Tinghir', 'Male', '2019-06-13 18:28:08', 89, 1, 2, 1),
(2, 'Ayoub', 'Hach', 'hach', 'hach@gmail.com', '$2y$10$N4K2wIQ9RTlRyXK.p48mJ.i1gvShhSztbkDI7SjfHfAOX7eYdC8My', NULL, '2020-05-20 17:50:22', NULL, 'Marrakech', 'Male', '2019-07-17 17:50:22', 89, 1, 2, 2),
(3, 'Andres', 'Iniesta', 'iniesta', 'iniesta@yahoo.com', '$2y$10$lly9XeBN4n67otCz6NC7AuUd8ZYZFkFTdheknDfQ0aRsgl7XPMwla', NULL, '2020-05-20 17:58:50', NULL, 'Fuentealbilla', 'Male', '2019-07-26 17:58:50', 76, 1, 2, 1),
(4, 'Leo', 'Messi', 'messi', 'messi@outlook.fr', '$2y$10$cFdIc4r2PvsAobMQHGpxle/MYBBwCkISqUioLpm8Uo2Tjdv2m.T8e', NULL, '2020-05-20 18:00:03', NULL, 'Rosario', 'Male', '2019-12-26 18:28:31', 96, 1, 2, 1),
(5, 'Neymar', 'Da Silva', 'neymar', 'neymar@gmail.com', '$2y$10$C2wsrzPEjKqtzVlnBLiVx.MbHRZ42z1pdILG8htRFwZB1ie12lj/G', NULL, '2020-05-20 18:01:39', NULL, 'Mogi das Cruzes', 'Male', '2020-01-09 18:01:39', 91, 1, 2, 1),
(6, 'Gerard', 'Pique', 'pique', 'pique@hotmail.com', '$2y$10$TIvnH32xzK4qgrgduSoiUOXhRTw/h5NVPxCsQXAidHMRUMPWvTm/u', NULL, '2020-05-20 18:03:12', NULL, 'Barcelone', 'Male', '2020-01-31 18:03:12', 76, 1, 2, 1),
(7, 'Cristiano', 'Ronaldo', 'ronaldo', 'ronaldo@gmail.com', '$2y$10$jA18zjVpIjRvzpcyZ204zO6gmYbB.4qTKEWjDrsyQLn04jxELYV7q', NULL, '2020-05-20 18:05:10', NULL, 'Funchal', 'Male', '2020-02-06 18:05:10', 95, 1, 2, 1),
(8, 'Gianluigi', 'Buffon', 'buffon', 'buffon@gmail.com', '$2y$10$KwE45lwQQ/QfKJbN7iJc1ujg45oUxwO6WIzIOYeDISyqgZub7YLaK', NULL, '2020-05-20 18:10:50', NULL, 'Carrare', 'Male', '2020-03-12 18:10:50', 12, 1, 2, 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Comments_Profiles` (`commentator_id`),
  ADD KEY `FK_Comments_Posts` (`post_id`);

--
-- Index pour la table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `invitations`
--
ALTER TABLE `invitations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Invitations_Profiles_Sender` (`sender_id`),
  ADD KEY `FK_Invitations_Profiles_Receiver` (`receiver_id`);

--
-- Index pour la table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Links_SocialMedia` (`social_media_id`),
  ADD KEY `FK_Links_Users` (`user_id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Notification_Profiles` (`participant_id`),
  ADD KEY `FK_Notifications_Posts` (`post_id`),
  ADD KEY `FK_Notifications_NotificationsTypes` (`type_id`);

--
-- Index pour la table `notifications_types`
--
ALTER TABLE `notifications_types`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `play_lists`
--
ALTER TABLE `play_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_PlayLists_Posts` (`post_id`),
  ADD KEY `FK_PlayLists_Profiles` (`author_id`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Posts_Profiles` (`author_id`);

--
-- Index pour la table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Profiles_Users` (`owner_id`);

--
-- Index pour la table `public_messages`
--
ALTER TABLE `public_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_PublicMessages_Profiles` (`sender_id`);

--
-- Index pour la table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Questions_Profiles` (`author_id`);

--
-- Index pour la table `reactions`
--
ALTER TABLE `reactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Reactions_Profiles` (`reactor_id`),
  ADD KEY `FK_Reactions_Posts` (`post_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `social_media`
--
ALTER TABLE `social_media`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Testimonials_Profiles` (`author_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `U_Username` (`username`),
  ADD UNIQUE KEY `U_Email` (`email`),
  ADD KEY `FK_Users_Countries` (`country_id`),
  ADD KEY `FK_Users_Levels` (`level_id`),
  ADD KEY `FK_Users_Roles` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT pour la table `invitations`
--
ALTER TABLE `invitations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `levels`
--
ALTER TABLE `levels`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `links`
--
ALTER TABLE `links`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `notifications_types`
--
ALTER TABLE `notifications_types`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `play_lists`
--
ALTER TABLE `play_lists`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `public_messages`
--
ALTER TABLE `public_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reactions`
--
ALTER TABLE `reactions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `social_media`
--
ALTER TABLE `social_media`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `FK_Comments_Posts` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Comments_Profiles` FOREIGN KEY (`commentator_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `invitations`
--
ALTER TABLE `invitations`
  ADD CONSTRAINT `FK_Invitations_Profiles_Receiver` FOREIGN KEY (`receiver_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Invitations_Profiles_Sender` FOREIGN KEY (`sender_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `links`
--
ALTER TABLE `links`
  ADD CONSTRAINT `FK_Links_SocialMedia` FOREIGN KEY (`social_media_id`) REFERENCES `social_media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Links_Users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `FK_Notification_Profiles` FOREIGN KEY (`participant_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Notifications_NotificationsTypes` FOREIGN KEY (`type_id`) REFERENCES `notifications_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Notifications_Posts` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `play_lists`
--
ALTER TABLE `play_lists`
  ADD CONSTRAINT `FK_PlayLists_Posts` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_PlayLists_Profiles` FOREIGN KEY (`author_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `FK_Posts_Profiles` FOREIGN KEY (`author_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `FK_Profiles_Users` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `public_messages`
--
ALTER TABLE `public_messages`
  ADD CONSTRAINT `FK_PublicMessages_Profiles` FOREIGN KEY (`sender_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `FK_Questions_Profiles` FOREIGN KEY (`author_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `reactions`
--
ALTER TABLE `reactions`
  ADD CONSTRAINT `FK_Reactions_Posts` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Reactions_Profiles` FOREIGN KEY (`reactor_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `testimonials`
--
ALTER TABLE `testimonials`
  ADD CONSTRAINT `FK_Testimonials_Profiles` FOREIGN KEY (`author_id`) REFERENCES `profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_Users_Countries` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Users_Levels` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Users_Roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
