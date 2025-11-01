# 🚀 Déploiement sur Render.com

## ⚠️ Important : Configuration de la Base de Données

Render.com (plan gratuit) ne supporte pas MySQL nativement. Vous avez 3 options :

### Option 1 : Utiliser PlanetScale (MySQL Gratuit) ⭐ Recommandé

1. Créez un compte sur https://planetscale.com
2. Créez une nouvelle base de données
3. Obtenez la `DATABASE_URL` (format : `mysql://user:pass@host:3306/dbname`)
4. Importez votre fichier `database.sql`
5. Dans Render.com, ajoutez la variable `DATABASE_URL`

### Option 2 : Utiliser Railway.app (MySQL + Hébergement)

Railway.app offre MySQL et l'hébergement en un seul endroit (plus simple) :

```bash
npm install -g @railway/cli
railway login
railway init
railway up
```

### Option 3 : Modifier pour PostgreSQL

Render.com offre PostgreSQL gratuitement. Modifiez votre projet :

```bash
composer require symfony/orm-pack
composer require doctrine/postgresql-driver
```

Puis modifiez `DATABASE_URL` pour utiliser PostgreSQL.

---

## 📝 Configuration Render.com

### Variables d'Environnement Requises

Ajoutez ces variables dans le Dashboard Render :

| Variable | Valeur | Description |
|----------|--------|-------------|
| `APP_ENV` | `prod` | Environnement de production |
| `APP_DEBUG` | `0` | Désactiver le mode debug |
| `APP_SECRET` | `[généré automatiquement]` | Clé secrète Symfony |
| `DATABASE_URL` | `mysql://user:pass@host:3306/dbname` | URL de connexion à la base |
| `DEFAULT_URI` | `https://votre-app.onrender.com` | URL de votre application |
| `MESSENGER_TRANSPORT_DSN` | `doctrine://default?auto_setup=0` | Configuration Messenger |
| `MAILER_DSN` | `null://null` | Configuration mailer |

### Générer APP_SECRET

```bash
php -r "echo bin2hex(random_bytes(32));"
```

---

## 🔧 Étapes de Déploiement

### 1. Préparer la Base de Données

Utilisez PlanetScale ou une autre base MySQL externe :

```sql
-- Importez votre database.sql
mysql -h HOST -u USER -p DATABASE < database.sql
```

### 2. Créer le Service sur Render

1. Allez sur https://dashboard.render.com
2. Cliquez sur **"New +"** → **"Web Service"**
3. Connectez votre repository GitHub : `Pablo-100/synf_project`
4. Configuration :
   - **Name** : `freshmarket`
   - **Environment** : `Docker`
   - **Region** : `Frankfurt` (EU)
   - **Branch** : `main`
   - **Dockerfile Path** : `./Dockerfile`

### 3. Configurer les Variables

Dans l'onglet **Environment** :

```
APP_ENV=prod
APP_DEBUG=0
APP_SECRET=32c8f8e9d5a7b2f1e4d3c2a1f9e8d7c6b5a4f3e2d1c0b9a8f7e6d5c4b3a2f1e0
DATABASE_URL=mysql://user:password@host.planetscale.com:3306/synf_project?serverVersion=8.0&charset=utf8mb4
DEFAULT_URI=https://votre-app.onrender.com
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
MAILER_DSN=null://null
```

### 4. Déployer

Cliquez sur **"Create Web Service"** et attendez le build.

---

## ✅ Vérification

Une fois déployé, testez :

- ✅ Page d'accueil : `https://votre-app.onrender.com`
- ✅ Login : `https://votre-app.onrender.com/login`
- ✅ Produits : `https://votre-app.onrender.com/products`

### Comptes de Test

**Admin** :
```
Email: admin@freshmarket.com
Password: admin123
```

**User** :
```
Email: user@freshmarket.com
Password: user123
```

---

## 🐛 Dépannage

### Erreur 500

Vérifiez les logs dans le Dashboard Render :

```bash
# Dans l'onglet "Logs"
```

### Erreur de Cache

Les permissions sont automatiquement configurées dans `docker-entrypoint.sh`.

### Erreur de Base de Données

Vérifiez que `DATABASE_URL` est correct et que la base est accessible depuis Render.

---

## 🎯 Alternative Recommandée : Railway.app

Si vous avez des problèmes avec Render, utilisez Railway.app (plus simple, MySQL inclus) :

```bash
npm install -g @railway/cli
railway login
railway init
railway up
```

✅ Tout est configuré automatiquement (MySQL + App + HTTPS) !

---

## 📞 Support

Si vous avez des problèmes :

1. Vérifiez les logs Render
2. Testez localement avec Docker : `docker-compose up`
3. Ouvrez une issue sur GitHub
