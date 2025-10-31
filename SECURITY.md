# 🔒 GUIDE DE SÉCURITÉ - Symfony Application

## ✅ Protections Implémentées

### 1. Protection XSS (Cross-Site Scripting)

#### Backend (PHP)
- ✅ **SecurityService** : Service dédié à la validation et sanitization
  - Méthode `sanitizeString()` : Supprime HTML et encode les caractères spéciaux
  - Méthode `detectXss()` : Détecte les tentatives d'injection de scripts
  - Méthode `validateAndSanitize()` : Validation complète des entrées

- ✅ **SecurityHeadersSubscriber** : Headers HTTP de sécurité automatiques
  - `X-XSS-Protection: 1; mode=block`
  - `Content-Security-Policy` : Politique stricte d'exécution des scripts
  - `X-Content-Type-Options: nosniff`

#### Frontend (Twig)
- ✅ Échappement automatique activé par défaut
- ✅ Utilisation de filtres Twig (`|e`, `|escape`)
- ✅ Validation côté client avec JavaScript

**Exemple d'utilisation :**
```php
// Dans un contrôleur
$input = $request->request->get('comment');
$cleanInput = $securityService->validateAndSanitize($input, 500);

if ($cleanInput === null) {
    throw new BadRequestException('Contenu suspect détecté');
}
```

### 2. Protection SQL Injection

#### Doctrine ORM
- ✅ **Query Builder** : Toutes les requêtes utilisent des paramètres bindés
- ✅ **Prepared Statements** : Aucune concaténation de chaînes SQL
- ✅ **Type Hinting** : Validation des types de données

#### Validation supplémentaire
- ✅ **SecurityService::detectSqlInjection()** : Détection de patterns suspects
- ✅ **sanitizeSearchQuery()** : Nettoyage des requêtes de recherche

**Exemple sécurisé (ProductRepository) :**
```php
public function searchProducts(string $query): array
{
    return $this->createQueryBuilder('p')
        ->andWhere('p.nom LIKE :query OR p.description LIKE :query')
        ->setParameter('query', '%'.$query.'%') // ✅ Paramètre bindé
        ->getQuery()
        ->getResult();
}
```

### 3. Protection CSRF (Cross-Site Request Forgery)

#### Configuration
- ✅ **framework.yaml** : `csrf_protection: ~` activée
- ✅ **security.yaml** : `enable_csrf: true` pour les formulaires de login
- ✅ Tokens CSRF automatiques dans tous les formulaires Symfony

#### Utilisation dans les formulaires
```twig
<form method="post">
    {{ form_start(form) }}
    {# Token CSRF automatiquement inclus #}
    {{ form_end(form) }}
</form>
```

### 4. Sécurité des Sessions

#### Configuration (framework.yaml)
```yaml
session:
    cookie_secure: auto      # HTTPS uniquement en production
    cookie_httponly: true    # Inaccessible par JavaScript
    cookie_samesite: lax     # Protection CSRF
```

### 5. Sécurité des Mots de Passe

- ✅ **Algorithm: auto** : Utilise Argon2i ou bcrypt (le plus fort disponible)
- ✅ **Cost factor** : Optimisé pour la sécurité (12+ rounds)
- ✅ **Hashing automatique** : Via UserPasswordHasher

### 6. Headers de Sécurité HTTP

Tous ajoutés via `SecurityHeadersSubscriber` :

| Header | Valeur | Protection |
|--------|--------|------------|
| X-XSS-Protection | 1; mode=block | XSS |
| X-Frame-Options | DENY | Clickjacking |
| X-Content-Type-Options | nosniff | MIME Sniffing |
| Referrer-Policy | strict-origin-when-cross-origin | Fuite d'infos |
| Content-Security-Policy | Politique stricte | XSS/Injection |
| Strict-Transport-Security | max-age=31536000 | Force HTTPS |

### 7. Validation des Uploads de Fichiers

```php
// Dans SecurityService
public function validateFileExtension(string $filename, array $allowedExtensions): bool
{
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($extension, array_map('strtolower', $allowedExtensions), true);
}

public function generateSecureFilename(string $filename): string
{
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $hash = bin2hex(random_bytes(16));
    return $hash . '.' . strtolower($extension);
}
```

### 8. Contrôle d'Accès (ACL)

#### Configuration (security.yaml)
```yaml
access_control:
    - { path: ^/admin, roles: ROLE_ADMIN }
    - { path: ^/profile, roles: ROLE_USER }
    - { path: ^/cart, roles: ROLE_USER }
```

## 🚀 Déploiement en Production

### 1. Préparer l'environnement

```bash
# 1. Passer en mode production
php bin/console cache:clear --env=prod --no-debug

# 2. Installer les dépendances (sans dev)
composer install --no-dev --optimize-autoloader

# 3. Générer une clé secrète unique
php -r "echo bin2hex(random_bytes(32));"
# Copiez le résultat dans APP_SECRET (.env.prod)

# 4. Configurer la base de données
# Éditez .env.prod avec vos vraies informations
```

### 2. Sécuriser les fichiers sensibles

```bash
# Ne JAMAIS commiter ces fichiers:
# .env.local
# .env.prod.local
# config/secrets/prod/

# Ajoutez dans .gitignore (déjà fait):
/.env.local
/.env.*.local
/config/secrets/prod/prod.decrypt.private.php
/var/
/vendor/
```

### 3. Configurer le serveur web

#### Apache (.htaccess - déjà présent)
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php [QSA,L]

# Headers de sécurité (backup si PHP non disponible)
Header set X-Content-Type-Options "nosniff"
Header set X-Frame-Options "DENY"
Header set X-XSS-Protection "1; mode=block"
```

#### Nginx (exemple de configuration)
```nginx
server {
    listen 80;
    server_name votre-domaine.com;
    root /var/www/synf_project/public;

    # Sécurité
    add_header X-Frame-Options "DENY" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ ^/index\.php(/|$) {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_split_path_info ^(.+\.php)(/.*)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $realpath_root;
        internal;
    }

    location ~ \.php$ {
        return 404;
    }
}
```

### 4. Permissions des dossiers

```bash
# Sur Linux/macOS
chmod -R 775 var/cache var/log public/uploads
chown -R www-data:www-data var/cache var/log public/uploads

# Sur Windows (déjà configuré)
# Les permissions sont gérées par IIS/Apache
```

### 5. HTTPS (SSL/TLS)

**OBLIGATOIRE en production !**

Options gratuites :
- **Let's Encrypt** : Certificats SSL gratuits et automatiques
- **Cloudflare** : SSL gratuit + CDN + protection DDoS

```bash
# Avec Certbot (Let's Encrypt)
sudo certbot --apache -d votre-domaine.com
```

## 🌐 Hébergement Gratuit

### Options recommandées :

1. **Railway.app** (Recommandé)
   - ✅ Gratuit pour commencer
   - ✅ Support MySQL
   - ✅ Déploiement Git automatique
   - ✅ HTTPS automatique
   - https://railway.app

2. **Fly.io**
   - ✅ Gratuit jusqu'à 3 apps
   - ✅ Support Symfony
   - ✅ SSL automatique
   - https://fly.io

3. **Heroku** (avec PostgreSQL)
   - ✅ Plan gratuit limité
   - ✅ Add-ons disponibles
   - https://heroku.com

4. **PlanetScale** (Base de données)
   - ✅ MySQL gratuit (5GB)
   - ✅ Backups automatiques
   - https://planetscale.com

5. **InfinityFree** (Hosting PHP traditionnel)
   - ✅ 5GB d'espace
   - ✅ MySQL illimité
   - ✅ Support PHP 8.x
   - https://infinityfree.net

## 🛡️ Checklist de Sécurité avant Déploiement

- [ ] `APP_ENV=prod` dans .env.prod
- [ ] `APP_DEBUG=0` dans .env.prod
- [ ] APP_SECRET généré avec random_bytes(32)
- [ ] Base de données configurée avec mot de passe fort
- [ ] HTTPS activé (certificat SSL)
- [ ] Fichiers sensibles dans .gitignore
- [ ] Permissions correctes (775 pour var/, public/uploads/)
- [ ] Cache vidé et optimisé
- [ ] Migrations de base de données appliquées
- [ ] Tests de sécurité effectués
- [ ] Sauvegarde de la base de données configurée

## 🧪 Tester la Sécurité

### Tests XSS
```bash
# Essayez d'entrer ceci dans un formulaire:
<script>alert('XSS')</script>
<img src=x onerror=alert('XSS')>

# Résultat attendu: Rejeté ou échappé
```

### Tests SQL Injection
```bash
# Dans la recherche:
' OR '1'='1
'; DROP TABLE users; --

# Résultat attendu: Recherche normale, aucune exécution SQL
```

### Tests CSRF
```bash
# Essayez de soumettre un formulaire sans le token CSRF
# Résultat attendu: Erreur 400/403
```

## 📱 Responsive Design

✅ Bootstrap 5.3 utilisé avec breakpoints:
- **xs**: < 576px (mobile)
- **sm**: ≥ 576px (mobile landscape)
- **md**: ≥ 768px (tablette)
- **lg**: ≥ 992px (desktop)
- **xl**: ≥ 1200px (large desktop)

Tous les templates sont responsive avec media queries personnalisées.

## 📞 Support et Maintenance

### Logs à surveiller :
- `var/log/prod.log` : Erreurs de production
- `var/log/dev.log` : Erreurs de développement
- Logs du serveur web (Apache/Nginx)

### Commandes utiles :
```bash
# Voir les erreurs récentes
tail -f var/log/prod.log

# Vérifier la sécurité
php bin/console security:check

# Mettre à jour les dépendances
composer update
```

## 🎯 Résumé

Votre application Symfony est maintenant sécurisée contre :
- ✅ XSS (Cross-Site Scripting)
- ✅ SQL Injection
- ✅ CSRF (Cross-Site Request Forgery)
- ✅ Clickjacking
- ✅ MIME Sniffing
- ✅ Session Hijacking
- ✅ Uploads de fichiers malveillants

Elle est responsive et prête pour la production !
