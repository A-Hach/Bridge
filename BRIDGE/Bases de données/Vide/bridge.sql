-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 20 mai 2020 à 19:17
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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT pour la table `invitations`
--
ALTER TABLE `invitations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `levels`
--
ALTER TABLE `levels`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `links`
--
ALTER TABLE `links`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `public_messages`
--
ALTER TABLE `public_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reactions`
--
ALTER TABLE `reactions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

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
