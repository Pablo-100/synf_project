# 📚 Documentation du Projet Symfony

## 🗂️ Index de la Documentation

### 🚀 Pour Démarrer
- **[QUICKSTART.md](QUICKSTART.md)** - Guide d'installation rapide (5 minutes)
  - Installation des dépendances
  - Création de la base de données
  - Lancement du serveur
  - Dépannage

### 📖 Documentation Complète
- **[README.md](README.md)** - Documentation complète du projet
  - Architecture
  - Cahier des charges
  - Structure détaillée
  - Prochaines étapes de développement
  - Ressources et support

### 📊 Récapitulatif Technique
- **[PROJECT_SUMMARY.md](PROJECT_SUMMARY.md)** - Vue d'ensemble du projet
  - État d'avancement
  - Modèle de données avec schéma
  - Technologies utilisées
  - Points forts
  - Évaluation par rapport au cahier des charges

### 🗄️ Base de Données
- **[database.sql](database.sql)** - Script SQL complet
  - Structure des tables
  - Données de test
  - Utilisateurs par défaut (admin/user)
  - Produits d'exemple

## 📁 Structure du Code

### Fichiers Principaux Créés

#### 🎮 Contrôleurs
```
src/Controller/
├── HomeController.php       # Page d'accueil, À propos, Contact
└── ProductController.php    # Liste, détails, recherche, catégories
```

#### 🗃️ Entités (Modèles)
```
src/Entity/
├── User.php                 # Utilisateurs et authentification
├── Product.php              # Produits/Services
├── Reservation.php          # Réservations de tables
├── Order.php                # Commandes
└── OrderItem.php            # Lignes de commandes
```

#### 📚 Repositories (Accès données)
```
src/Repository/
├── UserRepository.php           # + recherche par rôle
├── ProductRepository.php        # + filtres et recherche
├── ReservationRepository.php    # + statistiques
├── OrderRepository.php          # + calcul CA
└── OrderItemRepository.php      # + top produits
```

#### 🎨 Templates (Vues)
```
templates/
├── base.html.twig           # Layout principal Bootstrap 5
├── home/
│   ├── index.html.twig      # Page d'accueil
│   ├── about.html.twig      # À propos
│   └── contact.html.twig    # Contact
└── product/
    ├── index.html.twig      # Liste des produits
    ├── show.html.twig       # Détails d'un produit
    ├── category.html.twig   # Filtrage par catégorie
    └── search.html.twig     # Résultats de recherche
```

## 🎯 Fonctionnalités Implémentées

### ✅ Côté Utilisateur
- [x] Page d'accueil attractive
- [x] Consultation des produits
- [x] Recherche de produits
- [x] Filtrage par catégorie
- [x] Détails des produits
- [x] Système de navigation
- [x] Footer informatif
- [ ] Inscription (make:auth à exécuter)
- [ ] Connexion (make:auth à exécuter)
- [ ] Profil utilisateur (à créer)
- [ ] Gestion des réservations (à créer)
- [ ] Panier et commandes (à créer)

### ✅ Côté Administration
- [x] Modèle de données complet
- [x] Repositories avec statistiques
- [ ] Dashboard admin (à créer)
- [ ] CRUD Produits avec upload (à créer)
- [ ] Gestion réservations (à créer)
- [ ] Gestion commandes (à créer)
- [ ] Statistiques visuelles (à créer)

## 🛠️ Commandes Utiles

### Installation
```powershell
composer install
```

### Base de données
```powershell
# Définir les variables d'environnement
$env:DATABASE_URL="mysql://root:@127.0.0.1:3306/synf_project?serverVersion=8.0.32&charset=utf8mb4"
$env:DEFAULT_URI="http://localhost"

# Créer la base
php bin/console doctrine:database:create --if-not-exists

# Ou importer database.sql via phpMyAdmin
```

### Développement
```powershell
# Lancer le serveur
php -S localhost:8000 -t public

# Vider le cache
php bin/console cache:clear

# Créer un contrôleur
php bin/console make:controller

# Créer une entité
php bin/console make:entity

# Créer un formulaire
php bin/console make:form
```

## 📊 Statistiques du Projet

- **Entités** : 5 (User, Product, Reservation, Order, OrderItem)
- **Contrôleurs** : 2 (Home, Product)
- **Repositories** : 5 (tous avec méthodes personnalisées)
- **Templates** : 9 (base + 8 pages)
- **Lignes de code** : ~2000+
- **Temps de développement** : Base solide créée

## 🎓 Pour l'Évaluation

### Documents à Présenter
1. **Code Source** : Entités, Contrôleurs, Repositories
2. **Base de Données** : Schéma relationnel (voir PROJECT_SUMMARY.md)
3. **Interface** : Démo du site fonctionnel
4. **Documentation** : README + QUICKSTART

### Démonstration Suggérée
1. Montrer la page d'accueil
2. Navigation dans les produits
3. Recherche et filtres
4. Explication du modèle de données
5. Code des entités et repositories
6. Expliquer les statistiques

### Points Forts à Mentionner
- Architecture professionnelle
- 5 entités (dépasse le minimum)
- Relations complexes bien gérées
- Repositories optimisés
- Interface moderne et responsive
- Code documenté
- Prêt pour extension

## 🔗 Liens Utiles

### Documentation Officielle
- [Symfony](https://symfony.com/doc/current/index.html)
- [Doctrine](https://www.doctrine-project.org/)
- [Twig](https://twig.symfony.com/)
- [Bootstrap 5](https://getbootstrap.com/)

### Ressources d'Apprentissage
- [SymfonyCasts](https://symfonycasts.com/)
- [Grafikart - Symfony](https://grafikart.fr/formations/symfony)

## 📧 Support

Pour toute question ou problème :
1. Consulter le QUICKSTART.md pour l'installation
2. Vérifier les logs dans `var/log/dev.log`
3. Consulter la documentation Symfony officielle

---

**Créé le** : 11 octobre 2025  
**Version Symfony** : 7.3  
**PHP** : 8.1+  
**Base de données** : MySQL 8.0

✨ Bon développement !
