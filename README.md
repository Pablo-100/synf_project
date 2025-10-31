# 🍽️ **FreshMarket — Plateforme Web Symfony de Réservation & Commande**

> 🥦 *Marketplace gourmande & système intelligent de gestion de restaurant*  
> Une application web moderne, responsive et sécurisée développée avec **Symfony 7 & PHP 8.2+**

---

![Symfony](https://img.shields.io/badge/Symfony-7.x-black?style=for-the-badge&logo=symfony)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

---

## 🎯 **Objectif du Projet**

Créer une **plateforme web intuitive** permettant :
- Aux **clients** : de réserver une table, passer commande et suivre leurs achats.  
- Aux **administrateurs** : de gérer les produits, utilisateurs, réservations et commandes via un **dashboard intelligent et sécurisé**.

---

## ✨ **Fonctionnalités Principales**

### 👨‍🍳 Pour les Clients
- 🛍️ **Boutique en ligne** avec filtres et recherche dynamique  
- 🧺 **Panier interactif** (quantité, suppression, total en temps réel)  
- 🍽️ **Réservation de tables** avec confirmation instantanée  
- 📦 **Suivi de commande** et historique complet  
- 👤 **Espace personnel** avec gestion du profil  

### 🧑‍💼 Pour les Administrateurs
- 📊 **Dashboard Premium** avec statistiques temps réel  
- 🛒 CRUD complet : produits, utilisateurs, commandes, réservations  
- 🧠 Gestion des rôles et permissions (`ROLE_USER`, `ROLE_ADMIN`)  
- 📷 Upload d’images produit avec validation et compression automatique  
- 🔒 Sécurité renforcée (XSS, CSRF, SQL Injection, headers HTTPS)

---

## 💎 **Design & Expérience Utilisateur**

🎨 **Interface Premium** basée sur :
- 🧊 *Glassmorphism & Gradients*  
- 🌈 Palette de couleurs cohérente  
- ⚡ *Micro-interactions & transitions fluides*  
- 📱 *Responsive Design* (Mobile-First)  
- ♿ *Accessibilité renforcée*

---

## ⚙️ **Installation & Démarrage**

### 🧰 Prérequis
- PHP ≥ 8.2  
- MySQL ≥ 8.0  
- Composer 2.x  
- Extensions PHP : `pdo_mysql`, `gd`, `intl`

---

### 🚀 Étapes d’installation

```bash
# 1️⃣ Cloner le projet
git clone https://github.com/Pablo-100/synf_project.git
cd synf_project

# 2️⃣ Installer les dépendances
composer install

# 3️⃣ Configurer la base de données
cp .env .env.local
# Modifier DATABASE_URL="mysql://root:@127.0.0.1:3306/synf_project"

# 4️⃣ Créer la base et lancer les migrations
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# 5️⃣ (Optionnel) Charger les données de test
php bin/console doctrine:fixtures:load

# 6️⃣ Lancer le serveur
symfony server:start
# ou
php -S localhost:8000 -t public
````

> 🖥️ Accès local : [http://localhost:8000](http://localhost:8000)

---

## 🧱 **Structure du Projet**

```
synf_project/
├── config/              # Configuration Symfony
├── public/              # Point d'entrée web (index.php)
├── src/
│   ├── Controller/      # Contrôleurs MVC
│   ├── Entity/          # Entités Doctrine ORM
│   ├── Form/            # Gestion des formulaires
│   ├── Repository/      # Requêtes personnalisées
│   └── Service/         # Logique métier
├── templates/           # Templates Twig (admin, user, shop)
├── migrations/          # Migrations SQL
└── composer.json        # Dépendances PHP
```

---

## 🔐 **Sécurité & Fiabilité**

| Protection           | Description                        |
| -------------------- | ---------------------------------- |
| 🧱 CSRF              | Jetons sur tous les formulaires    |
| 🧩 XSS               | Filtrage & échappement Twig        |
| 🕵️‍♂️ SQL Injection | Paramètres préparés Doctrine       |
| 🔑 Mots de passe     | Hashage Argon2i / bcrypt           |
| 🧭 Sessions          | `httpOnly`, `SameSite`, `secure`   |
| 🧱 Headers           | 7 headers HTTP de sécurité activés |

---

## 🧪 **Tests de Sécurité Rapides**

### XSS

```html
<script>alert('XSS')</script>
```

➡️ Résultat : contenu échappé ✅

### SQL Injection

```sql
' OR '1'='1 --
```

➡️ Résultat : requête bloquée ✅

### Headers

```bash
curl -I http://localhost:8000
```

➡️ Résultat : `X-XSS-Protection`, `CSP`, `Frame-Options` présents ✅

---

## 🧑‍💻 **Comptes de Test**

| Rôle      | Email                   | Mot de passe |
| --------- | ----------------------- | ------------ |
| 👑 Admin  | `admin@freshmarket.com` | `admin123`   |
| 👤 Client | `user@freshmarket.com`  | `user123`    |

---

## 🌍 **Déploiement**

### 🔧 Hébergements compatibles

* **Railway.app** (recommandé — Git Deploy + MySQL)
* **Heroku** (PostgreSQL/MySQL add-ons)
* **InfinityFree** (hébergement PHP gratuit)

📄 Voir le [guide de déploiement complet](DEPLOYMENT.md)

---

## 🤝 **Contribution**

💡 Les contributions sont toujours les bienvenues !

```bash
# 1. Fork du dépôt
# 2. Nouvelle branche
git checkout -b feature/ma-fonctionnalite

# 3. Commit + Push
git commit -m "✨ Ajout d'une nouvelle fonctionnalité"
git push origin feature/ma-fonctionnalite
```

🔁 Ouvrez ensuite une **Pull Request** pour validation.

---

## 🪪 **Licence**

📜 Projet distribué sous licence [MIT](LICENSE)
© 2025 **Mustapha Amine TBINI** – Tous droits réservés.

---

## 👤 **Auteur**

**Mustapha Amine TBINI**
📍 Tunis, Tunisie
📧 [mustaphaamintbini@gmail.com](mailto:mustaphaamintbini@gmail.com)
🔗 [LinkedIn](https://www.linkedin.com/in/mustapha-amin-tbini)

---

## 🧠 **Remerciements**

* 🧰 [Symfony](https://symfony.com) – Framework PHP
* 🎨 [Bootstrap](https://getbootstrap.com) – Framework CSS
* 🗄️ [Doctrine](https://www.doctrine-project.org) – ORM
* 🧩 [Twig](https://twig.symfony.com) – Template Engine

---

⭐ **Si ce projet t’a plu, n’hésite pas à lui mettre une étoile sur GitHub !**
🐛 *Tu as trouvé un bug ?* → [Ouvre une issue](https://github.com/Pablo-100/synf_project/issues)
💬 *Des questions ?* → [Rejoins la discussion](https://github.com/Pablo-100/synf_project/discussions)

```

