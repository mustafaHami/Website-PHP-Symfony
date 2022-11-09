-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 07, 2022 at 07:14 PM
-- Server version: 5.7.32
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `mi5`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visuel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `texte` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categorie`
--

INSERT INTO `categorie` (`id`, `libelle`, `visuel`, `texte`) VALUES
(1, 'Fruits', 'images/fruits.jpg', 'De la passion ou de ton imagination'),
(2, 'Légumes', 'images/legumes.jpg', 'Plus tu en manges, moins tu en es un'),
(3, 'Junk Food', 'images/junk_food.jpg', 'Chère et cancérogène, tu es prévenu(e)');

-- --------------------------------------------------------

--
-- Table structure for table `commande`
--

CREATE TABLE `commande` (
  `id` int(11) NOT NULL,
  `usager_id` int(11) NOT NULL,
  `date_commande` date NOT NULL,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `commande`
--

INSERT INTO `commande` (`id`, `usager_id`, `date_commande`, `statut`) VALUES
(1, 2, '2022-02-20', 'commande valider'),
(2, 2, '2022-02-20', 'commande valider'),
(3, 2, '2022-02-20', 'commande valider');

-- --------------------------------------------------------

--
-- Table structure for table `ligne_commande`
--

CREATE TABLE `ligne_commande` (
  `commande_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `quantite` int(11) DEFAULT NULL,
  `prix` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ligne_commande`
--

INSERT INTO `ligne_commande` (`commande_id`, `produit_id`, `quantite`, `prix`) VALUES
(1, 5, 2, '3.40'),
(1, 6, 1, '1.81'),
(2, 1, 1, '3.42'),
(2, 6, 1, '1.81'),
(2, 9, 1, '2.50'),
(3, 5, 1, '1.70');

-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

CREATE TABLE `produit` (
  `id` int(11) NOT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `texte` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visuel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produit`
--

INSERT INTO `produit` (`id`, `categorie_id`, `libelle`, `texte`, `visuel`, `prix`) VALUES
(1, 1, 'Pomme', 'Elle est bonne pour la tienne', 'images/pommes.jpg', '3.42'),
(2, 1, 'Poire', 'Ici tu n\'en es pas une', 'images/poires.jpg', '2.11'),
(3, 1, 'Pêche', 'Elle va te la donner', 'images/peche.jpg', '2.84'),
(4, 2, 'Carotte', 'C\'est bon pour ta vue', 'images/carottes.jpg', '2.90'),
(5, 2, 'Tomate', 'Fruit ou Légume ? Légume', 'images/tomates.jpg', '1.70'),
(6, 2, 'Chou Romanesco', 'Mange des fractales', 'images/romanesco.jpg', '1.81'),
(7, 3, 'Nutella', 'C\'est bon, sauf pour ta santé', 'images/nutella.jpg', '4.50'),
(8, 3, 'Pizza', 'Y\'a pas pire que za', 'images/pizza.jpg', '8.25'),
(9, 3, 'Oreo', 'Seulement si tu es un smartphone', 'images/oreo.jpg', '2.50');

-- --------------------------------------------------------

--
-- Table structure for table `usager`
--

CREATE TABLE `usager` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usager`
--

INSERT INTO `usager` (`id`, `email`, `roles`, `password`, `nom`, `prenom`) VALUES
(1, 'mustafahami61@hotmail.fr', '[\"ROLE_CLIENT\"]', '$2y$13$WdBj9clzgaMpb6o4kfGv.eG52dWvih0479G99KZw234NCG2mdil3i', 'demir', 'mustafa'),
(2, 'mustafa.demir@etu.univ-grenoble-alpes.fr', '[\"ROLE_CLIENT\"]', '$2y$13$G0BIwZZaxcgLzJQGvWuX.uVrzgMua/dYyN9F8rKQwY/pw/M/34Q6O', 'demir2', 'mustafa2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6EEAA67D4F36F0FC` (`usager_id`);

--
-- Indexes for table `ligne_commande`
--
ALTER TABLE `ligne_commande`
  ADD PRIMARY KEY (`commande_id`,`produit_id`),
  ADD KEY `IDX_3170B74B82EA2E54` (`commande_id`),
  ADD KEY `IDX_3170B74BF347EFB` (`produit_id`);

--
-- Indexes for table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_29A5EC27BCF5E72D` (`categorie_id`);

--
-- Indexes for table `usager`
--
ALTER TABLE `usager`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_3CDC65FFE7927C74` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `commande`
--
ALTER TABLE `commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `usager`
--
ALTER TABLE `usager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_6EEAA67D4F36F0FC` FOREIGN KEY (`usager_id`) REFERENCES `usager` (`id`);

--
-- Constraints for table `ligne_commande`
--
ALTER TABLE `ligne_commande`
  ADD CONSTRAINT `FK_3170B74B82EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`),
  ADD CONSTRAINT `FK_3170B74BF347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`);

--
-- Constraints for table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `FK_29A5EC27BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`);
