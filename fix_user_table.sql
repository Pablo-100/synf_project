-- Fix for error #1932: Table 'synf_project.user' doesn't exist in engine
-- Run this in phpMyAdmin SQL tab

-- Step 1: Drop the corrupted table
DROP TABLE IF EXISTS `user`;

-- Step 2: Recreate the user table
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
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
