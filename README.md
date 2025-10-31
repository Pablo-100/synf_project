# 🍽️ FreshMarket - Application Web Symfony

# synf_project — Application Symfony de Réservation & Commande

> Une application web moderne et sécurisée de gestion de restaurant avec marketplace de produits, réservations et commandes en ligne.

## 🧩 Description

[![Symfony](https://img.shields.io/badge/Symfony-7.x-black?style=for-the-badge&logo=symfony)](https://symfony.com)

[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](https://php.net)**synf_project** est une application web développée avec **Symfony 7 (PHP)**.  

[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)Elle permet aux utilisateurs d’effectuer des **réservations** et de **passer des commandes** en ligne, tout en offrant une **interface d’administration complète** pour gérer les utilisateurs, les produits, les réservations et les commandes.

[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)

[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)### 🎯 Objectifs du projet

- Offrir une plateforme simple et moderne pour la gestion de réservations et commandes.

## 📋 Table des Matières- Implémenter les bonnes pratiques de développement web avec Symfony.

- Servir de base académique pour un projet d’ingénierie en développement web.

- [Aperçu](#-aperçu)

- [Fonctionnalités](#-fonctionnalités)---

- [Sécurité](#-sécurité)

- [Prérequis](#-prérequis)## ⚙️ Installation

- [Installation](#-installation)

- [Configuration](#-configuration)### Prérequis

- [Utilisation](#-utilisation)- **PHP ≥ 8.1**

- [Tests](#-tests)- **MySQL ≥ 8.0**

- [Déploiement](#-déploiement)- **Composer**

- [Structure du Projet](#-structure-du-projet)- Extensions PHP requises : `pdo_mysql`, `gd`, `intl`

- [Documentation](#-documentation)

### Étapes d’installation

## 🎯 Aperçu

1. **Cloner le dépôt**

**FreshMarket** est une application web complète développée avec **Symfony 7** qui permet de gérer un restaurant moderne avec :   ```bash

   git clone https://github.com/Pablo-100/synf_project.git

- 🛒 Une **marketplace de produits** (plats, boissons, desserts)   cd synf_project

- 📅 Un **système de réservations** de tables

- 🛍️ Un **système de commandes** en ligne avec panier

- 👥 Une **gestion utilisateurs** avec rôles (Admin/User)2. **Installer les dépendances**

- 📊 Des **dashboards interactifs** pour le suivi d'activité

- 🎨 Une **interface premium** responsive et moderne   ```bash

   composer install

### 🎨 Design Premium   ```



- Interface ultra-moderne avec **glassmorphism** et **gradients**3. **Configurer la base de données**

- Animations fluides et **micro-interactions**   Modifier la variable `DATABASE_URL` dans le fichier `.env` :

- **Icon boxes** 80x80px avec effets shine

- Palette de couleurs cohérente (Blue, Green, Cyan, Orange, Purple)   ```

- **100% Responsive** (mobile, tablette, desktop)   DATABASE_URL="mysql://root:@127.0.0.1:3306/synf_project?serverVersion=8.0&charset=utf8mb4"

   ```

## ✨ Fonctionnalités

4. **Créer la base de données et exécuter les migrations**

### 👤 Espace Client

   ```bash

| Fonctionnalité | Description | Status |   php bin/console doctrine:database:create

|----------------|-------------|--------|   php bin/console doctrine:migrations:migrate

| **Inscription/Connexion** | Système d'authentification sécurisé | ✅ |   

| **Boutique** | Catalogue de produits avec images | ✅ |

| **Recherche** | Recherche par nom ou description | ✅ |5. **(Optionnel)** Charger des données de test (fixtures)

| **Filtres** | Filtrage par catégorie (Plat, Boisson, Dessert) | ✅ |

| **Panier** | Gestion du panier (ajout, modification, suppression) | ✅ |   ```bash

| **Commandes** | Passage et suivi de commandes | ✅ |   php bin/console doctrine:fixtures:load

| **Réservations** | Réservation de tables avec date/heure/personnes | ✅ |   ```

| **Profil** | Gestion du profil utilisateur avec avatar | ✅ |

| **Historique** | Accès aux commandes et réservations passées | ✅ |6. **Créer un utilisateur administrateur**

| **Dashboard** | Vue d'ensemble personnalisée | ✅ |

   ```bash

### 👨‍💼 Espace Admin   php bin/console make:user

   ```

| Fonctionnalité | Description | Status |

|----------------|-------------|--------|7. **Démarrer le serveur local**

| **Dashboard** | Statistiques en temps réel | ✅ |

| **Gestion Produits** | CRUD complet (Create, Read, Update, Delete) | ✅ |   ```bash

| **Upload Images** | Gestion des images produits | ✅ |   symfony server:start

| **Gestion Stock** | Suivi et mise à jour du stock | ✅ |   # ou

| **Gestion Commandes** | Validation, préparation, livraison, annulation | ✅ |   php -S localhost:8000 -t public

| **Gestion Réservations** | Confirmation, modification, annulation | ✅ |   ```

| **Gestion Utilisateurs** | Liste et gestion des comptes | ✅ |

| **Statistiques** | Nombre d'utilisateurs, produits, commandes, réservations | ✅ |➡️ L’application sera accessible sur : [http://localhost:8000](http://localhost:8000)



## 🔒 Sécurité---



L'application implémente une **sécurité de niveau production** :## 🧱 Structure du projet



### Protection XSS (Cross-Site Scripting)```

synf_project/

- ✅ **Headers HTTP de sécurité** (`X-XSS-Protection`, `Content-Security-Policy`)├── config/              # Configuration de Symfony

- ✅ **SecurityService** avec 13 méthodes de validation├── public/              # Point d’entrée web (index.php)

- ✅ **Échappement automatique** Twig sur toutes les sorties├── src/

- ✅ **Validation des entrées** dans les contrôleurs│   ├── Controller/      # Contrôleurs

- ✅ **Sanitization** avec `strip_tags()` et `htmlspecialchars()`│   ├── Entity/          # Entités Doctrine

│   ├── Form/            # Formulaires Symfony

### Protection SQL Injection│   ├── Repository/      # Classes de gestion des entités

│   └── Kernel.php

- ✅ **Doctrine ORM** avec Query Builder├── templates/           # Vues Twig

- ✅ **Paramètres bindés** (`:parameter`) dans toutes les requêtes├── migrations/          # Fichiers de migration de la base

- ✅ **Aucune concaténation SQL**├── .env                 # Configuration d’environnement

- ✅ **Détection automatique** des patterns malveillants└── composer.json        # Dépendances PHP

```

### Protection CSRF (Cross-Site Request Forgery)

---

- ✅ **Tokens CSRF** automatiques dans tous les formulaires

- ✅ **Validation** côté serveur## 🔐 Sécurité

- ✅ **Configuration** dans `framework.yaml` et `security.yaml`

* Mots de passe hashés avec **bcrypt / argon2**

### Sécurité Supplémentaire* Protection **CSRF** sur les formulaires

* Gestion des rôles et permissions (**ROLE_USER**, **ROLE_ADMIN**)

| Protection | Implémentation | Status |* Validation des données côté serveur

|------------|----------------|--------|

| **Sessions sécurisées** | `httpOnly`, `sameSite`, `secure` | ✅ |---

| **Mots de passe** | Argon2i/bcrypt avec hashing automatique | ✅ |

| **ACL** | Contrôle d'accès par rôles (ROLE_USER, ROLE_ADMIN) | ✅ |## 🧑‍💻 Développement

| **Headers HTTP** | 7 headers de sécurité actifs | ✅ |

| **Validation fichiers** | Extensions et tailles vérifiées | ✅ |### Générer un contrôleur

| **.htaccess** | Protection Apache complète | ✅ |

```bash

### Headers de Sécurité HTTPphp bin/console make:controller NomController

```

```

X-XSS-Protection: 1; mode=block### Générer une entité

X-Frame-Options: DENY

X-Content-Type-Options: nosniff```bash

Referrer-Policy: strict-origin-when-cross-originphp bin/console make:entity NomEntity

Permissions-Policy: geolocation=(), microphone=(), camera=()php bin/console make:migration

Content-Security-Policy: default-src 'self'; ...php bin/console doctrine:migrations:migrate

Strict-Transport-Security: max-age=31536000 (production HTTPS)```

```

### Lancer les tests

## 📋 Prérequis

```bash

### Système Minimumphp bin/phpunit

```

- **PHP** : 8.2 ou supérieur

- **MySQL** : 8.0 ou supérieur  

- **Composer** : 2.x## 📊 Fonctionnalités principales

- **Apache/Nginx** : (optionnel pour développement)

### Utilisateurs

### Extensions PHP Requises

* Inscription / connexion

```* Profil personnel

php-pdo* Historique des réservations et commandes

php-mysql

php-xml### Réservations

php-mbstring

php-curl* Création, modification, annulation

php-zip* Statuts : en attente, confirmée, annulée, terminée

php-intl

php-gd### Commandes

```

* Ajout d’articles au panier

## 🚀 Installation* Validation de commande

* Suivi du statut

### 1. Cloner le Projet

### Administration

```bash

git clone https://github.com/Pablo-100/synf_project.git* CRUD complet : produits, utilisateurs, réservations, commandes

cd synf_project* Tableau de bord avec statistiques

```

---

### 2. Installer les Dépendances

## 🤝 Contribution

```bash

composer installLes contributions sont les bienvenues !

```Pour contribuer :



### 3. Créer la Base de Données1. Forker le dépôt

2. Créer une nouvelle branche :

#### Option A : Avec le fichier SQL fourni

   ```bash

```bash   git checkout -b feature/ma-fonctionnalite

# Créer la base   ```

mysql -u root -p -e "CREATE DATABASE synf_project CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"3. Effectuer vos modifications

4. Soumettre une Pull Request pour révision

# Importer le fichier

mysql -u root -p synf_project < database.sql---

```

## 🪪 Licence

#### Option B : Avec les migrations Symfony

Ce projet est distribué sous la **Licence MIT** :

```bash

# Créer la base```

php bin/console doctrine:database:createMIT License



# Exécuter les migrationsCopyright (c) 2025 

php bin/console doctrine:migrations:migrate

```Permission is hereby granted, free of charge, to any person obtaining a copy

of this software and associated documentation files (the “Software”), to deal

### 4. Configurationin the Software without restriction, including without limitation the rights

to use, copy, modify, merge, publish, distribute, sublicense, and/or sell

Créez un fichier `.env.local` à la racine du projet :copies of the Software, and to permit persons to whom the Software is

furnished to do so, subject to the following conditions:

```env

# Base de donnéesThe above copyright notice and this permission notice shall be included in all

DATABASE_URL="mysql://root:@127.0.0.1:3306/synf_project?serverVersion=8.0&charset=utf8mb4"copies or substantial portions of the Software.



# EnvironnementTHE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR

APP_ENV=devIMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,

APP_DEBUG=1FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.

APP_SECRET=VotreCleSecreteGenereeIN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,

```DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,

ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

### 5. Créer le Dossier d'Uploads```



```bash---

# Windows

mkdir public\uploads## 👤 Auteur



# Linux/Mac**Mustapha Amine TBINI**

mkdir -p public/uploads📍 Tunis, Tunisie

chmod -R 775 public/uploads📧 [mustaphaamintbini@gmail.com](mailto:mustaphaamintbini@gmail.com)

```🔗 [LinkedIn](https://www.linkedin.com/in/mustapha-amin-tbini)



### 6. Démarrer le Serveur


#### Windows (PowerShell)

```bash
.\start.ps1
```

#### Linux/Mac

```bash
symfony server:start
# ou
php -S localhost:8000 -t public
```

🌐 **Accédez à l'application** : http://localhost:8000

## ⚙️ Configuration

### Variables d'Environnement

| Variable | Description | Valeur par Défaut |
|----------|-------------|-------------------|
| `APP_ENV` | Environnement (dev/prod) | `dev` |
| `APP_DEBUG` | Mode debug (0/1) | `1` |
| `APP_SECRET` | Clé secrète Symfony | À générer |
| `DATABASE_URL` | URL de connexion MySQL | `mysql://root:@127.0.0.1:3306/synf_project` |

### Générer une Clé Secrète

```bash
php -r "echo bin2hex(random_bytes(32));"
```

## 🎮 Utilisation

### Comptes de Test

#### Administrateur

```
Email: admin@freshmarket.com
Mot de passe: admin123
```

**Accès** : http://localhost:8000/admin

#### Client

```
Email: user@freshmarket.com
Mot de passe: user123
```

**Accès** : http://localhost:8000/profile

### Pages Principales

| URL | Description | Accès |
|-----|-------------|-------|
| `/` | Page d'accueil | Public |
| `/products` | Catalogue produits | Public |
| `/products/search` | Recherche | Public |
| `/login` | Connexion | Public |
| `/register` | Inscription | Public |
| `/cart` | Panier | Utilisateur |
| `/cart/checkout` | Passer commande | Utilisateur |
| `/profile` | Dashboard utilisateur | Utilisateur |
| `/profile/orders` | Mes commandes | Utilisateur |
| `/profile/reservations` | Mes réservations | Utilisateur |
| `/profile/edit` | Modifier profil | Utilisateur |
| `/admin` | Dashboard admin | Admin |
| `/admin/products` | Gestion produits | Admin |
| `/admin/orders` | Gestion commandes | Admin |
| `/admin/reservations` | Gestion réservations | Admin |

## 🧪 Tests

### Tests Manuels

#### Test de Fonctionnalités

```bash
# 1. Démarrer le serveur
.\start.ps1

# 2. Ouvrir dans le navigateur
http://localhost:8000

# 3. Tester :
- Inscription/Connexion
- Navigation dans le catalogue
- Ajout au panier
- Passage de commande
- Réservation de table
- Dashboard utilisateur
- Dashboard admin
```

#### Tests de Sécurité

##### Test XSS

```html
<!-- Dans la recherche, essayez : -->
<script>alert('XSS')</script>
<img src=x onerror=alert('XSS')>

<!-- ✅ Résultat attendu : Texte échappé, pas d'exécution -->
```

##### Test SQL Injection

```sql
-- Dans la recherche, essayez :
' OR '1'='1
'; DROP TABLE products; --

-- ✅ Résultat attendu : Recherche normale, pas d'erreur SQL
```

##### Test CSRF

```bash
# Ouvrez test_csrf.html dans le navigateur
start test_csrf.html

# ✅ Résultat attendu : Erreur "Invalid CSRF token"
```

##### Test Headers HTTP

```bash
# PowerShell
curl -I http://localhost:8000

# ✅ Vérifiez la présence des 7 headers de sécurité
```

### Scripts de Test Automatiques

```bash
# Test XSS (Windows)
.\test_xss.bat

# Test Headers (Windows)
.\test_headers.bat
```

## 🌐 Déploiement

### Mode Production

#### 1. Configurer `.env.prod`

```env
APP_ENV=prod
APP_DEBUG=0
APP_SECRET=[générer avec: php -r "echo bin2hex(random_bytes(32));"]
DATABASE_URL="mysql://user:pass@host:3306/dbname?serverVersion=8.0"
```

#### 2. Script de Déploiement

```bash
php deploy.php
```

Ce script effectue :
- ✅ Vidage du cache
- ✅ Installation des dépendances (`--no-dev --optimize-autoloader`)
- ✅ Migrations de base de données
- ✅ Optimisation de l'autoloader
- ✅ Préchauffage du cache
- ✅ Vérification des permissions

### Hébergement Gratuit

#### Option 1 : Railway.app ⭐ (Recommandé)

```bash
npm install -g @railway/cli
railway login
railway init
railway up
```

**Avantages** :
- MySQL inclus
- HTTPS automatique
- Déploiement Git automatique
- Gratuit (500h/mois)

[Guide complet](DEPLOYMENT.md#option-1--railwayapp)

#### Option 2 : Heroku

```bash
# Installer Heroku CLI
choco install heroku-cli  # Windows

# Déployer
heroku login
heroku create mon-app
git push heroku main
```

[Guide complet](DEPLOYMENT.md#option-2--heroku)

#### Option 3 : InfinityFree

- Hosting PHP gratuit
- MySQL illimité
- Support PHP 8.x

[Guide complet](DEPLOYMENT.md#option-3--infinityfree)

### Configuration Apache (.htaccess)

Le fichier `.htaccess` est déjà configuré avec :
- ✅ Redirection vers `index.php`
- ✅ Headers de sécurité
- ✅ Protection des fichiers sensibles
- ✅ Désactivation du listing
- ✅ Support HTTPS

## 📁 Structure du Projet

```
synf_project/
├── config/                      # Configuration Symfony
│   ├── packages/               # Config des bundles
│   │   ├── framework.yaml     # CSRF, sessions
│   │   ├── security.yaml      # Authentification, ACL
│   │   └── doctrine.yaml      # ORM, base de données
│   └── routes/                # Définition des routes
│
├── public/                     # Point d'entrée web
│   ├── index.php              # Front controller
│   ├── .htaccess              # Config Apache
│   └── uploads/               # Images uploadées
│
├── src/
│   ├── Controller/            # Contrôleurs
│   │   ├── HomeController.php
│   │   ├── ProductController.php
│   │   ├── CartController.php
│   │   ├── ProfileController.php
│   │   └── Admin/            # Contrôleurs admin
│   │
│   ├── Entity/               # Entités Doctrine
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Order.php
│   │   └── Reservation.php
│   │
│   ├── EventSubscriber/      # Event subscribers
│   │   └── SecurityHeadersSubscriber.php  # Headers HTTP
│   │
│   ├── Form/                 # Types de formulaires
│   │   ├── RegistrationFormType.php
│   │   ├── ProductType.php
│   │   └── ReservationType.php
│   │
│   ├── Repository/           # Repositories Doctrine
│   │   ├── UserRepository.php
│   │   ├── ProductRepository.php
│   │   └── OrderRepository.php
│   │
│   └── Service/              # Services métier
│       └── SecurityService.php  # Validation/Sanitization
│
├── templates/                # Templates Twig
│   ├── base.html.twig       # Template principal
│   ├── home/                # Pages publiques
│   ├── product/             # Catalogue
│   ├── cart/                # Panier
│   ├── profile/             # Espace utilisateur
│   ├── admin/               # Interface admin
│   └── security/            # Login/Register
│
├── migrations/              # Migrations de base
├── var/
│   ├── cache/              # Cache Symfony
│   └── log/                # Logs
│
├── vendor/                 # Dépendances Composer
├── .env                    # Config environnement (base)
├── .env.local              # Config locale (non versionné)
├── .env.prod               # Config production
├── composer.json           # Dépendances PHP
├── database.sql            # Structure BDD
├── deploy.php              # Script de déploiement
├── start.ps1               # Script de démarrage Windows
└── README.md               # Ce fichier
```

## 🛠️ Stack Technique

### Backend

- **Framework** : Symfony 7.x
- **Langage** : PHP 8.2+
- **ORM** : Doctrine
- **Base de données** : MySQL 8.0
- **Sécurité** : Security Bundle + Custom Guards
- **Validation** : Symfony Validator
- **Sessions** : Symfony Session
- **Password** : Symfony Password Hasher (Argon2i/bcrypt)

### Frontend

- **Framework CSS** : Bootstrap 5.3
- **Icons** : Bootstrap Icons
- **Template Engine** : Twig
- **JavaScript** : Vanilla JS
- **Design** : Glassmorphism, Gradients, Animations CSS

### DevOps

- **Versionning** : Git/GitHub
- **Dependency Manager** : Composer
- **Deployment** : Railway, Heroku, VPS ready
- **Environment** : .env files

## 📚 Documentation

### Documentation du Projet

- 📖 [Guide de Sécurité Complet](SECURITY.md) - 8 sections détaillées
- 🚀 [Guide de Déploiement](DEPLOYMENT.md) - 4 options d'hébergement
- 📊 [Résumé de Sécurité](SECURITY_SUMMARY.md) - Vue d'ensemble

### Documentation Externe

- [Symfony Docs](https://symfony.com/doc/current/index.html)
- [Doctrine ORM](https://www.doctrine-project.org/projects/doctrine-orm/en/current/index.html)
- [Twig Templates](https://twig.symfony.com/doc/3.x/)
- [Bootstrap 5.3](https://getbootstrap.com/docs/5.3/)

## 📄 License

Ce projet est sous licence **MIT**.

---

<div align="center">

### ⭐ Si vous aimez ce projet, donnez-lui une étoile !

### 🐛 Trouvé un bug ? [Ouvrez une issue](https://github.com/Pablo-100/synf_project/issues)

### 🚀 Prêt pour la production !

**Made with ❤️ by Pablo-100**

