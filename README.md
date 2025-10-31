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

## ✨ Fonctionnalités- Implémenter les bonnes pratiques de développement web avec Symfony.

- Servir de base académique pour un projet d’ingénierie en développement web.

### 🛒 Pour les Clients

- **Boutique en ligne** avec recherche et filtres par catégorie---

- **Panier d'achats** avec gestion des quantités

- **Système de commandes** avec suivi de statut## ⚙️ Installation

- **Réservations de tables** avec confirmation

- **Profil utilisateur** avec historique complet### Prérequis

- **Interface responsive** adaptée mobile/tablette/desktop- **PHP ≥ 8.1**

- **MySQL ≥ 8.0**

### 👨‍💼 Pour les Administrateurs- **Composer**

- **Dashboard premium** avec statistiques en temps réel- Extensions PHP requises : `pdo_mysql`, `gd`, `intl`

- **Gestion des produits** (CRUD complet avec images)

- **Gestion des commandes** (validation, préparation, livraison)### Étapes d’installation

- **Gestion des réservations** (confirmation, annulation)

- **Gestion des utilisateurs** avec rôles et permissions1. **Cloner le dépôt**

   ```bash

### 🔒 Sécurité (Production Ready)   git clone https://github.com/Pablo-100/synf_project.git

- ✅ **Protection XSS** (Cross-Site Scripting)   cd synf_project

- ✅ **Protection SQL Injection** avec paramètres bindés

- ✅ **Protection CSRF** (Cross-Site Request Forgery)

- ✅ **Headers de sécurité HTTP** (7 headers actifs)2. **Installer les dépendances**

- ✅ **Sessions sécurisées** (httpOnly, sameSite, secure)

- ✅ **Validation et sanitization** des données   ```bash

- ✅ **Mots de passe hashés** (Argon2i/bcrypt)   composer install

   ```

## 🎨 Interface Utilisateur

3. **Configurer la base de données**

### Design Premium   Modifier la variable `DATABASE_URL` dans le fichier `.env` :

- 🎨 Interface moderne avec **glassmorphism** et **gradients**

- ✨ Animations fluides et **micro-interactions**   ```

- 🌈 Palette de couleurs cohérente et professionnelle   DATABASE_URL="mysql://root:@127.0.0.1:3306/synf_project?serverVersion=8.0&charset=utf8mb4"

- 📱 **100% Responsive** (mobile-first)   ```

- ♿ Accessible et intuitif

4. **Créer la base de données et exécuter les migrations**

### Pages Principales

- Page d'accueil dynamique avec produits vedettes   ```bash

- Catalogue complet avec filtres et recherche   php bin/console doctrine:database:create

- Dashboards admin et utilisateur ultra-premium   php bin/console doctrine:migrations:migrate

- Formulaires optimisés avec validation en temps réel   

- Navigation avec hamburger menu personnalisé

5. **(Optionnel)** Charger des données de test (fixtures)

## 🚀 Installation Rapide

   ```bash

### Prérequis   php bin/console doctrine:fixtures:load

- PHP 8.2 ou supérieur   ```

- MySQL 8.0 ou supérieur

- Composer 2.x6. **Créer un utilisateur administrateur**

- Apache/Nginx (optionnel pour développement)

   ```bash

### 1. Cloner le projet   php bin/console make:user

```bash   ```

git clone https://github.com/Pablo-100/synf_project.git

cd synf_project7. **Démarrer le serveur local**

```

   ```bash

### 2. Installer les dépendances   symfony server:start

```bash   # ou

composer install   php -S localhost:8000 -t public

```   ```



### 3. Configurer l'environnement➡️ L’application sera accessible sur : [http://localhost:8000](http://localhost:8000)

```bash

# Copier le fichier .env---

cp .env .env.local

## 🧱 Structure du projet

# Éditer .env.local avec vos paramètres

# DATABASE_URL="mysql://user:password@127.0.0.1:3306/synf_project"```

```synf_project/

├── config/              # Configuration de Symfony

### 4. Créer la base de données├── public/              # Point d’entrée web (index.php)

```bash├── src/

php bin/console doctrine:database:create│   ├── Controller/      # Contrôleurs

php bin/console doctrine:migrations:migrate│   ├── Entity/          # Entités Doctrine

```│   ├── Form/            # Formulaires Symfony

│   ├── Repository/      # Classes de gestion des entités

### 5. (Optionnel) Charger les données de test│   └── Kernel.php

```bash├── templates/           # Vues Twig

php bin/console doctrine:fixtures:load├── migrations/          # Fichiers de migration de la base

```├── .env                 # Configuration d’environnement

└── composer.json        # Dépendances PHP

### 6. Démarrer le serveur```

```bash

# Avec Symfony CLI (recommandé)---

symfony server:start

## 🔐 Sécurité

# Ou avec PHP intégré

php -S localhost:8000 -t public* Mots de passe hashés avec **bcrypt / argon2**

* Protection **CSRF** sur les formulaires

# Ou avec le script PowerShell (Windows)* Gestion des rôles et permissions (**ROLE_USER**, **ROLE_ADMIN**)

.\start.ps1* Validation des données côté serveur

```

---

🌐 **Accédez à l'application** : http://localhost:8000

## 🧑‍💻 Développement

## 📚 Documentation

### Générer un contrôleur

- 📖 [Guide de sécurité complet](SECURITY.md)

- 🚀 [Guide de déploiement](DEPLOYMENT.md)```bash

- 📊 [Résumé de sécurité](SECURITY_SUMMARY.md)php bin/console make:controller NomController

```

## 🔐 Comptes de Test

### Générer une entité

### Administrateur

``````bash

Email: admin@freshmarket.comphp bin/console make:entity NomEntity

Mot de passe: admin123php bin/console make:migration

```php bin/console doctrine:migrations:migrate

```

### Client

```### Lancer les tests

Email: user@freshmarket.com

Mot de passe: user123```bash

```php bin/phpunit

```

## 🛠️ Stack Technique



### Backend## 📊 Fonctionnalités principales

- **Framework**: Symfony 7.x

- **ORM**: Doctrine### Utilisateurs

- **Sécurité**: Security Bundle + Custom Guards

- **Base de données**: MySQL 8.0* Inscription / connexion

- **Validation**: Symfony Validator* Profil personnel

* Historique des réservations et commandes

### Frontend

- **Framework CSS**: Bootstrap 5.3### Réservations

- **Icons**: Bootstrap Icons

- **Template Engine**: Twig* Création, modification, annulation

- **JavaScript**: Vanilla JS (pas de framework lourd)* Statuts : en attente, confirmée, annulée, terminée



### DevOps### Commandes

- **Versionning**: Git

- **Dependency Manager**: Composer* Ajout d’articles au panier

- **Deployment**: Railway, Heroku, VPS ready* Validation de commande

* Suivi du statut

## 📁 Structure du Projet

### Administration

```

synf_project/* CRUD complet : produits, utilisateurs, réservations, commandes

├── config/               # Configuration Symfony* Tableau de bord avec statistiques

│   ├── packages/        # Config des bundles

│   └── routes/          # Définition des routes---

├── public/              # Point d'entrée web

│   ├── uploads/         # Images uploadées## 🤝 Contribution

│   └── .htaccess       # Config Apache

├── src/Les contributions sont les bienvenues !

│   ├── Controller/      # ContrôleursPour contribuer :

│   ├── Entity/          # Entités Doctrine

│   ├── EventSubscriber/ # Subscribers (sécurité)1. Forker le dépôt

│   ├── Form/            # Types de formulaires2. Créer une nouvelle branche :

│   ├── Repository/      # Repositories Doctrine

│   └── Service/         # Services métier   ```bash

├── templates/           # Templates Twig   git checkout -b feature/ma-fonctionnalite

│   ├── admin/          # Interface admin   ```

│   ├── profile/        # Espace utilisateur3. Effectuer vos modifications

│   ├── product/        # Boutique4. Soumettre une Pull Request pour révision

│   └── cart/           # Panier

├── migrations/          # Migrations de base---

├── .env                 # Config environnement

└── composer.json        # Dépendances PHP## 🪪 Licence

```

Ce projet est distribué sous la **Licence MIT** :

## 🌐 Déploiement

```

### Options d'hébergement gratuitMIT License



1. **Railway.app** ⭐ (Recommandé)Copyright (c) 2025 

   - Déploiement Git automatique

   - MySQL inclusPermission is hereby granted, free of charge, to any person obtaining a copy

   - HTTPS automatiqueof this software and associated documentation files (the “Software”), to deal

   - [Guide de déploiement](DEPLOYMENT.md#option-1--railwayapp)in the Software without restriction, including without limitation the rights

to use, copy, modify, merge, publish, distribute, sublicense, and/or sell

2. **Heroku**copies of the Software, and to permit persons to whom the Software is

   - Support Symfony natiffurnished to do so, subject to the following conditions:

   - PostgreSQL/MySQL add-ons

   - [Guide de déploiement](DEPLOYMENT.md#option-2--heroku)The above copyright notice and this permission notice shall be included in all

copies or substantial portions of the Software.

3. **InfinityFree**

   - Hosting PHP traditionnel gratuitTHE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR

   - MySQL illimitéIMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,

   - [Guide de déploiement](DEPLOYMENT.md#option-3--infinityfree)FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.

IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,

### Déploiement rapideDAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,

ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

```bash```

# 1. Configurer .env.prod

cp .env.prod .env.local---

# Éditer avec vos vraies informations

## 👤 Auteur

# 2. Exécuter le script de déploiement

php deploy.php**Mustapha Amine TBINI**

📍 Tunis, Tunisie

# 3. Push vers votre hébergeur📧 [mustaphaamintbini@gmail.com](mailto:mustaphaamintbini@gmail.com)

git push production main🔗 [LinkedIn](https://www.linkedin.com/in/mustapha-amin-tbini)

```



Voir [DEPLOYMENT.md](DEPLOYMENT.md) pour les instructions détaillées.

## 🧪 Tests de Sécurité

### Test XSS
```html
<!-- Essayez de soumettre ceci dans un formulaire -->
<script>alert('XSS')</script>
<img src=x onerror=alert('XSS')>

<!-- Résultat attendu: Rejeté ou échappé -->
```

### Test SQL Injection
```sql
-- Dans la barre de recherche
' OR '1'='1
'; DROP TABLE products; --

-- Résultat attendu: Recherche normale sans erreur SQL
```

### Test Headers de Sécurité
```bash
curl -I http://localhost:8000
# Vérifiez la présence de:
# X-XSS-Protection
# X-Frame-Options
# Content-Security-Policy
```

## 🤝 Contribution

Les contributions sont les bienvenues ! Pour contribuer :

1. Forkez le projet
2. Créez une branche (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Add AmazingFeature'`)
4. Pushez vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

## 📝 Changelog

### Version 2.0 - Octobre 2025
- ✨ Refonte complète de l'interface (design premium)
- 🔒 Implémentation complète de la sécurité (XSS, SQL, CSRF)
- 🛒 Page panier redesignée
- 📱 Amélioration du responsive
- 📄 Documentation complète
- 🚀 Scripts de déploiement automatique

### Version 1.0 - Initial Release
- ✅ Système de gestion de produits
- ✅ Système de commandes
- ✅ Système de réservations
- ✅ Authentification et autorisation
- ✅ Interface d'administration

## 📄 License

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 👤 Auteur

**Pablo-100**
- GitHub: [@Pablo-100](https://github.com/Pablo-100)
- Projet: [synf_project](https://github.com/Pablo-100/synf_project)

## 🙏 Remerciements

- [Symfony](https://symfony.com) - Framework PHP
- [Bootstrap](https://getbootstrap.com) - Framework CSS
- [Doctrine](https://www.doctrine-project.org) - ORM
- [Twig](https://twig.symfony.com) - Template Engine

---

⭐ **Si vous aimez ce projet, n'hésitez pas à lui donner une étoile !**

🐛 **Trouvé un bug ?** [Ouvrez une issue](https://github.com/Pablo-100/synf_project/issues)

💬 **Des questions ?** [Démarrez une discussion](https://github.com/Pablo-100/synf_project/discussions)
