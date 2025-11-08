# 🍽️ FreshMarket - Application Web Symfony

> Une application web moderne et sécurisée de gestion de restaurant avec marketplace de produits, réservations et commandes en ligne.

[![Symfony](https://img.shields.io/badge/Symfony-7.x-black?style=for-the-badge&logo=symfony)](https://symfony.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

## 🌐 Déploiement Gratuit

**🚀 Mettez votre projet en ligne GRATUITEMENT en 5 minutes !**

[![Deploy on Railway](https://img.shields.io/badge/Deploy%20on-Railway-blueviolet?style=for-the-badge&logo=railway)](https://railway.app)
[![Deploy on Render](https://img.shields.io/badge/Deploy%20on-Render-46E3B7?style=for-the-badge&logo=render)](https://render.com)

### Guides de Déploiement
- 🌟 **[Guide Complet : Domaine & Hébergement GRATUIT](FREE_DOMAIN_GUIDE.md)** ⭐ **NOUVEAU**
- 🚀 **[Déploiement en Un Clic](ONE_CLICK_DEPLOY.md)** ⭐ **NOUVEAU**
- 📖 [DEPLOYMENT.md](DEPLOYMENT.md) - Options complètes
- 🎯 [RENDER_DEPLOY.md](RENDER_DEPLOY.md) - Déploiement sur Render

**Option la plus rapide (5 minutes) :**

Utilisez le script automatique :
```bash
# Linux/macOS
./quick-deploy.sh

# Windows
quick-deploy.bat
```

Ou manuellement avec Railway :
```bash
npm install -g @railway/cli
railway login
railway init
railway up
```
✅ Votre app sera en ligne sur `https://votre-app.up.railway.app`

---

## 🎯 Objectifs du Projet

- Offrir une plateforme simple et moderne pour la gestion de réservations et commandes.
- Implémenter les bonnes pratiques de développement web avec Symfony.
- Servir de base académique pour un projet d’ingénierie en développement web.

---

## 📋 Table des Matières

- [Déploiement Gratuit](#-déploiement-gratuit) ⭐ **Nouveau**
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

### 👤 Espace Client

| Fonctionnalité          | Description                       | Status |
| ----------------------- | --------------------------------- | ------ |
| Inscription / Connexion | Authentification sécurisée        | ✅      |
| Boutique                | Catalogue produits avec images    | ✅      |
| Recherche & Filtres     | Par nom ou catégorie              | ✅      |
| Panier                  | Ajout / suppression / mise à jour | ✅      |
| Commandes               | Suivi des commandes               | ✅      |
| Réservations            | Réservation de tables             | ✅      |
| Profil                  | Gestion du profil et avatar       | ✅      |

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
│   ├── routes/
│   └── security.yaml
├── public/
│   ├── index.php
│   └── uploads/
├── src/
│   ├── Controller/
│   ├── Entity/
│   ├── Form/
│   ├── Repository/
│   ├── Service/
│   └── EventSubscriber/
├── templates/
├── migrations/
├── composer.json
└── README.md
```

---

## ⚙️ Configuration

| Variable     | Description   | Valeur                                     |
| ------------ | ------------- | ------------------------------------------ |
| APP_ENV      | Environnement | dev                                        |
| APP_DEBUG    | Mode debug    | 1                                          |
| APP_SECRET   | Clé Symfony   | Générée                                    |
| DATABASE_URL | Connexion DB  | `mysql://root@127.0.0.1:3306/synf_project` |

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

### 🎯 Guide Complet de Déploiement Gratuit

**📘 [Consultez le Guide Complet : Domaine & Hébergement GRATUIT](FREE_DOMAIN_GUIDE.md)**

Ce guide vous montre comment :
- ✅ Déployer votre app en **5 minutes** avec un domaine gratuit
- ✅ Obtenir un **sous-domaine gratuit** (`.up.railway.app`, `.onrender.com`)
- ✅ Configurer un **domaine personnalisé gratuit** (`.tk`, `.ml`, `.ga`)
- ✅ Héberger avec base de données MySQL **gratuitement**

### Options de Déploiement Rapide

#### 1. Railway.app ⭐ (Recommandé - Le plus simple)

**Option A : Script de Déploiement Automatique**
```bash
# Linux/macOS
./quick-deploy.sh

# Windows
quick-deploy.bat
```

**Option B : Manuel**
```bash
npm install -g @railway/cli
railway login
railway init
railway up
```
✅ URL gratuite : `https://votre-app.up.railway.app`

#### 2. Render.com
Voir : [RENDER_DEPLOY.md](RENDER_DEPLOY.md)

#### 3. Guides Détaillés
- 🚀 **[ONE_CLICK_DEPLOY.md](ONE_CLICK_DEPLOY.md)** - Déploiement en un clic
- 🌐 **[FREE_DOMAIN_GUIDE.md](FREE_DOMAIN_GUIDE.md)** - Domaines gratuits
- 📖 [DEPLOYMENT.md](DEPLOYMENT.md) - Guide complet de déploiement

### Mode Production

```env
APP_ENV=prod
APP_DEBUG=0
APP_SECRET=[clé générée]
DATABASE_URL="mysql://user:pass@host:3306/dbname?serverVersion=8.0"
```

Script de déploiement local :

```bash
php deploy.php
```

---

## 📚 Documentation

### 📘 Documentation du Projet
* 🌟 **[FREE_DOMAIN_GUIDE.md](FREE_DOMAIN_GUIDE.md)** - Domaine & Hébergement Gratuit
* 🚀 **[ONE_CLICK_DEPLOY.md](ONE_CLICK_DEPLOY.md)** - Déploiement en Un Clic
* 📖 **[DEPLOYMENT.md](DEPLOYMENT.md)** - Guide de Déploiement Complet
* 🎯 **[RENDER_DEPLOY.md](RENDER_DEPLOY.md)** - Déploiement sur Render
* 📊 **[PROJECT_SUMMARY.md](PROJECT_SUMMARY.md)** - Récapitulatif du Projet
* ⚡ **[QUICKSTART.md](QUICKSTART.md)** - Guide de Démarrage Rapide
* 🔒 **[SECURITY.md](SECURITY.md)** - Guide de Sécurité

### 🛠️ Documentation Technique
* [Symfony Docs](https://symfony.com/doc/current/index.html)
* [Doctrine ORM](https://www.doctrine-project.org/projects/doctrine-orm/en/current/index.html)
* [Twig Templates](https://twig.symfony.com/doc/3.x/)
* [Bootstrap 5.3](https://getbootstrap.com/docs/5.3/)

---

## 🧑‍💻 Auteur

**Mustapha Amine TBINI**
📍 Tunis, Tunisie
📧 [mustaphaamintbini@gmail.com](mailto:mustaphaamintbini@gmail.com)
🔗 [LinkedIn](https://www.linkedin.com/in/mustapha-amin-tbini)

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
