# 🌐 Guide Complet : Domaine et Hébergement GRATUIT

Ce guide vous montre comment mettre votre projet **FreshMarket** en ligne **gratuitement** avec un domaine accessible à tous.

---

## 🎯 Options Disponibles

| Option | Type de Domaine | Déploiement | Difficulté | Temps |
|--------|----------------|-------------|------------|-------|
| 1. Railway.app | Sous-domaine gratuit `.up.railway.app` | ⚡ Automatique | ⭐ Facile | 5 min |
| 2. Render.com | Sous-domaine gratuit `.onrender.com` | ⚡ Automatique | ⭐ Facile | 10 min |
| 3. Vercel | Sous-domaine gratuit `.vercel.app` | ⚡ Automatique | ⭐ Facile | 5 min |
| 4. Railway + Domaine Gratuit | Domaine personnalisé `.tk/.ml/.ga` | 📦 Manuel | ⭐⭐ Moyen | 20 min |
| 5. GitHub Pages (Frontend) | Sous-domaine `.github.io` | 📦 Semi-auto | ⭐⭐ Moyen | 15 min |
| 6. InfinityFree | Sous-domaine `.rf.gd/.epizy.com` | 📦 Manuel | ⭐⭐⭐ Difficile | 30 min |

---

## ⚡ Option 1 : Railway.app (Recommandé ⭐)

**Domaine fourni** : `https://votre-app.up.railway.app`  
**Base de données** : MySQL inclus gratuitement  
**SSL** : Automatique et gratuit  

### Pourquoi Railway ?
- ✅ Le plus simple et rapide
- ✅ MySQL inclus (pas besoin de configuration externe)
- ✅ Déploiement en une commande
- ✅ SSL automatique
- ✅ 500 heures gratuites/mois (suffisant pour un petit projet)

### Étapes

#### 1. Installer Railway CLI
```bash
npm install -g @railway/cli
```

#### 2. Se connecter et déployer
```bash
cd /chemin/vers/synf_project

# Se connecter à Railway avec GitHub
railway login

# Initialiser le projet
railway init

# Déployer l'application
railway up
```

#### 3. Ajouter MySQL
```bash
# Ajouter une base de données MySQL
railway add --database mysql
```

Railway va automatiquement :
- Créer une base MySQL
- Générer la `DATABASE_URL`
- L'injecter dans votre application

#### 4. Configurer les variables d'environnement
```bash
# Via CLI
railway variables set APP_ENV=prod
railway variables set APP_DEBUG=0
railway variables set APP_SECRET=$(php -r "echo bin2hex(random_bytes(32));")

# Ou via le Dashboard : https://railway.app/dashboard
```

#### 5. Obtenir votre URL
```bash
railway open
```

✅ **C'est tout ! Votre application est en ligne sur :**  
`https://votre-app.up.railway.app`

### Personnaliser le sous-domaine Railway
Dans le Dashboard Railway :
1. Sélectionnez votre service
2. Settings → Domains
3. Generate Domain → Vous pouvez éditer le nom
4. Exemple : `freshmarket-pablo.up.railway.app`

---

## 🚀 Option 2 : Render.com

**Domaine fourni** : `https://votre-app.onrender.com`  
**Base de données** : PostgreSQL gratuit (MySQL externe nécessaire)  
**SSL** : Automatique et gratuit  

### Avantages
- ✅ Déploiement automatique depuis GitHub
- ✅ SSL inclus
- ✅ Interface simple

### Étapes

#### 1. Connecter GitHub
1. Allez sur https://render.com
2. Connectez-vous avec GitHub
3. Cliquez sur "New +" → "Web Service"
4. Sélectionnez votre repository `Pablo-100/synf_project`

#### 2. Configuration
```
Name: freshmarket
Environment: Docker
Branch: main
Dockerfile Path: ./Dockerfile
Region: Frankfurt (EU)
Instance Type: Free
```

#### 3. Variables d'environnement
Ajoutez dans l'onglet "Environment" :
```
APP_ENV=prod
APP_DEBUG=0
APP_SECRET=<générez avec: php -r "echo bin2hex(random_bytes(32));">
DATABASE_URL=mysql://user:pass@host:3306/dbname
```

⚠️ **Note** : Render ne fournit pas MySQL gratuit. Utilisez :
- **PlanetScale** (MySQL gratuit) : https://planetscale.com
- **Railway MySQL** : Créez juste la base sur Railway et utilisez son URL

#### 4. Déployer
Cliquez sur "Create Web Service" et attendez le build (5-10 min).

✅ **Votre app est en ligne sur :**  
`https://freshmarket.onrender.com`

---

## ⚡ Option 3 : Vercel

**Domaine fourni** : `https://votre-app.vercel.app`  
**Meilleur pour** : Applications avec frontend statique + API  

### Note
Vercel est optimisé pour Next.js et frontends statiques, mais peut héberger des API PHP via des fonctions serverless. Pour une app Symfony complète, préférez Railway ou Render.

### Étapes rapides
```bash
npm install -g vercel
vercel login
vercel
```

---

## 🆓 Option 4 : Domaine Personnalisé GRATUIT

Si vous voulez un vrai nom de domaine au lieu d'un sous-domaine, voici les options **gratuites** :

### 4A. Freenom - Domaines Gratuits

**Domaines disponibles** : `.tk`, `.ml`, `.ga`, `.cf`, `.gq`  
**Gratuit pendant** : 1 an (renouvelable)  

#### Étapes

1. **Créer un compte sur Freenom**
   - https://www.freenom.com
   - Cherchez votre nom de domaine (ex: `freshmarket.tk`)
   - Vérifiez la disponibilité

2. **Enregistrer le domaine**
   - Cliquez sur "Get it now"
   - Checkout (gratuit)
   - Période : 12 mois (maximum gratuit)

3. **Configurer les DNS**

   **Pour Railway :**
   - Dans Freenom → Manage Domain → Management Tools → Nameservers
   - Ajoutez un enregistrement CNAME :
     ```
     Type: CNAME
     Name: @
     Target: votre-app.up.railway.app
     ```

   **Pour Render :**
   - Ajoutez un enregistrement CNAME :
     ```
     Type: CNAME
     Name: @
     Target: votre-app.onrender.com
     ```

4. **Configurer sur Railway/Render**
   
   **Railway :**
   ```bash
   railway domain add freshmarket.tk
   ```

   **Render :**
   - Dashboard → Settings → Custom Domains
   - Ajoutez `freshmarket.tk`

⏱️ **Propagation DNS** : 10 minutes à 48 heures

✅ **Votre app est maintenant sur :**  
`https://freshmarket.tk`

### 4B. Alternatives Freenom

Si Freenom ne fonctionne pas dans votre région :

1. **DuckDNS** (sous-domaine gratuit)
   - https://www.duckdns.org
   - Domaine : `freshmarket.duckdns.org`
   - Redirection gratuite

2. **NO-IP** (sous-domaine gratuit)
   - https://www.noip.com
   - Domaine : `freshmarket.ddns.net`

3. **eu.org** (domaine gratuit)
   - https://nic.eu.org
   - Domaine : `freshmarket.eu.org`
   - Gratuit à vie (mais validation manuelle, peut prendre des jours)

---

## 📱 Option 5 : GitHub Pages (Pour démonstrations)

**Domaine** : `https://pablo-100.github.io/synf_project`  
**Meilleur pour** : Pages statiques, documentation, démos

### Note
GitHub Pages ne peut pas héberger une application PHP Symfony complète, mais vous pouvez y mettre :
- Documentation du projet
- Landing page
- Captures d'écran de l'application

### Étapes rapides
```bash
cd synf_project

# Créer une branche gh-pages
git checkout -b gh-pages

# Créer un index.html simple
echo '<html><head><meta http-equiv="refresh" content="0; url=https://votre-app.up.railway.app" /></head></html>' > index.html

git add index.html
git commit -m "Add GitHub Pages redirect"
git push origin gh-pages
```

Dans GitHub → Settings → Pages → Source → `gh-pages`

✅ **Page accessible sur :**  
`https://pablo-100.github.io/synf_project` (redirige vers votre app)

---

## 🏆 Recommandation Finale

### Pour commencer rapidement (5 minutes) :
```bash
# Option 1 : Railway
npm install -g @railway/cli
railway login
railway init
railway add --database mysql
railway up
```

✅ URL gratuite : `https://votre-app.up.railway.app`

### Pour un domaine personnalisé (20 minutes) :
1. Déployer sur Railway (5 min)
2. Enregistrer un domaine gratuit sur Freenom (10 min)
3. Configurer les DNS (5 min)

✅ URL personnalisée : `https://freshmarket.tk`

---

## 🎉 Checklist de Déploiement

- [ ] Compte créé sur Railway/Render
- [ ] Code poussé sur GitHub
- [ ] Application déployée
- [ ] Base de données configurée
- [ ] Variables d'environnement définies
- [ ] SSL activé (automatique)
- [ ] Application accessible publiquement
- [ ] (Optionnel) Domaine personnalisé configuré
- [ ] (Optionnel) Domaine personnalisé propagé

---

## 🔧 Dépannage

### L'application ne démarre pas
```bash
# Vérifier les logs
railway logs
# ou
render logs (dans le dashboard)
```

### Erreur de base de données
Vérifiez que `DATABASE_URL` est bien configurée :
```bash
railway variables
```

### Le domaine personnalisé ne fonctionne pas
1. Vérifiez la configuration DNS (peut prendre 48h)
2. Testez avec `dig freshmarket.tk` ou `nslookup freshmarket.tk`
3. Vérifiez que le domaine est ajouté dans Railway/Render

---

## 📞 Support

- **Railway** : https://railway.app/help
- **Render** : https://render.com/docs
- **Freenom** : https://www.freenom.com/support

---

## ⚠️ Limitations Gratuites

| Plateforme | Limitations |
|------------|-------------|
| Railway | 500 heures/mois, $5 de crédit |
| Render | 750 heures/mois, mise en veille après 15 min d'inactivité |
| Freenom | Renouvellement manuel chaque année |

💡 **Astuce** : Pour les projets sérieux, envisagez d'upgrader (5-7$/mois pour éviter la mise en veille)

---

## 🌟 Partager Votre Projet

Une fois déployé, partagez votre URL :

```markdown
🔗 **Application en ligne** : https://freshmarket.up.railway.app

📖 **Documentation** : https://github.com/Pablo-100/synf_project

⭐ Si vous aimez ce projet, donnez-lui une étoile sur GitHub !
```

---

✨ **Votre application est maintenant accessible à tous dans le monde entier !**
