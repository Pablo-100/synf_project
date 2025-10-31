# 🚀 GUIDE DE DÉPLOIEMENT RAPIDE

## Option 1 : Railway.app (Recommandé - Le plus simple)

### 1. Créer un compte sur Railway
- Allez sur https://railway.app
- Connectez-vous avec GitHub

### 2. Déployer votre projet
```bash
# Depuis votre machine locale
npm install -g @railway/cli
railway login
railway init
railway up
```

### 3. Configurer les variables d'environnement
Dans le dashboard Railway, ajoutez :
```
APP_ENV=prod
APP_DEBUG=0
APP_SECRET=[généré avec: php -r "echo bin2hex(random_bytes(32));"]
DATABASE_URL=[fourni automatiquement par Railway MySQL]
```

### 4. Ajouter MySQL
- Dans Railway, cliquez sur "New" > "Database" > "MySQL"
- Copiez l'URL de connexion dans DATABASE_URL

✅ **Votre app est en ligne !**

---

## Option 2 : Heroku

### 1. Installer Heroku CLI
```bash
# Windows
choco install heroku-cli

# macOS
brew tap heroku/brew && brew install heroku

# Linux
curl https://cli-assets.heroku.com/install.sh | sh
```

### 2. Déployer
```bash
cd c:\xampp\htdocs\synf_project
heroku login
heroku create mon-app-symfony
git push heroku main
```

### 3. Configurer la base de données
```bash
# Ajouter PostgreSQL (gratuit)
heroku addons:create heroku-postgresql:essential-0

# Ou MySQL avec ClearDB
heroku addons:create cleardb:ignite
```

### 4. Variables d'environnement
```bash
heroku config:set APP_ENV=prod
heroku config:set APP_DEBUG=0
heroku config:set APP_SECRET=$(php -r "echo bin2hex(random_bytes(32));")
```

### 5. Lancer les migrations
```bash
heroku run php bin/console doctrine:migrations:migrate
```

✅ **Votre app est en ligne !**

---

## Option 3 : InfinityFree (Hosting gratuit traditionnel)

### 1. Créer un compte
- https://infinityfree.net
- Choisissez un sous-domaine gratuit

### 2. Uploader les fichiers
- Via FTP (FileZilla)
- Host : ftpupload.net
- Port : 21
- Uploadez tout sauf `/var`, `/vendor`, `.env`

### 3. Installer composer sur le serveur
```bash
# Via SSH (si disponible) ou cPanel Terminal
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php composer.phar install --no-dev --optimize-autoloader
```

### 4. Configurer la base de données
- Créez une base MySQL dans cPanel
- Copiez les informations de connexion
- Créez `.env.local` avec :
```
DATABASE_URL="mysql://username:password@host:3306/dbname"
APP_ENV=prod
APP_DEBUG=0
```

### 5. Configurer le .htaccess
Le fichier existe déjà dans `/public`

### 6. Définir le dossier web root
Dans cPanel, définissez `/public` comme dossier racine

✅ **Votre app est en ligne !**

---

## Option 4 : VPS (DigitalOcean, Vultr, Linode)

### 1. Créer un serveur
- Ubuntu 22.04 LTS
- 1GB RAM minimum (5$/mois)

### 2. Installation LAMP/LEMP
```bash
# Se connecter en SSH
ssh root@votre-ip

# Mettre à jour
apt update && apt upgrade -y

# Installer Apache, PHP 8.2, MySQL
apt install apache2 php8.2 php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip mysql-server git unzip -y

# Installer Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

### 3. Cloner le projet
```bash
cd /var/www/
git clone https://github.com/Pablo-100/synf_project.git
cd synf_project
composer install --no-dev --optimize-autoloader
```

### 4. Configurer Apache
```bash
nano /etc/apache2/sites-available/synf_project.conf
```

Ajoutez :
```apache
<VirtualHost *:80>
    ServerName votre-domaine.com
    DocumentRoot /var/www/synf_project/public
    
    <Directory /var/www/synf_project/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

```bash
# Activer le site
a2ensite synf_project
a2enmod rewrite
systemctl restart apache2
```

### 5. Configurer MySQL
```bash
mysql -u root -p
```

```sql
CREATE DATABASE synf_project CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'synf_user'@'localhost' IDENTIFIED BY 'mot_de_passe_fort';
GRANT ALL PRIVILEGES ON synf_project.* TO 'synf_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 6. Configurer l'application
```bash
cd /var/www/synf_project
cp .env .env.local
nano .env.local
```

Modifiez :
```
APP_ENV=prod
APP_DEBUG=0
APP_SECRET=[générer avec: php -r "echo bin2hex(random_bytes(32));"]
DATABASE_URL="mysql://synf_user:mot_de_passe_fort@127.0.0.1:3306/synf_project"
```

### 7. Permissions
```bash
chown -R www-data:www-data /var/www/synf_project
chmod -R 775 /var/www/synf_project/var
chmod -R 775 /var/www/synf_project/public/uploads
```

### 8. Migrations
```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

### 9. SSL avec Let's Encrypt (GRATUIT)
```bash
apt install certbot python3-certbot-apache -y
certbot --apache -d votre-domaine.com
```

✅ **Votre app est en ligne avec HTTPS !**

---

## 🔐 Checklist Post-Déploiement

- [ ] HTTPS activé (SSL)
- [ ] `APP_ENV=prod`
- [ ] `APP_DEBUG=0`
- [ ] APP_SECRET unique généré
- [ ] Base de données configurée
- [ ] Migrations appliquées
- [ ] Cache vidé : `php bin/console cache:clear --env=prod`
- [ ] Permissions correctes (775 pour var/)
- [ ] .env.local non commité
- [ ] Tests de sécurité effectués
- [ ] Backup de la base configuré

---

## 🧪 Tester votre déploiement

### 1. Vérifier que l'app fonctionne
```
https://votre-domaine.com
```

### 2. Tester la sécurité
```bash
# Headers de sécurité
curl -I https://votre-domaine.com

# Cherchez:
# X-XSS-Protection: 1; mode=block
# X-Frame-Options: DENY
# Strict-Transport-Security (si HTTPS)
```

### 3. Tester XSS
Essayez de soumettre :
```html
<script>alert('XSS')</script>
```
Doit être rejeté ou échappé.

### 4. Tester SQL Injection
Dans la recherche :
```
' OR '1'='1
```
Doit retourner des résultats normaux, pas d'erreur SQL.

---

## 📊 Monitoring (Optionnel)

### Uptime Monitoring gratuit
- **UptimeRobot** : https://uptimerobot.com (50 monitors gratuits)
- **Freshping** : https://freshping.io (50 checks gratuits)

### Logs
```bash
# Voir les erreurs en temps réel
tail -f var/log/prod.log

# Sur Heroku
heroku logs --tail

# Sur Railway
railway logs
```

---

## 💰 Coûts Estimés

| Plateforme | Prix/mois | Base de données | SSL | Backup |
|------------|-----------|-----------------|-----|--------|
| Railway.app | Gratuit (500h) → 5$ | MySQL inclus | ✅ | ❌ |
| Heroku | Gratuit limité → 7$ | PostgreSQL 1GB | ✅ | ✅ |
| InfinityFree | **Gratuit** | MySQL illimité | ⚠️ | ❌ |
| DigitalOcean | 6$/mois | À installer | ✅ | ➕ |
| Vultr | 6$/mois | À installer | ✅ | ➕ |

**Recommandation** : Railway.app pour commencer (le plus simple)

---

## 🆘 Support

En cas de problème :
1. Vérifiez les logs : `var/log/prod.log`
2. Vérifiez les permissions : `ls -la var/`
3. Videz le cache : `php bin/console cache:clear --env=prod`
4. Vérifiez la configuration : `.env.prod` ou `.env.local`

**Commandes de debug :**
```bash
# Voir la configuration
php bin/console debug:config

# Voir les routes
php bin/console debug:router

# Voir les services
php bin/console debug:container
```

✅ **Votre application Symfony est maintenant sécurisée et prête pour la production !**
