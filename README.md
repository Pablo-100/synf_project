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

* **CSRF Tokens** sur tous les formulaires
* **XSS Protection** via Twig auto-escaping
* **Validation des entrées** côté serveur
* **SQL Injection Prevention** (Doctrine ORM + Query Builder)
* **Sessions sécurisées** (`httpOnly`, `sameSite`)
* **Hashing** (Argon2i / bcrypt)

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

### Mode Production

```env
APP_ENV=prod
APP_DEBUG=0
APP_SECRET=[clé générée]
DATABASE_URL="mysql://user:pass@host:3306/dbname?serverVersion=8.0"
```

Script :

```bash
php deploy.php
```

### Hébergement Gratuit

* **Railway.app** (Recommandé)
* **Heroku**
* **InfinityFree**

---

## 📚 Documentation

* [Symfony Docs](https://symfony.com/doc/current/index.html)
* [Doctrine ORM](https://www.doctrine-project.org/projects/doctrine-orm/en/current/index.html)
* [Twig Templates](https://twig.symfony.com/doc/3.x/)
* [Bootstrap 5.3](https://getbootstrap.com/docs/5.3/)

---

## 🎯 Nouveautés de cette Version (Nov 2025)

### 🔥 Ajouts Majeurs
- ✅ **Authentification OAuth 2.0** avec Google et Facebook
- ✅ **Panier dynamique AJAX** avec compteur en temps réel
- ✅ **Avatars intelligents** : Photos de profil ou initiales par défaut
- ✅ **Badges de connexion** : Indicateurs visuels des comptes sociaux
- ✅ **UI améliorée** : Animations, design moderne, responsive

### 🔧 Améliorations Techniques
- Migration de base de données pour `google_id` et `facebook_id`
- Extension Twig personnalisée pour le compteur de panier
- Authenticators OAuth personnalisés
- Gestion des emails avec système de fallback
- API REST pour le panier (`/cart/add-ajax`, `/cart/count`)

### 🎨 Design
- Badge panier animé avec effet pulse
- Cercles colorés avec initiales pour avatars par défaut
- Support des URLs d'avatar depuis Google/Facebook
- Interface responsive mobile et desktop

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
