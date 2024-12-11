-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 11 déc. 2024 à 09:07
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog_ocr_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `chapo` text NOT NULL,
  `content` text NOT NULL,
  `date_creation` datetime NOT NULL,
  `author` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`author`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `title`, `chapo`, `content`, `date_creation`, `author`) VALUES
(5, 'gergeg', 'lolololocqoskdcosdvoskdv', 'ccccssss', '2024-04-10 09:31:09', 18),
(6, 'gergeg', 'scscscsccc', 'sehsth y;dt,dtyt:dse &lt;', '2024-04-08 14:32:26', 18),
(7, 'TOTO', 'fffffffffffffff', 'fffffffffffv&lt;?php echo &quot;toto&quot;?&gt;', '2024-04-10 10:42:21', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id_comment` int NOT NULL AUTO_INCREMENT,
  `content_comment` text NOT NULL,
  `user_id` int NOT NULL,
  `validate` tinyint(1) NOT NULL,
  `article_id` int DEFAULT NULL,
  PRIMARY KEY (`id_comment`),
  KEY `article_id` (`article_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id_comment`, `content_comment`, `user_id`, `validate`, `article_id`) VALUES
(18, 'comenter l&#039;article\r\n', 32, 1, 6);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `name`, `firstname`, `email`, `password`, `role`) VALUES
(18, 'delva', 'tom', 'Tomtfc8200@gmail.com', '$2y$12$NiRQe77X.UMQxlCzhcwG9OzsJ.NDtcBfED.VgFpi36e.i0ginHPFm', 'admin'),
(26, 'test', 'test', 'test@test.fr', '$2y$12$65jNn15C0vrRsH0SiQKyuO23McfaaKsCf/eVRPbGs2EEu5ijFnFB6', 'admin'),
(28, 'test', 'test', 'test1@test.fr', '$2y$12$GTpD3hC9d6RD/FkDGGaiu.omGikm.b5r7AM6yhfUGDJkiu5e6CcX.', 'user'),
(32, 'test', 'test', 'tom@studio509.fr', '$2y$12$KKUHKH.ehsap2/TfW.lHP.NE0hU5I9AqSuej3lWzZziRnzSQdx2Za', 'user');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`author`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `article_id` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
