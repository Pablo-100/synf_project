-- Base de données pour le projet Symfony
-- Créer la base de données
CREATE DATABASE IF NOT EXISTS synf_project CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE synf_project;

-- Table User
CREATE TABLE IF NOT EXISTS `user` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `email` VARCHAR(180) NOT NULL,
  `roles` JSON NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `nom` VARCHAR(100) NOT NULL,
  `prenom` VARCHAR(100) NOT NULL,
  `telephone` VARCHAR(20) DEFAULT NULL,
  `adresse` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table Product
CREATE TABLE IF NOT EXISTS `product` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `nom` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `prix` DECIMAL(10, 2) NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `categorie` VARCHAR(100) NOT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `disponible` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table Reservation
CREATE TABLE IF NOT EXISTS `reservation` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `user_id` INT NOT NULL,
  `date_reservation` DATE NOT NULL,
  `heure_reservation` TIME NOT NULL,
  `nombre_personnes` INT NOT NULL,
  `statut` VARCHAR(50) NOT NULL DEFAULT 'en_attente',
  `commentaire` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_42C84955A76ED395` (`user_id`),
  CONSTRAINT `FK_42C84955A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table Order
CREATE TABLE IF NOT EXISTS `order` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `user_id` INT NOT NULL,
  `numero_commande` VARCHAR(50) NOT NULL,
  `montant_total` DECIMAL(10, 2) NOT NULL DEFAULT '0.00',
  `statut` VARCHAR(50) NOT NULL DEFAULT 'en_cours',
  `adresse_livraison` TEXT DEFAULT NULL,
  `commentaire` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `validated_at` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F5299398ABE530DA` (`numero_commande`),
  KEY `IDX_F5299398A76ED395` (`user_id`),
  CONSTRAINT `FK_F5299398A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table OrderItem
CREATE TABLE IF NOT EXISTS `order_item` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `commande_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantite` INT NOT NULL DEFAULT 1,
  `prix_unitaire` DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_52EA1F0982EA2E54` (`commande_id`),
  KEY `IDX_52EA1F094584665A` (`product_id`),
  CONSTRAINT `FK_52EA1F0982EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `order` (`id`),
  CONSTRAINT `FK_52EA1F094584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Données de test

-- Insérer un utilisateur admin (mot de passe: admin123)
INSERT INTO `user` (`email`, `roles`, `password`, `nom`, `prenom`, `telephone`, `adresse`, `created_at`) VALUES
('admin@example.com', '["ROLE_ADMIN"]', '$2y$13$QJKvJupkZfP8G1FX5VBfEOuRwLDwCGQR0DZBJy4l8YjWzQ.KG9ryu', 'Admin', 'Site', '0123456789', '123 Rue Example, Paris', NOW()),
('user@example.com', '["ROLE_USER"]', '$2y$13$QJKvJupkZfP8G1FX5VBfEOuRwLDwCGQR0DZBJy4l8YjWzQ.KG9ryu', 'Utilisateur', 'Test', '0987654321', '456 Avenue Test, Lyon', NOW());

-- Insérer des produits d'exemple
INSERT INTO `product` (`nom`, `description`, `prix`, `categorie`, `stock`, `disponible`, `created_at`) VALUES
('Pizza Margherita', 'Pizza traditionnelle italienne avec sauce tomate, mozzarella et basilic frais', 12.50, 'plat', 50, 1, NOW()),
('Pizza Quattro Formaggi', 'Pizza aux quatre fromages : mozzarella, gorgonzola, parmesan et chèvre', 14.00, 'plat', 40, 1, NOW()),
('Spaghetti Carbonara', 'Pâtes italiennes avec sauce crémeuse, lardons et parmesan', 11.00, 'plat', 60, 1, NOW()),
('Lasagnes Bolognaise', 'Lasagnes maison avec sauce bolognaise et béchamel', 13.50, 'plat', 35, 1, NOW()),
('Salade César', 'Salade romaine, poulet grillé, croûtons, parmesan et sauce césar', 9.50, 'plat', 45, 1, NOW()),
('Coca-Cola 33cl', 'Boisson gazeuse rafraîchissante', 2.50, 'boisson', 100, 1, NOW()),
('Sprite 33cl', 'Boisson gazeuse au citron', 2.50, 'boisson', 100, 1, NOW()),
('Jus d\'Orange', 'Jus d\'orange frais pressé', 3.50, 'boisson', 80, 1, NOW()),
('Eau Minérale 50cl', 'Eau minérale naturelle', 2.00, 'boisson', 150, 1, NOW()),
('Vin Rouge (verre)', 'Verre de vin rouge de la maison', 4.50, 'boisson', 50, 1, NOW()),
('Tiramisu', 'Dessert italien au mascarpone et café', 6.00, 'dessert', 30, 1, NOW()),
('Panna Cotta', 'Crème italienne aux fruits rouges', 5.50, 'dessert', 25, 1, NOW()),
('Profiteroles', 'Choux à la crème avec sauce chocolat', 6.50, 'dessert', 20, 1, NOW()),
('Tarte au Citron', 'Tarte meringuée au citron', 5.50, 'dessert', 25, 1, NOW()),
('Salade de Fruits', 'Mélange de fruits frais de saison', 5.00, 'dessert', 40, 1, NOW());

-- Insérer une réservation d'exemple
INSERT INTO `reservation` (`user_id`, `date_reservation`, `heure_reservation`, `nombre_personnes`, `statut`, `commentaire`, `created_at`)
VALUES (2, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '19:30:00', 4, 'en_attente', 'Table près de la fenêtre si possible', NOW());

-- Insérer une commande d'exemple
INSERT INTO `order` (`user_id`, `numero_commande`, `montant_total`, `statut`, `adresse_livraison`, `created_at`)
VALUES (2, CONCAT('CMD', DATE_FORMAT(NOW(), '%Y%m%d'), '-', FLOOR(RAND() * 1000000)), 34.50, 'en_cours', '456 Avenue Test, 69000 Lyon', NOW());

-- Récupérer l'ID de la commande créée et ajouter des items
SET @order_id = LAST_INSERT_ID();

INSERT INTO `order_item` (`commande_id`, `product_id`, `quantite`, `prix_unitaire`) VALUES
(@order_id, 1, 2, 12.50),
(@order_id, 6, 2, 2.50),
(@order_id, 11, 2, 6.00);

-- Table doctrine_migration_versions (pour Doctrine Migrations)
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` VARCHAR(191) NOT NULL,
  `executed_at` DATETIME DEFAULT NULL,
  `execution_time` INT DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Afficher un message de succès
SELECT 'Base de données créée avec succès!' AS message;
SELECT CONCAT('Utilisateur admin : admin@example.com / Mot de passe : admin123') AS credentials;
SELECT CONCAT('Utilisateur test : user@example.com / Mot de passe : admin123') AS credentials;
