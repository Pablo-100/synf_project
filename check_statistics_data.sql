-- Vérifier les données pour les statistiques

-- Compter les commandes par utilisateur
SELECT 
    u.id, 
    u.email, 
    u.prenom, 
    u.nom,
    COUNT(o.id) as nombre_commandes,
    SUM(o.montantTotal) as total_depense
FROM user u
LEFT JOIN `order` o ON o.user_id = u.id
GROUP BY u.id, u.email, u.prenom, u.nom
ORDER BY nombre_commandes DESC;

-- Compter les réservations par utilisateur
SELECT 
    u.id, 
    u.email, 
    u.prenom, 
    u.nom,
    COUNT(r.id) as nombre_reservations
FROM user u
LEFT JOIN reservation r ON r.user_id = u.id
GROUP BY u.id, u.email, u.prenom, u.nom
ORDER BY nombre_reservations DESC;

-- Statistiques globales
SELECT 
    (SELECT COUNT(*) FROM `order`) as total_commandes,
    (SELECT COUNT(*) FROM reservation) as total_reservations,
    (SELECT COUNT(*) FROM user) as total_utilisateurs,
    (SELECT SUM(montantTotal) FROM `order` WHERE statut IN ('validee', 'livree')) as total_revenus;

-- Commandes par mois (derniers 12 mois)
SELECT 
    YEAR(createdAt) as annee,
    MONTH(createdAt) as mois,
    COUNT(*) as nombre,
    SUM(montantTotal) as revenus
FROM `order`
GROUP BY YEAR(createdAt), MONTH(createdAt)
ORDER BY annee DESC, mois DESC
LIMIT 12;

-- Réservations par mois (derniers 12 mois)
SELECT 
    YEAR(dateReservation) as annee,
    MONTH(dateReservation) as mois,
    COUNT(*) as nombre
FROM reservation
GROUP BY YEAR(dateReservation), MONTH(dateReservation)
ORDER BY annee DESC, mois DESC
LIMIT 12;
