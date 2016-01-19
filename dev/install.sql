-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 11 Janvier 2016 à 17:34
-- Version du serveur :  5.6.24
-- Version de PHP :  5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `iut_qcmanager`
--

-- --------------------------------------------------------

--
-- Structure de la table `album`
--

CREATE TABLE IF NOT EXISTS `album` (
  `id` int(11) NOT NULL,
  `artist` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `owner` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `album`
--

INSERT INTO `album` (`id`, `artist`, `title`, `owner`) VALUES
(1, 'The  Military  Wives', 'In  My  Dreams', 1),
(2, 'Adele', '21', 1),
(3, 'Bruce  Springsteen', 'Wrecking Ball (Deluxe)', 1),
(7, 'Toby Fox', 'Undertale', 2),
(8, 'Deluxe', 'Making Music', 2),
(9, 'POPO', 'Yolo', 1);

-- --------------------------------------------------------

--
-- Structure de la table `answer`
--

CREATE TABLE IF NOT EXISTS `answer` (
  `answer_id` int(11) NOT NULL,
  `answer_student_user_id` int(11) NOT NULL,
  `answer_choice_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `answer`
--

INSERT INTO `answer` (`answer_id`, `answer_student_user_id`, `answer_choice_id`) VALUES
(1, 2, 46),
(2, 2, 42),
(3, 2, 38),
(4, 2, 33),
(5, 1, 75),
(6, 1, 83),
(7, 1, 80),
(8, 1, 72),
(9, 2, 52),
(10, 2, 61),
(11, 2, 63),
(12, 2, 55),
(13, 3, 51),
(14, 3, 61),
(15, 3, 56),
(16, 3, 63),
(17, 3, 46),
(18, 3, 43),
(19, 3, 40),
(20, 3, 34),
(21, 3, 75),
(22, 3, 81),
(23, 3, 71),
(24, 3, 78),
(25, 1, 100),
(26, 1, 89),
(27, 1, 107),
(28, 1, 103),
(29, 1, 97),
(30, 1, 94),
(31, 1, 92),
(32, 1, 109),
(33, 1, 114),
(34, 1, 119),
(36, 1, 131),
(37, 1, 129),
(38, 1, 122),
(40, 1, 134);

-- --------------------------------------------------------

--
-- Structure de la table `choice`
--

CREATE TABLE IF NOT EXISTS `choice` (
  `choice_id` int(11) NOT NULL,
  `choice_question_id` int(11) NOT NULL,
  `choice_content` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `choice_status` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `choice`
--

INSERT INTO `choice` (`choice_id`, `choice_question_id`, `choice_content`, `choice_status`) VALUES
(32, 4, 'l''alignement', 0),
(33, 4, 'le soulignement', 1),
(34, 4, 'le centrage', 0),
(35, 4, 'la justification', 0),
(36, 3, 'd''abscisse', 0),
(37, 3, 'd''ordonnÃ©e', 0),
(38, 3, 'boolÃ©ens', 1),
(39, 3, 'adjacents', 0),
(40, 3, 'de proximitÃ©', 0),
(41, 2, 'le mode bloquÃ©', 0),
(42, 2, 'le mode sÃ©curisÃ©', 1),
(43, 2, 'le mode confidentiel', 0),
(44, 2, 'le mode secret', 0),
(45, 2, 'le mode verrouillÃ©', 0),
(46, 1, 'on le coupe et on le copie Ã  l''endroit dÃ©sirÃ©', 0),
(47, 1, 'on le coupe et on le colle Ã  l''endroit dÃ©sirÃ©', 0),
(48, 1, 'on le coll et on le copie Ã  l''endroit dÃ©sirÃ©', 0),
(49, 1, 'on le fait glisser jusqu''Ã  l''endroit dÃ©sirÃ©', 0),
(50, 1, 'on le copie et on le colle Ã  l''endroit dÃ©sirÃ©', 1),
(51, 5, 'Elocution', 1),
(52, 5, 'Ellocution', 0),
(53, 5, 'Elocusion', 0),
(54, 5, 'Elokution', 0),
(55, 6, 'Faite', 1),
(56, 6, 'Fais', 0),
(57, 6, 'Fait', 0),
(58, 6, 'Faites', 0),
(59, 7, 'Il voudrait que vous trirez ces papiers', 0),
(60, 7, 'Il voudrait que vous triez ces papiers', 0),
(61, 7, 'Il voudrait que vous trierez ces papiers', 0),
(62, 7, 'Il voudrait que vous triiez ces papiers', 1),
(63, 8, 'Nonchalance', 0),
(64, 8, 'PrÃ©ciositÃ©', 1),
(65, 8, 'NÃ©gligence', 0),
(66, 8, 'FutilitÃ©', 0),
(71, 9, '(x-y)(x+y)', 0),
(72, 9, '(x+y)Â²', 0),
(73, 9, '(x-iy)(x+iy)', 1),
(74, 9, '(x-y)Â²', 0),
(75, 10, '1 bar = 105 N.mÂ².sÂ² ', 1),
(76, 10, '1 bar = 105 kg.m.sÂ²', 0),
(77, 11, '7,7 x 10^7  J ', 0),
(78, 11, '7,7 x 10^7  N ', 0),
(79, 11, '10 x 10^8  J', 0),
(80, 11, '10 x 10^14 J ', 1),
(81, 12, '0.0001 mm ', 0),
(82, 12, '10^(-8) mm', 0),
(83, 12, '0.1 mm ', 0),
(84, 12, '0.000001 mm', 1),
(88, 13, 'Londres', 0),
(89, 13, 'AthÃ¨nes', 1),
(90, 13, 'Sydney', 0),
(91, 14, '40,15 km', 0),
(92, 14, '42,10 km', 0),
(93, 14, '42,19 km', 1),
(94, 15, 'le brÃ©sil', 0),
(95, 15, 'l''espagne', 0),
(96, 15, 'l''afrique du sud', 1),
(97, 16, 'une nageuse', 1),
(98, 16, 'une sprinteuse', 0),
(99, 16, 'une navigatrice', 0),
(100, 17, '1996', 0),
(101, 17, '1947', 0),
(102, 17, '1950', 1),
(103, 18, 'Roumanie', 0),
(104, 18, 'Pays-Bas', 0),
(105, 18, 'Italie', 1),
(106, 19, '3', 0),
(107, 19, '5', 1),
(108, 19, '7', 0),
(109, 20, 'Spyro', 0),
(110, 20, 'Koopa', 0),
(111, 20, 'Kirby', 1),
(112, 20, 'Crash Bandicoot', 0),
(113, 21, 'Falco', 0),
(114, 21, 'Pikachu', 0),
(115, 21, 'Link', 1),
(116, 21, 'Solid Snake', 0),
(117, 21, 'Ganondorf', 0),
(118, 22, 'Aladdin', 0),
(119, 22, 'Spyro', 1),
(120, 22, 'Lara Croft', 0),
(121, 23, '3e siÃ¨cle', 0),
(122, 23, '9e siÃ¨cle', 1),
(123, 23, '12e siÃ¨cle', 0),
(127, 24, 'TonalitÃ© fixe et basse continue', 0),
(128, 24, 'TonalitÃ© continue et basse fixe', 0),
(129, 24, 'Basse continue et â€œClavier bien tempÃ©rÃ©â€', 1),
(130, 25, 'Classicisme', 0),
(131, 25, 'NÃ©o-classicisme', 1),
(132, 25, 'PrÃ©-classicisme', 0),
(133, 26, 'GrÃ©gorien', 0),
(134, 26, 'Latin', 1),
(135, 26, 'Grec', 0);

-- --------------------------------------------------------

--
-- Structure de la table `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `question_id` int(11) NOT NULL,
  `question_questionnaire_id` int(11) NOT NULL,
  `question_num` int(11) NOT NULL,
  `question_content` varchar(255) CHARACTER SET latin1 NOT NULL,
  `question_type` enum('checkbox','radio') CHARACTER SET latin1 NOT NULL,
  `question_hint` text CHARACTER SET latin1,
  `question_weight` enum('1','2','3','4','5') CHARACTER SET latin1 NOT NULL DEFAULT '1'
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `question`
--

INSERT INTO `question` (`question_id`, `question_questionnaire_id`, `question_num`, `question_content`, `question_type`, `question_hint`, `question_weight`) VALUES
(1, 2, 1, 'Dans un traitement de texte, que doit-on faire pour dupliquer un paragraphe', 'radio', 'Vous devriez essayer', '5'),
(2, 2, 2, 'Lors de l''achat d''un produit sur Internet, au moment de transmettre ses coordonnÃ©es bancaires, les donnÃ©es sont cryptÃ©es pour Ã©viter toute interception par un tiers. Comment appelle-t-on ce mode de transaction', 'radio', '', '5'),
(3, 2, 3, 'Dans une recherche sur le Web, quel terme qualifie les opÃ©rateurs ', 'radio', 'true, false', '5'),
(4, 2, 4, 'Quelle est la propriÃ©tÃ© propre aux caractÃ¨res', 'radio', 'pensez que la propriÃ©tÃ© ne doit pouvoir s''appliquer sur sur un caractÃ¨re seul', '5'),
(5, 1, 1, 'Quel mot est bien orthographiÃ© ?', 'radio', '', '1'),
(6, 1, 4, 'ComplÃ©ter la phrase suivante : Â« Je mâ€™aperÃ§ois de lâ€™erreur que jâ€™ai . Â»', 'radio', '', '1'),
(7, 1, 2, 'Quelle est la phrase correcte ?', 'radio', '', '1'),
(8, 1, 3, 'Trouvez le mot nâ€™ayant pas un sens proche du mot Â« dÃ©sinvolture Â»', 'radio', '', '1'),
(9, 3, 4, 'On considÃ¨re lâ€™expression xÂ² + yÂ² oÃ¹ x et y sont des nombres rÃ©els. Quelle est la bonne rÃ©ponse ', 'radio', '', '1'),
(10, 3, 1, ' On considÃ¨re les deux unitÃ©s de pression : le Bar et le Pascal. Laquelle des propositions suivantes est correcte?', 'radio', '', '1'),
(11, 3, 3, 'Lâ€™Ã©nergie cinÃ©tique dâ€™un train de 200 tonnes roulant Ã  100 km/h est de :', 'radio', '', '1'),
(12, 3, 2, 'La taille dâ€™une molÃ©cule dâ€™eau est de lâ€™ordre de :', 'radio', '', '1'),
(13, 4, 2, 'Les Jeux olympiques de 2008 se sont tenus Ã  PÃ©kin. OÃ¹ se sont dÃ©roulÃ©s ceux de 2004 ?', 'radio', '', '1'),
(14, 4, 3, 'Quelle est la longueur dâ€™un marathon ?', 'radio', '', '1'),
(15, 4, 4, 'Quel pays a organisÃ© la Coupe du monde de football en 2010 ?', 'radio', '', '1'),
(16, 4, 6, 'AurÃ©lie Muller', 'radio', '', '1'),
(17, 4, 1, 'Le championnat du monde Formule 1 a Ã©tÃ© crÃ©Ã© en :', 'radio', '', '1'),
(18, 4, 7, 'Lâ€™Ã©quipe de France de football a Ã©tÃ© Ã©liminÃ©e Ã  lâ€™Euro 2008, en huitiÃ¨me de finale. Contre quelle Ã©quipe a-t-elle jouÃ© son dernier match ?', 'radio', '', '1'),
(19, 4, 5, 'Combien de fois la France a-t-elle accueilli sur son territoire les Jeux olympiques ?', 'radio', '', '1'),
(20, 5, 1, 'Petit boule rose je m''envole en me remplissant d''air et si je gobe un ennemi, je peux lui voler son pouvoir si j''appuie sur la touche du bas. Je suis. . .', 'radio', '', '1'),
(21, 5, 2, 'Je suis obligÃ© de parcourir le royaume D''Hyrule afin de sauver ma princesse et de trouver les diffÃ©rents morceaux de la Triforce. Je suis. . .', 'radio', '', '1'),
(22, 5, 3, 'Je vole Ã  travers des royaumes colorÃ©s et je collecte des diamants en crachant du feu ? Alors, vous me reconnaissez ?', 'radio', '', '1'),
(23, 6, 1, 'Ã€ quand remontent les premiÃ¨res notations musicales en France ?', 'radio', '', '1'),
(24, 6, 3, 'Quels Ã©lÃ©ments correspondent le mieux Ã  la musique baroque ?', 'radio', '', '1'),
(25, 6, 2, 'Ã€ quel mouvement se rattache la Symphonie Classique de Prokofiev ?', 'radio', '', '1'),
(26, 6, 4, 'En quelle langue est habituellement interprÃ©tÃ© un chant grÃ©gorien ?', 'radio', '', '1');

-- --------------------------------------------------------

--
-- Structure de la table `questionnaire`
--

CREATE TABLE IF NOT EXISTS `questionnaire` (
  `questionnaire_id` int(11) NOT NULL,
  `questionnaire_user_id` int(11) NOT NULL,
  `questionnaire_title` text CHARACTER SET latin1 NOT NULL,
  `questionnaire_description` text CHARACTER SET latin1 NOT NULL,
  `questionnaire_start_date` int(10) unsigned NOT NULL,
  `questionnaire_end_date` int(10) unsigned NOT NULL,
  `questionnaire_notation_rule` int(10) unsigned NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `questionnaire`
--

INSERT INTO `questionnaire` (`questionnaire_id`, `questionnaire_user_id`, `questionnaire_title`, `questionnaire_description`, `questionnaire_start_date`, `questionnaire_end_date`, `questionnaire_notation_rule`) VALUES
(1, 1, 'SÃ©lection du CEFIPA', 'QCM de sÃ©lection du Centre supÃ©rieur de formation par l''apprentissage.', 1452517200, 1452519540, 1),
(2, 1, 'C2i compÃ©tence 1', 'Test du C2i de 2014', 1452521760, 1452608160, 0),
(3, 2, 'Maths', 'Questionnaire de MathÃ©matiques', 1452527100, 1452613500, 0),
(4, 5, 'Sports', 'QCM concernant les sports en gÃ©nÃ©ral', 1452528120, 1452614520, 0),
(5, 5, 'Jeu VidÃ©o', 'QCM  centrÃ© sur la culture gÃ©nÃ©rale concernant les jeux vidÃ©os communs', 1452528480, 1452614880, 0),
(6, 5, 'Musique', 'l''Histoire de la musique, rÃ©sumÃ©e en un QCM', 1452528840, 1452615240, 0);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL,
  `user_firstname` text CHARACTER SET latin1 NOT NULL,
  `user_lastname` text CHARACTER SET latin1 NOT NULL,
  `user_email` char(255) CHARACTER SET latin1 NOT NULL,
  `user_photo_path` varchar(255) CHARACTER SET latin1 NOT NULL,
  `user_schoolname` text CHARACTER SET latin1 NOT NULL,
  `user_password` char(255) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`user_id`, `user_firstname`, `user_lastname`, `user_email`, `user_photo_path`, `user_schoolname`, `user_password`) VALUES
(1, 'Saugues', 'Benjamin', 'benjamin.saugues@yopmail.com', '', 'IUT', '6be8990ed070b92e6300d4e88509c662606aaf40'),
(2, 'Chazelle', 'Benjamin', 'benjamin.chazelle@yopmail.com', '', 'IUT', '6be8990ed070b92e6300d4e88509c662606aaf40'),
(3, 'Bourdier', 'Valentin', 'valentin.bourdier@yopmail.com', '', 'IUT', '6be8990ed070b92e6300d4e88509c662606aaf40'),
(4, 'Benouaret', 'Karim', 'karim.benouaret@yopmail.com', '', 'IUT', '6be8990ed070b92e6300d4e88509c662606aaf40'),
(5, 'MellÃ©', 'Tom', 'tom.melle@yopmail.com', '', 'IUT', '6be8990ed070b92e6300d4e88509c662606aaf40');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `real_name` varchar(150) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `real_name`) VALUES
(1, 'alice', '6384e2b2184bcbf58eccf10ca7a6563c', 'Alice'),
(2, 'bob', '9f9d51bc70ef21ca5c14f307980a29d8', 'Bob');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`answer_id`), ADD KEY `answer_student_user_id` (`answer_student_user_id`), ADD KEY `answer_choice_id` (`answer_choice_id`);

--
-- Index pour la table `choice`
--
ALTER TABLE `choice`
  ADD PRIMARY KEY (`choice_id`), ADD KEY `choice_question_id` (`choice_question_id`);

--
-- Index pour la table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`question_id`), ADD KEY `question_questionnaire_id` (`question_questionnaire_id`);

--
-- Index pour la table `questionnaire`
--
ALTER TABLE `questionnaire`
  ADD PRIMARY KEY (`questionnaire_id`), ADD KEY `questionnaire_user_id` (`questionnaire_user_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `user_email` (`user_email`), ADD KEY `user_EMAIL_INDEX` (`user_email`), ADD KEY `user_PASSWORD_INDEX` (`user_password`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `album`
--
ALTER TABLE `album`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT pour la table `answer`
--
ALTER TABLE `answer`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT pour la table `choice`
--
ALTER TABLE `choice`
  MODIFY `choice_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=136;
--
-- AUTO_INCREMENT pour la table `question`
--
ALTER TABLE `question`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT pour la table `questionnaire`
--
ALTER TABLE `questionnaire`
  MODIFY `questionnaire_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
