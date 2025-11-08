# 🚀 Déploiement en Un Clic

Déployez **FreshMarket** instantanément sur des plateformes gratuites avec un simple clic !

---

## ⚡ Déploiement Instantané

### Railway.app ⭐ (Recommandé)

[![Deploy on Railway](https://railway.app/button.svg)](https://railway.app/template/your-template)

**Ce qui sera créé automatiquement :**
- ✅ Application web hébergée
- ✅ Base de données MySQL
- ✅ Variables d'environnement configurées
- ✅ SSL/HTTPS gratuit
- ✅ Domaine gratuit : `votre-app.up.railway.app`

**Durée** : 5 minutes

---

### Render.com

[![Deploy to Render](https://render.com/images/deploy-to-render-button.svg)](https://render.com/deploy)

**Ce qui sera créé :**
- ✅ Application web hébergée
- ✅ SSL/HTTPS gratuit
- ✅ Domaine gratuit : `votre-app.onrender.com`

⚠️ **Note** : Vous devrez configurer une base MySQL externe (PlanetScale recommandé)

**Durée** : 10 minutes

---

## 📋 Étapes Post-Déploiement

Après le déploiement automatique :

### 1. Configurer la Base de Données

#### Option A : Utiliser Railway MySQL (si déployé sur Railway)
La base de données est automatiquement créée et connectée ! ✅

#### Option B : Utiliser PlanetScale (gratuit)
1. Créez un compte sur https://planetscale.com
2. Créez une nouvelle base de données
3. Copiez la `DATABASE_URL`
4. Dans Railway/Render, mettez à jour la variable `DATABASE_URL`

### 2. Importer les Données

**Via Railway CLI :**
```bash
# Se connecter
railway login

# Lier le projet
railway link

# Importer la base
railway run mysql -h [HOST] -u [USER] -p[PASSWORD] [DATABASE] < database.sql
```

**Via PlanetScale :**
```bash
# Installer pscale CLI
brew install planetscale/tap/pscale  # macOS
# ou
scoop install pscale  # Windows

# Se connecter
pscale auth login

# Importer
pscale database import [DATABASE_NAME] < database.sql
```

### 3. Vérifier les Variables d'Environnement

Assurez-vous que ces variables sont définies :

| Variable | Valeur | Description |
|----------|--------|-------------|
| `APP_ENV` | `prod` | Environnement de production |
| `APP_DEBUG` | `0` | Désactiver le mode debug |
| `APP_SECRET` | Auto-généré | Clé secrète Symfony |
| `DATABASE_URL` | Auto-configuré | Connexion à la base |

### 4. Tester Votre Application

1. Cliquez sur le lien fourni après le déploiement
2. Vous devriez voir la page d'accueil de FreshMarket
3. Testez la connexion avec les comptes de test :

**Admin :**
```
Email: admin@example.com
Mot de passe: admin123
```

**User :**
```
Email: user@example.com
Mot de passe: admin123
```

---

## 🎯 Méthode Alternative : CLI

Si le déploiement en un clic ne fonctionne pas, utilisez la méthode CLI :

### Railway (La plus simple)

```bash
# 1. Installer Railway CLI
npm install -g @railway/cli

# 2. Se connecter
railway login

# 3. Initialiser et déployer
railway init
railway up

# 4. Ajouter MySQL
railway add --database mysql

# 5. Ouvrir l'app
railway open
```

### Render

1. Allez sur https://render.com
2. Connectez votre repository GitHub
3. Suivez les instructions dans [RENDER_DEPLOY.md](RENDER_DEPLOY.md)

---

## 🌐 Obtenir un Domaine Personnalisé Gratuit

Après le déploiement, vous pouvez ajouter un domaine personnalisé :

### Option 1 : Utiliser le sous-domaine fourni
- Railway : `votre-app.up.railway.app`
- Render : `votre-app.onrender.com`

✅ **Déjà configuré avec SSL !**

### Option 2 : Domaine personnalisé gratuit

1. **Obtenir un domaine gratuit :**
   - Freenom : https://www.freenom.com (`.tk`, `.ml`, `.ga`)
   - eu.org : https://nic.eu.org (`.eu.org`)
   - DuckDNS : https://www.duckdns.org (`.duckdns.org`)

2. **Configurer les DNS :**
   
   Pour Railway :
   ```bash
   railway domain add votre-domaine.tk
   ```
   
   Pour Render :
   - Dashboard → Settings → Custom Domains
   - Ajoutez votre domaine

3. **Configurer Freenom DNS :**
   ```
   Type: CNAME
   Name: @
   Target: votre-app.up.railway.app (ou .onrender.com)
   TTL: 14400
   ```

⏱️ **Propagation** : 10 minutes à 48 heures

---

## 🔧 Dépannage

### L'application ne démarre pas

**Railway :**
```bash
railway logs
```

**Render :**
- Dashboard → Logs (onglet en temps réel)

### Erreur 500

1. Vérifiez les logs
2. Vérifiez que `APP_ENV=prod` et `APP_DEBUG=0`
3. Vérifiez la connexion à la base de données

### Base de données vide

Importez le fichier `database.sql` :
```bash
# Via Railway
railway run mysql -h [HOST] -u [USER] -p < database.sql

# Via ligne de commande directe
mysql -h [HOST] -u [USER] -p [DATABASE] < database.sql
```

---

## 📊 Monitoring et Maintenance

### Uptime Monitoring (Gratuit)

- **UptimeRobot** : https://uptimerobot.com (50 monitors gratuits)
- **Freshping** : https://freshping.io
- **Better Uptime** : https://betteruptime.com

Configurez des alertes pour être notifié si votre site tombe en panne.

### Logs

**Railway :**
```bash
railway logs --follow
```

**Render :**
Dashboard → Logs

---

## 💡 Optimisations

### Éviter la Mise en Veille (Render)

Render met les apps gratuites en veille après 15 minutes d'inactivité.

**Solution :**
1. Utilisez un service de ping gratuit (UptimeRobot)
2. Configurez un ping toutes les 10 minutes vers votre URL

### Améliorer les Performances

1. **Cache :**
   ```bash
   php bin/console cache:clear --env=prod
   ```

2. **Assets :**
   ```bash
   php bin/console assets:install --env=prod
   ```

3. **Opcache :**
   Activé automatiquement en production

---

## 🎉 C'est Tout !

Votre application **FreshMarket** est maintenant :
- ✅ En ligne et accessible publiquement
- ✅ Sécurisée avec HTTPS
- ✅ Hébergée gratuitement
- ✅ Prête à être utilisée !

---

## 📚 Ressources Supplémentaires

- 🌟 [FREE_DOMAIN_GUIDE.md](FREE_DOMAIN_GUIDE.md) - Guide complet des domaines gratuits
- 📖 [DEPLOYMENT.md](DEPLOYMENT.md) - Guide de déploiement détaillé
- 🎯 [RENDER_DEPLOY.md](RENDER_DEPLOY.md) - Guide spécifique Render
- 📘 [README.md](README.md) - Documentation principale

---

## 🤝 Support

Besoin d'aide ?

- 📧 Email : mustaphaamintbini@gmail.com
- 🐛 Issues : [GitHub Issues](https://github.com/Pablo-100/synf_project/issues)
- 💬 Discussions : [GitHub Discussions](https://github.com/Pablo-100/synf_project/discussions)

---

## 🌟 Partager Votre Projet

Une fois déployé, partagez-le :

```markdown
🎉 Mon projet FreshMarket est en ligne !
🔗 https://votre-app.up.railway.app
⭐ Donnez une étoile sur GitHub : https://github.com/Pablo-100/synf_project
```

---

✨ **Développé avec ❤️ par Mustapha Amine TBINI**
