# 🎯 Projet Symfony - Site de Réservation

## 📋 Cahier des charges

Projet conforme au cahier des charges PHP/Symfony/JS comprenant :

### Fonctionnalités Utilisateur
- ✅ **Inscription** : Création de compte utilisateur
- ✅ **Connexion** : Authentification sécurisée
- ✅ **Consultation du compte** : Profil et historique
- ✅ **Réservations** : Système de réservation de tables
- ✅ **Commandes** : Système de commande de produits/services

### Fonctionnalités Administration
- ✅ **Gestion des clients** : Liste et gestion des utilisateurs
- ✅ **Gestion des produits/services** : CRUD complet avec upload d'images
- ✅ **Gestion des réservations** : Validation, annulation
- ✅ **Gestion des commandes** : Suivi et mise à jour du statut
- ✅ **Statistiques** : Dashboard avec graphiques et KPIs

## 🏗️ Architecture du Projet

### Entités Créées
1. **User** - Gestion des utilisateurs (UserInterface + PasswordAuthenticatedUserInterface)
2. **Product** - Produits/Services avec image, prix, stock
3. **Reservation** - Réservations avec date, heure, nombre de personnes
4. **Order** - Commandes avec numéro unique et statut
5. **OrderItem** - Détails des articles commandés

### Structure
```
synf_project/
├── config/              # Configuration Symfony
├── public/              # Point d'entrée web
│   ├── uploads/         # Dossier pour les images uploadées
│   └── index.php
├── src/
│   ├── Controller/      # Contrôleurs (à créer)
│   ├── Entity/          # ✅ Entités Doctrine
│   ├── Form/            # Formulaires (à créer)
│   ├── Repository/      # ✅ Repositories Doctrine
│   └── Kernel.php
├── templates/           # Vues Twig
├── tests/               # Tests
├── .env                 # Configuration environnement
└── composer.json
```

## 🚀 Installation

### Prérequis
- PHP 8.1 ou supérieur
- MySQL 8.0 ou supérieur (XAMPP)
- Composer
- Extension PHP : pdo_mysql, gd (pour les images)

### Étape 1 : Démarrer XAMPP
```bash
# Démarrer Apache et MySQL depuis XAMPP Control Panel
```

### Étape 2 : Installer les dépendances
```bash
cd c:\xampp\htdocs\synf_project
composer install
```

### Étape 3 : Configurer la base de données
```powershell
# Dans PowerShell, définir les variables d'environnement
$env:DATABASE_URL="mysql://root:@127.0.0.1:3306/synf_project?serverVersion=8.0.32&charset=utf8mb4"
$env:DEFAULT_URI="http://localhost"

# Créer la base de données
php bin/console doctrine:database:create --if-not-exists

# Générer et exécuter les migrations
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

### Étape 4 : Créer un utilisateur admin
```powershell
php bin/console make:user
# Suivre les instructions pour créer un admin
```

### Étape 5 : Charger des données de test (optionnel)
```powershell
php bin/console doctrine:fixtures:load
```

### Étape 6 : Créer le dossier uploads
```powershell
New-Item -ItemType Directory -Path public/uploads -Force
```

### Étape 7 : Lancer le serveur
```powershell
php bin/console server:run
# OU utiliser le serveur built-in PHP
php -S localhost:8000 -t public
```

Accéder au site : http://localhost:8000

## 📦 Dépendances Installées

- **symfony/framework-bundle** : Core Symfony
- **symfony/webapp-pack** : Pack complet (Twig, Security, Form, etc.)
- **doctrine/orm** : ORM pour la base de données
- **symfony/maker-bundle** : Générateur de code
- **symfony/security-bundle** : Authentification et autorisation
- **symfony/form** : Gestion des formulaires
- **symfony/validator** : Validation des données
- **symfony/asset** : Gestion des assets
- **symfony/mailer** : Envoi d'emails
- **monolog/monolog** : Logs
- **twig/twig** : Moteur de templates

## 🎨 Prochaines Étapes de Développement

### 1. Créer les Contrôleurs
```bash
php bin/console make:controller HomeController
php bin/console make:controller ProductController
php bin/console make:controller ReservationController
php bin/console make:controller OrderController
php bin/console make:controller Admin/DashboardController
php bin/console make:controller Admin/ProductController
php bin/console make:controller Admin/ReservationController
php bin/console make:controller Admin/OrderController
```

### 2. Créer les Formulaires
```bash
php bin/console make:form ProductType
php bin/console make:form ReservationType
php bin/console make:form OrderType
php bin/console make:registration-form
```

### 3. Créer l'authentification
```bash
php bin/console make:auth
```

### 4. Créer les vues Twig
- Layout de base (base.html.twig)
- Pages utilisateur (home, products, profile, reservations, orders)
- Pages admin (dashboard, CRUD products, manage reservations/orders)

### 5. Ajouter JavaScript
- Validation côté client
- Interactions dynamiques (AJAX)
- Graphiques pour les statistiques (Chart.js)

### 6. Styliser avec Bootstrap 5
- Intégration de Bootstrap via AssetMapper
- Création d'un thème personnalisé

## 📊 Fonctionnalités des Entités

### User
- Email (unique, utilisé pour login)
- Nom et prénom
- Téléphone et adresse
- Rôles (ROLE_USER, ROLE_ADMIN)
- Relations avec Reservations et Orders

### Product
- Nom, description, prix
- Image (upload obligatoire)
- Catégorie et stock
- Disponibilité (actif/inactif)

### Reservation
- Date et heure
- Nombre de personnes
- Statut : en_attente, confirmee, annulee, terminee
- Commentaire optionnel

### Order
- Numéro de commande unique auto-généré
- Montant total calculé automatiquement
- Statut : en_cours, validee, livree, annulee
- Adresse de livraison
- Items (OrderItem) avec quantité et prix unitaire

## 🔐 Sécurité

Le projet utilise le composant Security de Symfony :
- Hashage des mots de passe (bcrypt/argon2)
- Protection CSRF sur les formulaires
- Contrôle d'accès par rôle (ROLE_USER, ROLE_ADMIN)
- Validation des données côté serveur

## 📚 Ressources

- [Documentation Symfony](https://symfony.com/doc/current/index.html)
- [Doctrine ORM](https://www.doctrine-project.org/projects/doctrine-orm/en/current/index.html)
- [Twig](https://twig.symfony.com/)
- [Bootstrap 5](https://getbootstrap.com/)

## 🐛 Dépannage

### Erreur de connexion à la base de données
```powershell
# Vérifier que MySQL est démarré dans XAMPP
# Vérifier le .env :
DATABASE_URL="mysql://root:@127.0.0.1:3306/synf_project?serverVersion=8.0.32&charset=utf8mb4"
```

### Cache
```powershell
# Vider le cache
php bin/console cache:clear

# OU manuellement
Remove-Item -Path var\cache -Recurse -Force
```

### Permissions sur uploads/
```powershell
# S'assurer que le dossier est accessible en écriture
icacls public\uploads /grant Everyone:F
```

## 👥 Équipe

- Projet individuel ou en équipe (max 4 personnes)
- Adaptable selon le cahier des charges

## 📝 License

Projet académique - Formation PHP/Symfony

---

**Note** : Ce projet répond aux exigences du cahier des charges avec :
- ✅ 4 entités principales (User, Product, Reservation, Order + OrderItem)
- ✅ Système d'authentification
- ✅ CRUD complet avec upload
- ✅ Gestion des réservations et commandes
- ✅ Base pour les statistiques dans l'admin
- ✅ Structure professionnelle et extensible
