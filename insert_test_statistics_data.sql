-- Script pour ajouter des données de test pour les statistiques

-- Assurez-vous d'avoir un utilisateur de test (changez l'ID selon votre base)
-- Récupérer l'ID de votre utilisateur actuel avec : SELECT id, email FROM user;

-- Exemple : Si votre utilisateur a l'ID 1, créons quelques commandes

-- Commandes de test (ajustez user_id selon votre utilisateur)
INSERT INTO `order` (user_id, numeroCommande, montantTotal, statut, createdAt, validatedAt) VALUES
(1, 'CMD20251101-001', 45.50, 'livree', '2025-11-01 14:30:00', '2025-11-01 15:00:00'),
(1, 'CMD20251015-002', 32.00, 'validee', '2025-10-15 12:00:00', '2025-10-15 12:30:00'),
(1, 'CMD20251020-003', 67.80, 'livree', '2025-10-20 18:45:00', '2025-10-20 19:00:00'),
(1, 'CMD20250915-004', 25.50, 'livree', '2025-09-15 10:20:00', '2025-09-15 10:45:00'),
(1, 'CMD20250920-005', 89.00, 'validee', '2025-09-20 16:00:00', '2025-09-20 16:30:00');

-- Réservations de test (ajustez user_id selon votre utilisateur)
INSERT INTO reservation (user_id, nom, prenom, telephone, email, dateReservation, heureReservation, nombrePersonnes, statut, createdAt) VALUES
(1, 'Dupont', 'Jean', '0612345678', 'jean.dupont@email.com', '2025-12-05', '19:30:00', 4, 'confirmee', '2025-11-20 10:00:00'),
(1, 'Dupont', 'Jean', '0612345678', 'jean.dupont@email.com', '2025-11-10', '20:00:00', 2, 'terminee', '2025-10-25 14:00:00'),
(1, 'Dupont', 'Jean', '0612345678', 'jean.dupont@email.com', '2025-10-15', '19:00:00', 6, 'terminee', '2025-09-30 11:00:00'),
(1, 'Dupont', 'Jean', '0612345678', 'jean.dupont@email.com', '2025-09-20', '18:30:00', 3, 'terminee', '2025-09-05 16:00:00');

-- Vérifier les données insérées
SELECT 'Commandes insérées:' as info, COUNT(*) as count FROM `order` WHERE user_id = 1
UNION ALL
SELECT 'Réservations insérées:', COUNT(*) FROM reservation WHERE user_id = 1;
