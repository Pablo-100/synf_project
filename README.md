# 🍽️ FreshMarket - Application Web Symfony

> Une application web moderne et sécurisée de gestion de restaurant avec marketplace de produits, réservations et commandes en ligne.

[![Symfony](https://img.shields.io/badge/Symfony-7.x-black?style=for-the-badge&logo=symfony)](https://symfony.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

---

## 🎯 Objectifs du Projet

- Offrir une plateforme simple et moderne pour la gestion de réservations et commandes.
- Implémenter les bonnes pratiques de développement web avec Symfony.
- Servir de base académique pour un projet d’ingénierie en développement web.

---

## 📋 Table des Matières

- [Aperçu](#-aperçu)
- [Fonctionnalités](#-fonctionnalités)
- [Sécurité](#-sécurité)
- [Prérequis](#-prérequis)
- [Installation](#-installation)
- [Utilisation](#-utilisation)
- [Tests](#-tests)
- [Déploiement](#-déploiement)
- [Structure du Projet](#-structure-du-projet)
- [Documentation](#-documentation)
- [Licence](#-licence)

---

## ⚙️ Installation

### Prérequis

- **PHP ≥ 8.1**
- **MySQL ≥ 8.0**
- **Composer**
- Extensions PHP : `pdo_mysql`, `gd`, `intl`

### Étapes

```bash
git clone https://github.com/Pablo-100/synf_project.git
cd synf_project
composer install
````

Modifier la variable `DATABASE_URL` dans `.env` :

```env
DATABASE_URL="mysql://root:@127.0.0.1:3306/synf_project?serverVersion=8.0&charset=utf8mb4"
```

Créer la base et exécuter les migrations :

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

Charger des données de test :

```bash
php bin/console doctrine:fixtures:load
```

Créer un utilisateur admin :

```bash
php bin/console make:user
```

Lancer le serveur :

```bash
symfony server:start
# ou
php -S localhost:8000 -t public
```

---

## 🎨 Aperçu

**FreshMarket** est une application web complète développée avec **Symfony 7** pour gérer :

* 🛒 Marketplace de produits
* 📅 Réservations de tables
* 🛍️ Commandes en ligne
* 👥 Gestion des utilisateurs
* 📊 Dashboard admin

---

## ✨ Fonctionnalités

### � Authentification OAuth 2.0 (NOUVEAU !)

| Fonctionnalité              | Description                                    | Status |
| --------------------------- | ---------------------------------------------- | ------ |
| Connexion Google            | Authentification et création de compte Google  | ✅      |
| Connexion Facebook          | Authentification et création de compte Facebook| ✅      |
| Synchronisation avatars     | Import automatique des photos de profil        | ✅      |
| Gestion intelligente emails | Système de fallback pour comptes sans email    | ✅      |

### 🛍️ Panier Dynamique (NOUVEAU !)

| Fonctionnalité         | Description                               | Status |
| ---------------------- | ----------------------------------------- | ------ |
| Compteur en temps réel | Badge mis à jour automatiquement via AJAX | ✅      |
| Ajout sans rechargement| Mise à jour instantanée du panier         | ✅      |
| Animation du badge     | Effet visuel pulse lors des ajouts        | ✅      |
| Comptage intelligent   | Nombre de produits uniques (pas quantités)| ✅      |

### �👤 Espace Client

| Fonctionnalité          | Description                       | Status |
| ----------------------- | --------------------------------- | ------ |
| Inscription / Connexion | Authentification sécurisée        | ✅      |
| OAuth Google & Facebook | Connexion avec réseaux sociaux    | ✅ NEW |
| Boutique                | Catalogue produits avec images    | ✅      |
| Recherche & Filtres     | Par nom ou catégorie              | ✅      |
| Panier                  | Ajout / suppression / mise à jour | ✅      |
| Panier AJAX             | Mise à jour en temps réel         | ✅ NEW |
| Commandes               | Suivi des commandes               | ✅      |
| Réservations            | Réservation de tables             | ✅      |
| Profil                  | Gestion du profil et avatar       | ✅      |
| Avatars par défaut      | Cercles avec initiales colorées   | ✅ NEW |
| Badges de connexion     | Indicateurs Google/Facebook       | ✅ NEW |

### 👨‍💼 Espace Admin

| Fonctionnalité | Description                | Status |
| -------------- | -------------------------- | ------ |
| Dashboard      | Statistiques temps réel    | ✅      |
| CRUD Produits  | Gestion complète           | ✅      |
| Commandes      | Validation, annulation     | ✅      |
| Réservations   | Confirmation, modification | ✅      |
| Utilisateurs   | Rôles et comptes           | ✅      |

---

## 🔒 Sécurité

*   **Protection XSS Avancée** : Twig auto-escaping + Content Security Policy (CSP).
*   **Sécurité des Headers** : `X-Frame-Options`, `X-XSS-Protection`, `X-Content-Type-Options`.
*   **CSRF Protection** : Activée globalement sur tous les formulaires et actions critiques.
*   **SQL Injection Prevention** : Doctrine ORM + Requêtes préparées systématiques.
*   **Sessions & Cookies** : Sécurisés avec `httpOnly` et `sameSite: lax`.
*   **Système Responsif Fluide** : Utilisation de `clamp()` et variables CSS pour une adaptabilité parfaite (Mobile/Desktop).
*   **Panier par Token** : Stockage optimisé côté client (Cookie) pour réduire la charge DB.

---

## 🧱 Structure du Projet

```
synf_project/
├── config/
│   ├── packages/
│   │   ├── knpu_oauth2_client.yaml  # Config OAuth Google/Facebook
│   │   └── security.yaml             # Authenticators OAuth
│   └── routes/
├── public/
│   ├── index.php
│   └── uploads/
├── src/
│   ├── Controller/
│   │   ├── CartController.php          # Panier avec AJAX
│   │   ├── GoogleOAuthController.php   # OAuth Google
│   │   └── FacebookOAuthController.php # OAuth Facebook
│   ├── Entity/
│   │   └── User.php                    # google_id, facebook_id, avatar
│   ├── Form/
│   ├── Repository/
│   ├── Security/
│   │   ├── GoogleAuthenticator.php     # Authenticator Google
│   │   └── FacebookAuthenticator.php   # Authenticator Facebook
│   ├── Service/
│   ├── Twig/
│   │   └── CartExtension.php           # Fonction cart_count()
│   └── EventSubscriber/
├── templates/
│   ├── security/
│   │   └── login.html.twig             # Boutons OAuth
│   ├── registration/
│   │   └── register.html.twig          # Boutons OAuth
│   ├── profile/
│   │   └── index.html.twig             # Avatars et badges
│   └── base.html.twig                  # Badge panier animé
├── migrations/
│   └── migrate_add_facebook_id.php     # Migration Facebook
├── start.ps1                            # Script de démarrage
├── composer.json
└── README.md
```

---

## ⚙️ Configuration

| Variable               | Description         | Valeur                                     |
| ---------------------- | ------------------- | ------------------------------------------ |
| APP_ENV                | Environnement       | dev                                        |
| APP_DEBUG              | Mode debug          | 1                                          |
| APP_SECRET             | Clé Symfony         | Générée                                    |
| DATABASE_URL           | Connexion DB        | `mysql://root@127.0.0.1:3306/synf_project` |
| GOOGLE_CLIENT_ID       | OAuth Google        | Configuré dans start.ps1                   |
| GOOGLE_CLIENT_SECRET   | Secret Google       | Configuré dans start.ps1                   |
| FACEBOOK_CLIENT_ID     | OAuth Facebook      | Configuré dans start.ps1                   |
| FACEBOOK_CLIENT_SECRET | Secret Facebook     | Configuré dans start.ps1                   |

### 🔑 Configuration OAuth

#### Google OAuth
1. Créer un projet sur [Google Cloud Console](https://console.cloud.google.com)
2. Activer l'API Google+
3. URI de redirection : `http://localhost:8000/connect/google/check`
4. Les identifiants sont dans `start.ps1`

#### Facebook OAuth
1. Créer une app sur [Facebook Developers](https://developers.facebook.com)
2. Ajouter "Facebook Login"
3. URI de redirection : `http://localhost:8000/connect/facebook/check`
4. Les identifiants sont dans `start.ps1`

---

## 🎮 Utilisation

### Comptes de Test

**Admin**

```
Email: admin@example.com
Mot de passe: admin123
```

**User**

```
Email: user@example.com
Mot de passe: admin123
```

---

## 🧪 Tests

### Test XSS

```html
<script>alert('XSS')</script>
```

### Test SQLi

```sql
' OR '1'='1
```

### Test CSRF

```bash
start test_csrf.html
```

---

## 🌐 Déploiement

### Préparation Production (Nouveau !)
Utilisez le script automatisé pour préparer l'environnement :
```powershell
.\setup_prod.ps1
```
Ce script s'occupe de :
1.  L'optimisation de l'autoloader Composer.
2.  Le préchauffage du cache (Warmup).
3.  La compilation des assets via AssetMapper.
4.  L'audit de sécurité des dépendances.

### Hébergement Recommandé
*   **AlwaysData** (Gratuit/Premium - Support PHP 8.2+ & MySQL)
*   **Infomaniak / Hostinger** (Performance optimale)
*   **Vercel** (Via runtime PHP community)

---

## 📚 Documentation

### Documentation Symfony & Frameworks
* [Symfony Docs](https://symfony.com/doc/current/index.html)
* [Doctrine ORM](https://www.doctrine-project.org/projects/doctrine-orm/en/current/index.html)
* [Twig Templates](https://twig.symfony.com/doc/3.x/)
* [Bootstrap 5.3](https://getbootstrap.com/docs/5.3/)

### Documentation du Projet
* 📖 [Guide de Démarrage Rapide](QUICKSTART.md)
* 🔒 [Résumé Sécurité](SECURITY_SUMMARY.md)
* 📋 [Résumé du Projet](PROJECT_SUMMARY.md)
* 🚀 [Déploiement](DEPLOYMENT.md)
* 🎨 [Guide Visuel](VISUAL_GUIDE.md)

### 🛒 Optimisation du Panier (Nouveau !)
* ⚡ **[Optimisation du Panier](CART_OPTIMIZATION.md)** - Stockage par tokens (98% de réduction)
* 📊 **[Comparaison Avant/Après](CART_COMPARISON.md)** - Exemples concrets et métriques
* 🔍 **[Guide de Vérification](VERIFICATION_GUIDE.md)** - Comment tester l'optimisation
* 🧪 **[Page de Test](public/test_cart_storage.php)** - Outil de vérification visuel

---

## 🎯 Nouveautés de cette Version (Nov 2025)

### 🔥 Ajouts Majeurs
- ✅ **Authentification OAuth 2.0** avec Google et Facebook
- ✅ **Panier dynamique AJAX** avec compteur en temps réel
- ✅ **Avatars intelligents** : Photos de profil ou initiales par défaut
- ✅ **Badges de connexion** : Indicateurs visuels des comptes sociaux
- ✅ **UI améliorée** : Animations, design moderne, responsive
- ⚡ **Optimisation du panier** : Stockage par tokens (réduction de 98%)

### 🔧 Améliorations Techniques
- Migration de base de données pour `google_id` et `facebook_id`
- Extension Twig personnalisée pour le compteur de panier
- Authenticators OAuth personnalisés
- Gestion des emails avec système de fallback
- API REST pour le panier (`/cart/add-ajax`, `/cart/count`)
- **CartService optimisé** : Stockage d'IDs au lieu d'objets complets
- **Page de test** : Vérification visuelle du stockage en session

### 🎨 Design
- Badge panier animé avec effet pulse
- Cercles colorés avec initiales pour avatars par défaut
- Support des URLs d'avatar depuis Google/Facebook
- Interface responsive mobile et desktop

### 📊 Performance
- **98% de réduction** de l'espace en session du panier
- **50x plus rapide** pour les opérations sur le panier
- Données toujours à jour depuis la base de données
- Meilleure scalabilité pour plus d'utilisateurs simultanés

## 🧑‍💻 Auteur

**Mustapha Amine TBINI**
📍 Tunis, Tunisie
📧 [mustaphaamintbini@gmail.com](mailto:mustaphaamintbini@gmail.com)
🔗 [LinkedIn](https://www.linkedin.com/in/mustapha-amin-tbini)

**Développé par Pablo-100**

---

## 🪪 Licence

Ce projet est sous licence **MIT**.
© 2025 — Mustapha Amine TBINI

---

<div align="center">

⭐ **Si vous aimez ce projet, donnez-lui une étoile !**
🐛 **Trouvé un bug ?** [Ouvrez une issue](https://github.com/Pablo-100/synf_project/issues)
🚀 **Prêt pour la production !**

</div>
```
