-- Ajouter la colonne avatar à la table user
ALTER TABLE `user` ADD COLUMN `avatar` VARCHAR(255) NULL AFTER `adresse`;
