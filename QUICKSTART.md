# 🚀 Guide de Démarrage Rapide

## Étapes d'Installation (5 minutes)

### 1. Prérequis
- ✅ XAMPP installé (Apache + MySQL)
- ✅ PHP 8.1+ (vérifier avec `php -v`)
- ✅ Composer installé

### 2. Démarrer XAMPP
```
Ouvrir XAMPP Control Panel
Démarrer Apache
Démarrer MySQL
```

### 3. Installation des dépendances
```powershell
cd c:\xampp\htdocs\synf_project
composer install
```

### 4. Créer la base de données

**Option A : Via Symfony Console (Recommandé)**
```powershell
# Dans PowerShell
$env:DATABASE_URL="mysql://root:@127.0.0.1:3306/synf_project?serverVersion=8.0.32&charset=utf8mb4"
$env:DEFAULT_URI="http://localhost"

# Créer la base
php bin/console doctrine:database:create --if-not-exists

# Créer les tables
php bin/console make:migration
php bin/console doctrine:migrations:migrate --no-interaction
```

**Option B : Via phpMyAdmin**
1. Ouvrir http://localhost/phpmyadmin
2. Créer une nouvelle base de données : `synf_project`
3. Importer le fichier `database.sql` (voir ci-dessous)

### 5. Créer le dossier uploads
```powershell
New-Item -ItemType Directory -Path public/uploads -Force
```

### 6. Lancer le serveur
```powershell
# Option 1 : Serveur built-in PHP
php -S localhost:8000 -t public

# Option 2 : Utiliser Apache de XAMPP
# Le site sera accessible sur http://localhost/synf_project/public
```

### 7. Accéder au site
```
http://localhost:8000
ou
http://localhost/synf_project/public
```

## Créer un utilisateur admin

```powershell
# Méthode manuelle via console Symfony
php bin/console doctrine:query:sql "INSERT INTO user (email, roles, password, nom, prenom, created_at) VALUES ('admin@example.com', '[\"ROLE_ADMIN\"]', '\$2y\$13\$hashedpassword', 'Admin', 'Site', NOW())"
```

Ou via phpMyAdmin, insérer dans la table `user` :
- email: `admin@example.com`
- roles: `["ROLE_ADMIN"]`
- password: généré via `php bin/console security:hash-password`
- nom: `Admin`
- prenom: `Site`

## Données de test

Pour tester rapidement, vous pouvez créer des produits via phpMyAdmin :

```sql
INSERT INTO product (nom, description, prix, categorie, stock, disponible, created_at) VALUES
('Pizza Margherita', 'Pizza traditionnelle avec tomates et mozzarella', 12.50, 'plat', 50, 1, NOW()),
('Coca-Cola', 'Boisson gazeuse 33cl', 2.50, 'boisson', 100, 1, NOW()),
('Tiramisu', 'Dessert italien traditionnel', 6.00, 'dessert', 30, 1, NOW());
```

## Dépannage Rapide

### Erreur "Class App\Kernel not found"
```powershell
composer dump-autoload
Remove-Item -Path var\cache -Recurse -Force
```

### Erreur de connexion à la base de données
1. Vérifier que MySQL est démarré dans XAMPP
2. Vérifier le fichier `.env` :
```
DATABASE_URL="mysql://root:@127.0.0.1:3306/synf_project?serverVersion=8.0.32&charset=utf8mb4"
```

### Port 8000 déjà utilisé
```powershell
# Utiliser un autre port
php -S localhost:8080 -t public
```

### Permissions uploads/
```powershell
icacls public\uploads /grant Everyone:F
```

## Développement

### Créer un contrôleur
```powershell
php bin/console make:controller NomController
```

### Créer une entité
```powershell
php bin/console make:entity NomEntite
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

### Créer un formulaire
```powershell
php bin/console make:form NomFormType
```

### Créer l'authentification
```powershell
php bin/console make:auth
php bin/console make:registration-form
```

### Vider le cache
```powershell
php bin/console cache:clear
```

## Structure du Projet

```
synf_project/
├── src/
│   ├── Controller/      ✅ HomeController, ProductController
│   ├── Entity/          ✅ User, Product, Reservation, Order, OrderItem
│   ├── Form/            📝 À créer (Registration, Login, etc.)
│   ├── Repository/      ✅ Repositories pour toutes les entités
│   └── Kernel.php       ✅
├── templates/           ✅ base.html.twig + templates home et product
├── public/
│   ├── uploads/         📁 Dossier pour les images
│   └── index.php
├── .env                 ✅ Configuration
└── README.md            ✅ Documentation complète
```

## Prochaines Étapes

1. ✅ **Installation** - Vous êtes ici
2. 📝 **Authentification** - `php bin/console make:auth`
3. 📝 **Formulaires** - Créer les forms pour Product, Reservation, Order
4. 📝 **Admin Panel** - Créer les contrôleurs d'administration
5. 📝 **Upload d'images** - Implémenter le système d'upload
6. 📝 **Statistiques** - Créer le dashboard avec graphiques
7. 🎨 **Personnalisation** - Adapter le thème à vos besoins

## Support

En cas de problème :
1. Consulter le README.md complet
2. Vérifier les logs : `var/log/dev.log`
3. Activer le debug : `APP_ENV=dev` dans `.env`

---

✨ **Votre projet Symfony est prêt à être développé !**
