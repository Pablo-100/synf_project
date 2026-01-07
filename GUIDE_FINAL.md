# 🛒 Guide Final - Projet Synf Market

## 📋 Table des Matières

1. [Présentation du Projet](#-présentation-du-projet)
2. [Prérequis](#-prérequis)
3. [Installation](#-installation)
4. [Configuration](#-configuration)
5. [Base de Données](#-base-de-données)
6. [Lancement du Projet](#-lancement-du-projet)
7. [Comptes de Test](#-comptes-de-test)
8. [Fonctionnalités](#-fonctionnalités)
9. [Problèmes Résolus](#-problèmes-résolus)
10. [Dépannage](#-dépannage)

---

## 🎯 Présentation du Projet

**Synf Market** est une application e-commerce développée avec Symfony, offrant :

- 🛍️ Catalogue de produits avec recherche
- 🛒 Panier d'achat complet
- 👤 Authentification (Email, Google, Facebook)
- 📅 Système de réservations
- 👨‍💼 Panel d'administration
- 📱 Design responsive (mobile-friendly)

---

## 💻 Prérequis

| Composant | Version Requise | Vérification |
|-----------|-----------------|--------------|
| PHP | 8.2+ | `php -v` |
| MySQL | 5.7+ | `mysql --version` |
| Composer | 2.x | `composer -V` |
| Node.js | 18+ (optionnel) | `node -v` |

### Configuration PHP Requise

Extensions PHP nécessaires :
- `pdo_mysql`
- `curl`
- `openssl`
- `mbstring`
- `xml`
- `intl`

---

## 📥 Installation

### 1. Cloner le projet

```bash
cd C:\xampp\htdocs
git clone <repository-url> synf_project
cd synf_project
```

### 2. Installer les dépendances

```bash
composer install
```

### 3. Configurer l'environnement

Créer le fichier `.env.local` :

```env
APP_ENV=dev
APP_SECRET=votre_secret_unique_ici

DATABASE_URL="mysql://root:@127.0.0.1:3306/synf_project?serverVersion=8.0"

# OAuth Google (optionnel)
GOOGLE_CLIENT_ID=votre_client_id
GOOGLE_CLIENT_SECRET=votre_client_secret

# OAuth Facebook (optionnel)
FACEBOOK_CLIENT_ID=votre_app_id
FACEBOOK_CLIENT_SECRET=votre_app_secret
```

---

## ⚙️ Configuration

### Configuration PHP pour ce Projet

Le projet utilise PHP 8.2 situé à :
```
C:\xampp-old\php\windowsXamppPhp\php.exe
```

### Configuration SSL (pour OAuth)

Le fichier `php.ini` doit contenir :
```ini
curl.cainfo = "C:\xampp-old\php\windowsXamppPhp\extras\ssl\cacert.pem"
openssl.cafile = "C:\xampp-old\php\windowsXamppPhp\extras\ssl\cacert.pem"
```

---

## 🗄️ Base de Données

### Créer la base de données

1. Ouvrir phpMyAdmin : http://localhost/phpmyadmin
2. Créer une nouvelle base de données : `synf_project`
3. Importer le fichier SQL :

```sql
-- Exécuter dans phpMyAdmin ou MySQL CLI
SOURCE C:/xampp/htdocs/synf_project/populate_database.sql;
```

### Structure des Tables

| Table | Description | Enregistrements |
|-------|-------------|-----------------|
| `user` | Utilisateurs | 8 |
| `product` | Produits | 22 |
| `category` | Catégories | 5 |
| `order` | Commandes | 10 |
| `order_item` | Articles commandés | 40 |
| `reservation` | Réservations | 12 |
| `statistics` | Statistiques | 365 |

### Réinitialiser la Base de Données

Pour réinitialiser complètement :

```sql
DROP DATABASE IF EXISTS synf_project;
CREATE DATABASE synf_project;
USE synf_project;
SOURCE C:/xampp/htdocs/synf_project/populate_database.sql;
```

---

## 🚀 Lancement du Projet

### Méthode 1 : Script PowerShell (Recommandé)

```powershell
cd C:\xampp\htdocs\synf_project
.\start.ps1
```

### Méthode 2 : Commande Manuelle

```powershell
& "C:\xampp-old\php\windowsXamppPhp\php.exe" -S localhost:8000 -t public
```

### Accéder à l'Application

- **Site** : http://localhost:8000
- **phpMyAdmin** : http://localhost/phpmyadmin

---

## 👥 Comptes de Test

### Utilisateurs Standards

| Email | Mot de passe | Rôle |
|-------|--------------|------|
| `admin@synfony.com` | `password123` | Admin |
| `manager@synfony.com` | `password123` | Manager |
| `user@example.com` | `password123` | User |
| `jean.dupont@email.com` | `password123` | User |
| `marie.martin@email.com` | `password123` | User |
| `pierre.durand@email.com` | `password123` | User |
| `sophie.bernard@email.com` | `password123` | User |
| `lucas.petit@email.com` | `password123` | User |

### Accès Admin

1. Se connecter avec `admin@synfony.com` / `password123`
2. Accéder au panel : http://localhost:8000/admin

---

## ✨ Fonctionnalités

### 🏠 Pages Publiques

| Page | URL | Description |
|------|-----|-------------|
| Accueil | `/` | Page d'accueil |
| Boutique | `/products/` | Liste des produits |
| Produit | `/products/{id}` | Détail d'un produit |
| À propos | `/about` | Page à propos |
| Contact | `/contact` | Formulaire de contact |

### 🔐 Authentification

| Page | URL | Description |
|------|-----|-------------|
| Connexion | `/login` | Connexion email/password |
| Inscription | `/register` | Créer un compte |
| Google OAuth | `/connect/google` | Connexion Google |
| Facebook OAuth | `/connect/facebook` | Connexion Facebook |

### 🛒 E-Commerce

| Action | URL | Description |
|--------|-----|-------------|
| Panier | `/cart/` | Voir le panier |
| Ajouter au panier | `/cart/add/{id}` | Ajouter un produit |
| Commandes | `/orders/` | Historique des commandes |

### 👨‍💼 Administration

| Page | URL | Description |
|------|-----|-------------|
| Dashboard | `/admin` | Tableau de bord |
| Produits | `/admin/products` | Gestion des produits |
| Utilisateurs | `/admin/users` | Gestion des utilisateurs |
| Commandes | `/admin/orders` | Gestion des commandes |
| Statistiques | `/admin/statistics` | Statistiques et rapports |

---

## 🔧 Problèmes Résolus

### 1. Erreur "#1932 - Table doesn't exist in engine"

**Cause** : Tables InnoDB corrompues ou fichiers .ibd manquants

**Solution** : Recréer les tables avec `populate_database.sql`

### 2. PHP Version Mismatch

**Cause** : XAMPP par défaut utilise PHP 7.4, projet nécessite PHP 8.2

**Solution** : Utiliser le chemin PHP 8.2 dans `start.ps1` :
```powershell
$phpPath = "C:\xampp-old\php\windowsXamppPhp\php.exe"
```

### 3. Erreur SSL cURL 60

**Cause** : Certificats SSL non configurés pour cURL/OpenSSL

**Solution** : 
1. Télécharger `cacert.pem` depuis https://curl.se/ca/cacert.pem
2. Configurer dans `php.ini` :
```ini
curl.cainfo = "C:\xampp-old\php\windowsXamppPhp\extras\ssl\cacert.pem"
openssl.cafile = "C:\xampp-old\php\windowsXamppPhp\extras\ssl\cacert.pem"
```

### 4. Mot de passe invalide

**Cause** : Hash de mot de passe incorrect dans la base de données

**Solution** : Utiliser un hash bcrypt valide :
```
$2y$10$XWiwho6.Gwf1NdMqquw/lu0v/fRJW.RRdK2CunK7wYmlt15rN0/xO
```
(correspond à `password123`)

### 5. Burger Menu non fonctionnel

**Cause** : Les spans du bouton interceptaient les clics

**Solution** : 
- Ajout de `pointer-events: none` sur les spans
- Ajout d'un fallback JavaScript pour Bootstrap Collapse

---

## 🛠️ Dépannage

### Le serveur ne démarre pas

```powershell
# Vérifier si PHP est accessible
& "C:\xampp-old\php\windowsXamppPhp\php.exe" -v

# Vérifier si le port 8000 est utilisé
netstat -ano | findstr :8000

# Tuer les processus PHP existants
Get-Process -Name php -ErrorAction SilentlyContinue | Stop-Process -Force
```

### Erreur de base de données

```powershell
# Vérifier que MySQL est démarré dans XAMPP Control Panel
# Puis tester la connexion
& "C:\xampp-old\php\windowsXamppPhp\php.exe" -r "new PDO('mysql:host=127.0.0.1;dbname=synf_project', 'root', ''); echo 'OK';"
```

### Erreur SSL persistante

```powershell
# Vérifier la configuration SSL
& "C:\xampp-old\php\windowsXamppPhp\php.exe" -i | Select-String "curl.cainfo|openssl.cafile"

# Tester SSL
& "C:\xampp-old\php\windowsXamppPhp\php.exe" -r "echo file_get_contents('https://www.google.com') ? 'SSL OK' : 'SSL FAIL';"
```

### Vider le cache Symfony

```powershell
& "C:\xampp-old\php\windowsXamppPhp\php.exe" bin/console cache:clear
```

---

## 📁 Structure du Projet

```
synf_project/
├── assets/              # Assets frontend (JS, CSS)
├── bin/                 # Binaires Symfony
├── config/              # Configuration
│   ├── packages/        # Config des bundles
│   └── routes/          # Routes
├── migrations/          # Migrations Doctrine
├── public/              # Point d'entrée web
│   ├── index.php
│   └── uploads/         # Fichiers uploadés
├── src/
│   ├── Controller/      # Contrôleurs
│   ├── Entity/          # Entités Doctrine
│   ├── Form/            # Formulaires
│   ├── Repository/      # Repositories
│   ├── Security/        # Authentification
│   └── Service/         # Services métier
├── templates/           # Templates Twig
│   ├── base.html.twig   # Layout principal
│   ├── admin/           # Templates admin
│   ├── cart/            # Templates panier
│   └── product/         # Templates produits
├── .env                 # Variables d'environnement
├── composer.json        # Dépendances PHP
├── populate_database.sql # Script de population BDD
└── start.ps1            # Script de démarrage
```

---

## 📞 Support

En cas de problème :

1. Vérifier les logs : `var/log/dev.log`
2. Consulter la documentation Symfony : https://symfony.com/doc/current/index.html
3. Vider le cache : `php bin/console cache:clear`

---

## ✅ Checklist de Démarrage Rapide

- [ ] XAMPP démarré (MySQL actif)
- [ ] Base de données `synf_project` créée
- [ ] `populate_database.sql` importé
- [ ] `composer install` exécuté
- [ ] `.env.local` configuré
- [ ] `.\start.ps1` lancé
- [ ] Accès http://localhost:8000 ✓

---

*Dernière mise à jour : Janvier 2026*
