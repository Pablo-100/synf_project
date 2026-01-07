-- =====================================================
-- SCRIPT POUR REMPLIR TOUTES LES TABLES - synf_project
-- Exécuter dans phpMyAdmin > SQL
-- =====================================================

-- Supprimer les tables existantes (dans l'ordre pour respecter les clés étrangères)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `order_item`;
DROP TABLE IF EXISTS `order`;
DROP TABLE IF EXISTS `reservation`;
DROP TABLE IF EXISTS `product`;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `messenger_messages`;
DROP TABLE IF EXISTS `doctrine_migration_versions`;
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- CRÉATION DES TABLES
-- =====================================================

-- Table: user
CREATE TABLE `user` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `email` VARCHAR(180) NOT NULL,
    `roles` JSON NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `nom` VARCHAR(100) NOT NULL,
    `prenom` VARCHAR(100) NOT NULL,
    `telephone` VARCHAR(20) DEFAULT NULL,
    `adresse` LONGTEXT DEFAULT NULL,
    `avatar` VARCHAR(1024) DEFAULT NULL,
    `created_at` DATETIME NOT NULL,
    `google_id` VARCHAR(255) DEFAULT NULL,
    `facebook_id` VARCHAR(255) DEFAULT NULL,
    UNIQUE INDEX UNIQ_8D93D649E7927C74 (`email`),
    UNIQUE INDEX UNIQ_8D93D64976F5C865 (`google_id`),
    UNIQUE INDEX UNIQ_8D93D6499BE8FD98 (`facebook_id`),
    PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: product
CREATE TABLE `product` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `nom` VARCHAR(255) NOT NULL,
    `description` LONGTEXT NOT NULL,
    `prix` DECIMAL(10,2) NOT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `categorie` VARCHAR(100) NOT NULL,
    `stock` INT NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME DEFAULT NULL,
    `disponible` TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: order
CREATE TABLE `order` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `user_id` INT NOT NULL,
    `numero_commande` VARCHAR(50) NOT NULL,
    `montant_total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `statut` VARCHAR(50) NOT NULL DEFAULT 'en_cours',
    `adresse_livraison` LONGTEXT DEFAULT NULL,
    `commentaire` LONGTEXT DEFAULT NULL,
    `created_at` DATETIME NOT NULL,
    `validated_at` DATETIME DEFAULT NULL,
    UNIQUE INDEX UNIQ_ORDER_NUMERO (`numero_commande`),
    INDEX IDX_ORDER_USER (`user_id`),
    PRIMARY KEY(`id`),
    CONSTRAINT FK_ORDER_USER FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: order_item
CREATE TABLE `order_item` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `commande_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantite` INT NOT NULL DEFAULT 1,
    `prix_unitaire` DECIMAL(10,2) NOT NULL,
    INDEX IDX_ORDER_ITEM_COMMANDE (`commande_id`),
    INDEX IDX_ORDER_ITEM_PRODUCT (`product_id`),
    PRIMARY KEY(`id`),
    CONSTRAINT FK_ORDER_ITEM_COMMANDE FOREIGN KEY (`commande_id`) REFERENCES `order` (`id`) ON DELETE CASCADE,
    CONSTRAINT FK_ORDER_ITEM_PRODUCT FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: reservation
CREATE TABLE `reservation` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `user_id` INT DEFAULT NULL,
    `nom` VARCHAR(100) NOT NULL,
    `prenom` VARCHAR(100) NOT NULL,
    `telephone` VARCHAR(50) NOT NULL,
    `email` VARCHAR(180) NOT NULL,
    `date_reservation` DATE NOT NULL,
    `heure_reservation` TIME NOT NULL,
    `nombre_personnes` INT NOT NULL,
    `statut` VARCHAR(50) NOT NULL DEFAULT 'en_attente',
    `commentaire` LONGTEXT DEFAULT NULL,
    `created_at` DATETIME NOT NULL,
    INDEX IDX_RESERVATION_USER (`user_id`),
    PRIMARY KEY(`id`),
    CONSTRAINT FK_RESERVATION_USER FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: messenger_messages (pour Symfony Messenger)
CREATE TABLE `messenger_messages` (
    `id` BIGINT AUTO_INCREMENT NOT NULL,
    `body` LONGTEXT NOT NULL,
    `headers` LONGTEXT NOT NULL,
    `queue_name` VARCHAR(190) NOT NULL,
    `created_at` DATETIME NOT NULL,
    `available_at` DATETIME NOT NULL,
    `delivered_at` DATETIME DEFAULT NULL,
    INDEX IDX_MESSENGER_QUEUE (`queue_name`),
    INDEX IDX_MESSENGER_AVAILABLE (`available_at`),
    INDEX IDX_MESSENGER_DELIVERED (`delivered_at`),
    PRIMARY KEY(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: doctrine_migration_versions
CREATE TABLE `doctrine_migration_versions` (
    `version` VARCHAR(191) NOT NULL,
    `executed_at` DATETIME DEFAULT NULL,
    `execution_time` INT DEFAULT NULL,
    PRIMARY KEY(`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INSERTION DES DONNÉES
-- =====================================================

-- =====================================================
-- TABLE: user
-- Mot de passe pour tous: "password123"
-- Hash généré avec password_hash('password123', PASSWORD_BCRYPT)
-- =====================================================
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `nom`, `prenom`, `telephone`, `adresse`, `avatar`, `created_at`, `google_id`, `facebook_id`) VALUES
(1, 'admin@restaurant.com', '["ROLE_ADMIN", "ROLE_USER"]', '$2y$10$XWiwho6.Gwf1NdMqquw/lu0v/fRJW.RRdK2CunK7wYmlt15rN0/xO', 'Admin', 'Super', '0600000001', '123 Rue de l''Administration, 75001 Paris', NULL, '2025-01-01 10:00:00', NULL, NULL),
(2, 'jean.dupont@email.com', '["ROLE_USER"]', '$2y$10$XWiwho6.Gwf1NdMqquw/lu0v/fRJW.RRdK2CunK7wYmlt15rN0/xO', 'Dupont', 'Jean', '0612345678', '45 Avenue des Champs-Élysées, 75008 Paris', NULL, '2025-06-15 14:30:00', NULL, NULL),
(3, 'marie.martin@email.com', '["ROLE_USER"]', '$2y$10$XWiwho6.Gwf1NdMqquw/lu0v/fRJW.RRdK2CunK7wYmlt15rN0/xO', 'Martin', 'Marie', '0623456789', '12 Rue de la Paix, 75002 Paris', NULL, '2025-07-20 09:15:00', NULL, NULL),
(4, 'pierre.bernard@email.com', '["ROLE_USER"]', '$2y$10$XWiwho6.Gwf1NdMqquw/lu0v/fRJW.RRdK2CunK7wYmlt15rN0/xO', 'Bernard', 'Pierre', '0634567890', '78 Boulevard Haussmann, 75009 Paris', NULL, '2025-08-10 16:45:00', NULL, NULL),
(5, 'sophie.petit@email.com', '["ROLE_USER"]', '$2y$10$XWiwho6.Gwf1NdMqquw/lu0v/fRJW.RRdK2CunK7wYmlt15rN0/xO', 'Petit', 'Sophie', '0645678901', '34 Rue du Faubourg Saint-Honoré, 75008 Paris', NULL, '2025-09-05 11:20:00', NULL, NULL),
(6, 'lucas.moreau@email.com', '["ROLE_USER"]', '$2y$10$XWiwho6.Gwf1NdMqquw/lu0v/fRJW.RRdK2CunK7wYmlt15rN0/xO', 'Moreau', 'Lucas', '0656789012', '56 Avenue Montaigne, 75008 Paris', NULL, '2025-10-12 08:00:00', NULL, NULL),
(7, 'emma.leroy@email.com', '["ROLE_USER"]', '$2y$10$XWiwho6.Gwf1NdMqquw/lu0v/fRJW.RRdK2CunK7wYmlt15rN0/xO', 'Leroy', 'Emma', '0667890123', '23 Rue de Rivoli, 75004 Paris', NULL, '2025-11-01 13:30:00', NULL, NULL),
(8, 'hugo.roux@email.com', '["ROLE_USER"]', '$2y$10$XWiwho6.Gwf1NdMqquw/lu0v/fRJW.RRdK2CunK7wYmlt15rN0/xO', 'Roux', 'Hugo', '0678901234', '89 Rue de la République, 69002 Lyon', NULL, '2025-11-15 17:00:00', NULL, NULL);

-- =====================================================
-- TABLE: product
-- =====================================================
INSERT INTO `product` (`id`, `nom`, `description`, `prix`, `image`, `categorie`, `stock`, `created_at`, `updated_at`, `disponible`) VALUES
-- Entrées
(1, 'Salade César', 'Salade romaine, poulet grillé, parmesan, croûtons et sauce César maison', '12.50', 'salade-cesar.jpg', 'Entrées', 50, '2025-01-01 10:00:00', NULL, 1),
(2, 'Soupe à l''Oignon', 'Soupe à l''oignon gratinée au fromage, servie avec croûtons', '9.90', 'soupe-oignon.jpg', 'Entrées', 40, '2025-01-01 10:00:00', NULL, 1),
(3, 'Carpaccio de Bœuf', 'Fines tranches de bœuf cru, roquette, parmesan et huile d''olive', '14.90', 'carpaccio-boeuf.jpg', 'Entrées', 30, '2025-01-01 10:00:00', NULL, 1),
(4, 'Foie Gras Maison', 'Foie gras de canard mi-cuit, chutney de figues et pain brioché', '18.50', 'foie-gras.jpg', 'Entrées', 25, '2025-01-01 10:00:00', NULL, 1),
(5, 'Tartare de Saumon', 'Saumon frais, avocat, citron vert et ciboulette', '15.90', 'tartare-saumon.jpg', 'Entrées', 35, '2025-01-01 10:00:00', NULL, 1),

-- Plats principaux
(6, 'Entrecôte Grillée', 'Entrecôte de bœuf 300g, sauce au poivre, frites maison', '28.90', 'entrecote.jpg', 'Plats', 45, '2025-01-01 10:00:00', NULL, 1),
(7, 'Filet de Dorade', 'Filet de dorade royale, légumes de saison et beurre blanc', '24.50', 'dorade.jpg', 'Plats', 35, '2025-01-01 10:00:00', NULL, 1),
(8, 'Risotto aux Champignons', 'Risotto crémeux aux cèpes et parmesan, truffe noire', '22.90', 'risotto.jpg', 'Plats', 40, '2025-01-01 10:00:00', NULL, 1),
(9, 'Magret de Canard', 'Magret de canard rôti, sauce aux cerises, purée maison', '26.50', 'magret.jpg', 'Plats', 30, '2025-01-01 10:00:00', NULL, 1),
(10, 'Poulet Fermier Rôti', 'Demi-poulet fermier rôti aux herbes, pommes grenailles', '19.90', 'poulet-roti.jpg', 'Plats', 50, '2025-01-01 10:00:00', NULL, 1),
(11, 'Burger Gourmet', 'Steak haché Angus, cheddar affiné, bacon, sauce maison', '18.50', 'burger.jpg', 'Plats', 60, '2025-01-01 10:00:00', NULL, 1),
(12, 'Pâtes Carbonara', 'Tagliatelles fraîches, guanciale, œuf bio, pecorino', '16.90', 'carbonara.jpg', 'Plats', 55, '2025-01-01 10:00:00', NULL, 1),

-- Desserts
(13, 'Tiramisu', 'Tiramisu traditionnel au mascarpone et café', '8.90', 'tiramisu.jpg', 'Desserts', 40, '2025-01-01 10:00:00', NULL, 1),
(14, 'Crème Brûlée', 'Crème brûlée à la vanille de Madagascar', '7.50', 'creme-brulee.jpg', 'Desserts', 45, '2025-01-01 10:00:00', NULL, 1),
(15, 'Fondant au Chocolat', 'Fondant au chocolat noir 70%, cœur coulant', '9.50', 'fondant-chocolat.jpg', 'Desserts', 35, '2025-01-01 10:00:00', NULL, 1),
(16, 'Tarte Tatin', 'Tarte Tatin aux pommes caramélisées, crème fraîche', '8.50', 'tarte-tatin.jpg', 'Desserts', 30, '2025-01-01 10:00:00', NULL, 1),
(17, 'Profiteroles', 'Choux garnis de glace vanille, sauce chocolat chaud', '9.90', 'profiteroles.jpg', 'Desserts', 40, '2025-01-01 10:00:00', NULL, 1),

-- Boissons
(18, 'Coca-Cola', 'Coca-Cola 33cl', '3.50', 'coca.jpg', 'Boissons', 100, '2025-01-01 10:00:00', NULL, 1),
(19, 'Eau Minérale Evian', 'Bouteille d''eau minérale Evian 50cl', '4.00', 'evian.jpg', 'Boissons', 100, '2025-01-01 10:00:00', NULL, 1),
(20, 'Vin Rouge Bordeaux', 'Verre de vin rouge AOC Bordeaux 15cl', '6.50', 'vin-rouge.jpg', 'Boissons', 80, '2025-01-01 10:00:00', NULL, 1),
(21, 'Café Espresso', 'Café espresso italien', '2.50', 'espresso.jpg', 'Boissons', 150, '2025-01-01 10:00:00', NULL, 1),
(22, 'Thé Vert Bio', 'Thé vert bio du Japon', '3.50', 'the-vert.jpg', 'Boissons', 80, '2025-01-01 10:00:00', NULL, 1);

-- =====================================================
-- TABLE: order (commandes)
-- =====================================================
INSERT INTO `order` (`id`, `user_id`, `numero_commande`, `montant_total`, `statut`, `adresse_livraison`, `commentaire`, `created_at`, `validated_at`) VALUES
(1, 2, 'CMD20251201-001', '65.30', 'livree', '45 Avenue des Champs-Élysées, 75008 Paris', 'Sonner 2 fois', '2025-12-01 12:30:00', '2025-12-01 14:00:00'),
(2, 3, 'CMD20251205-002', '48.80', 'livree', '12 Rue de la Paix, 75002 Paris', NULL, '2025-12-05 19:15:00', '2025-12-05 20:30:00'),
(3, 4, 'CMD20251210-003', '87.40', 'validee', '78 Boulevard Haussmann, 75009 Paris', 'Livraison avant 20h SVP', '2025-12-10 18:00:00', '2025-12-10 18:30:00'),
(4, 5, 'CMD20251215-004', '35.90', 'en_cours', '34 Rue du Faubourg Saint-Honoré, 75008 Paris', NULL, '2025-12-15 13:00:00', NULL),
(5, 2, 'CMD20251220-005', '112.70', 'livree', '45 Avenue des Champs-Élysées, 75008 Paris', 'Repas pour 4 personnes', '2025-12-20 19:30:00', '2025-12-20 21:00:00'),
(6, 6, 'CMD20251225-006', '54.40', 'validee', '56 Avenue Montaigne, 75008 Paris', 'Commande de Noël', '2025-12-25 12:00:00', '2025-12-25 12:30:00'),
(7, 7, 'CMD20251228-007', '29.40', 'annulee', '23 Rue de Rivoli, 75004 Paris', 'Annulé - changement de plans', '2025-12-28 20:00:00', NULL),
(8, 8, 'CMD20260101-008', '76.80', 'en_cours', '89 Rue de la République, 69002 Lyon', 'Repas du Nouvel An', '2026-01-01 20:00:00', NULL),
(9, 3, 'CMD20260102-009', '41.30', 'validee', '12 Rue de la Paix, 75002 Paris', NULL, '2026-01-02 12:45:00', '2026-01-02 13:00:00'),
(10, 5, 'CMD20260103-010', '93.20', 'en_cours', '34 Rue du Faubourg Saint-Honoré, 75008 Paris', 'Anniversaire - besoin de bougies', '2026-01-03 18:30:00', NULL);

-- =====================================================
-- TABLE: order_item (articles de commande)
-- =====================================================
INSERT INTO `order_item` (`id`, `commande_id`, `product_id`, `quantite`, `prix_unitaire`) VALUES
-- Commande 1
(1, 1, 1, 1, '12.50'),
(2, 1, 6, 1, '28.90'),
(3, 1, 13, 1, '8.90'),
(4, 1, 18, 2, '3.50'),
(5, 1, 21, 2, '2.50'),

-- Commande 2
(6, 2, 3, 1, '14.90'),
(7, 2, 10, 1, '19.90'),
(8, 2, 14, 1, '7.50'),
(9, 2, 19, 1, '4.00'),
(10, 2, 21, 1, '2.50'),

-- Commande 3
(11, 3, 4, 2, '18.50'),
(12, 3, 9, 2, '26.50'),
(13, 3, 15, 1, '9.50'),
(14, 3, 20, 2, '6.50'),

-- Commande 4
(15, 4, 2, 1, '9.90'),
(16, 4, 12, 1, '16.90'),
(17, 4, 17, 1, '9.90'),

-- Commande 5
(18, 5, 5, 2, '15.90'),
(19, 5, 7, 2, '24.50'),
(20, 5, 16, 2, '8.50'),
(21, 5, 20, 3, '6.50'),

-- Commande 6
(22, 6, 1, 2, '12.50'),
(23, 6, 8, 1, '22.90'),
(24, 6, 19, 1, '4.00'),
(25, 6, 21, 1, '2.50'),

-- Commande 7 (annulée)
(26, 7, 11, 1, '18.50'),
(27, 7, 14, 1, '7.50'),
(28, 7, 18, 1, '3.50'),

-- Commande 8
(29, 8, 4, 1, '18.50'),
(30, 8, 6, 1, '28.90'),
(31, 8, 9, 1, '26.50'),
(32, 8, 21, 1, '2.50'),

-- Commande 9
(33, 9, 2, 1, '9.90'),
(34, 9, 11, 1, '18.50'),
(35, 9, 13, 1, '8.90'),
(36, 9, 19, 1, '4.00'),

-- Commande 10
(37, 10, 3, 1, '14.90'),
(38, 10, 9, 2, '26.50'),
(39, 10, 15, 2, '9.50'),
(40, 10, 22, 2, '3.50');

-- =====================================================
-- TABLE: reservation
-- =====================================================
INSERT INTO `reservation` (`id`, `user_id`, `nom`, `prenom`, `telephone`, `email`, `date_reservation`, `heure_reservation`, `nombre_personnes`, `statut`, `commentaire`, `created_at`) VALUES
(1, 2, 'Dupont', 'Jean', '0612345678', 'jean.dupont@email.com', '2026-01-10', '19:30:00', 4, 'confirmee', 'Table près de la fenêtre si possible', '2025-12-20 10:00:00'),
(2, 3, 'Martin', 'Marie', '0623456789', 'marie.martin@email.com', '2026-01-12', '20:00:00', 2, 'confirmee', 'Anniversaire de mariage', '2025-12-22 14:30:00'),
(3, 4, 'Bernard', 'Pierre', '0634567890', 'pierre.bernard@email.com', '2026-01-15', '12:30:00', 6, 'en_attente', 'Déjeuner d''affaires', '2025-12-28 09:00:00'),
(4, 5, 'Petit', 'Sophie', '0645678901', 'sophie.petit@email.com', '2026-01-08', '19:00:00', 3, 'confirmee', NULL, '2025-12-25 16:00:00'),
(5, NULL, 'Lefevre', 'Thomas', '0698765432', 'thomas.lefevre@email.com', '2026-01-20', '20:30:00', 8, 'en_attente', 'Fête d''anniversaire - besoin d''un gâteau', '2026-01-02 11:00:00'),
(6, 6, 'Moreau', 'Lucas', '0656789012', 'lucas.moreau@email.com', '2026-01-05', '19:30:00', 2, 'terminee', 'Saint-Valentin anticipée', '2025-12-30 18:00:00'),
(7, 7, 'Leroy', 'Emma', '0667890123', 'emma.leroy@email.com', '2026-01-18', '13:00:00', 4, 'confirmee', 'Régime végétarien pour 2 personnes', '2026-01-01 10:30:00'),
(8, NULL, 'Girard', 'Antoine', '0611223344', 'antoine.girard@email.com', '2026-01-25', '20:00:00', 5, 'en_attente', NULL, '2026-01-03 09:15:00'),
(9, 8, 'Roux', 'Hugo', '0678901234', 'hugo.roux@email.com', '2026-01-22', '19:00:00', 2, 'confirmee', 'Allergie aux fruits de mer', '2026-01-02 20:00:00'),
(10, 2, 'Dupont', 'Jean', '0612345678', 'jean.dupont@email.com', '2026-02-14', '20:00:00', 2, 'en_attente', 'Réservation Saint-Valentin', '2026-01-03 08:00:00'),
(11, NULL, 'Blanc', 'Camille', '0655443322', 'camille.blanc@email.com', '2026-01-06', '12:00:00', 10, 'annulee', 'Annulé pour cause de maladie', '2025-12-28 15:00:00'),
(12, 3, 'Martin', 'Marie', '0623456789', 'marie.martin@email.com', '2026-01-30', '19:30:00', 4, 'en_attente', 'Menu enfant pour 2', '2026-01-03 12:00:00');

-- =====================================================
-- Réinitialiser les auto-increment
-- =====================================================
ALTER TABLE `user` AUTO_INCREMENT = 9;
ALTER TABLE `product` AUTO_INCREMENT = 23;
ALTER TABLE `order` AUTO_INCREMENT = 11;
ALTER TABLE `order_item` AUTO_INCREMENT = 41;
ALTER TABLE `reservation` AUTO_INCREMENT = 13;

-- =====================================================
-- RÉSUMÉ DES DONNÉES INSÉRÉES
-- =====================================================
-- user: 8 utilisateurs (1 admin + 7 clients)
-- product: 22 produits (5 entrées, 7 plats, 5 desserts, 5 boissons)
-- order: 10 commandes
-- order_item: 40 articles de commande
-- reservation: 12 réservations
-- 
-- Identifiants de connexion:
-- Admin: admin@restaurant.com / password123
-- Clients: [email]@email.com / password123
-- =====================================================

SELECT 'Base de données remplie avec succès!' AS Message;
