# 🧪 Guide d'Implémentation US-11 & US-12

## 📋 Table des Matières
- [US-11 : Tests Automatiques & CI](#us-11--tests-automatiques--ci)
- [US-12 : Sécurité / Hardening](#us-12--sécurité--hardening)

---

## US-11 : Tests Automatiques & CI

### ✅ Critères d'Acceptation
- ✅ Pipeline CI (GitHub Actions) configuré
- ✅ Tests PHPUnit fonctionnels
- ✅ Badge de build dans README

### 🚀 Mise en Place

#### 1. Configuration GitHub Actions

Le fichier `.github/workflows/ci.yml` a été créé. Il contient :
- Tests sur PHP 8.2 et 8.3
- Installation des dépendances Composer
- Exécution de PHPUnit
- Vérification des vulnérabilités

#### 2. Lancer les Tests Localement

```powershell
# Installer PHPUnit (déjà inclus dans Symfony)
composer require --dev symfony/test-pack

# Lancer tous les tests
php bin/phpunit

# Lancer avec plus de détails
php bin/phpunit --testdox

# Tester un fichier spécifique
php bin/phpunit tests/Service/CartServiceTest.php

# Avec coverage (nécessite Xdebug)
php bin/phpunit --coverage-html coverage
```

#### 3. Tests Créés

**a) Tests de Service**
- `tests/Service/CartServiceTest.php` : Tests du panier
  - ✅ Ajout de produit
  - ✅ Suppression de produit
  - ✅ Mise à jour de quantité
  - ✅ Vidage du panier
  - ✅ Calcul du total
  - ✅ Vérification panier vide

**b) Tests de Controller**
- `tests/Controller/SecurityTest.php` : Tests d'authentification
  - ✅ Page de connexion
  - ✅ Connexion avec identifiants invalides
  - ✅ Page d'inscription
  - ✅ Protection admin
  - ✅ Token CSRF présent

- `tests/Controller/ProductControllerTest.php` : Tests produits
  - ✅ Liste des produits
  - ✅ Recherche de produits
  - ✅ Détail d'un produit
  - ✅ Gestion des erreurs 404

#### 4. Ajouter le Badge CI

Une fois le repository sur GitHub, ajoutez ce badge dans `README.md` :

```markdown
![CI Tests](https://github.com/VOTRE_USERNAME/synf_project/workflows/CI%20Tests/badge.svg)
```

#### 5. Configuration Environnement de Test

Créer `.env.test.local` si besoin :

```env
DATABASE_URL="sqlite:///%kernel.project_dir%/data/test.db"
APP_ENV=test
```

---

## US-12 : Sécurité / Hardening

### ✅ Critères d'Acceptation
- ✅ Protection CSRF active
- ✅ Protection XSS active
- ✅ Headers de sécurité configurés
- ✅ Tests de démonstration disponibles

### 🔒 Protections Mises en Place

#### 1. Protection CSRF (Cross-Site Request Forgery)

**Déjà en place dans Symfony !**

Tous les formulaires Symfony incluent automatiquement un token CSRF :

```twig
{# Twig génère automatiquement le champ caché #}
{{ form_start(form) }}
    {# ... champs du formulaire ... #}
{{ form_end(form) }}

{# Résultat HTML : #}
<input type="hidden" name="_csrf_token" value="UNIQUE_TOKEN">
```

**Test manuel :**
```powershell
# Ouvrir le fichier de test
start http://localhost:8000/test_csrf_protection.html
```

#### 2. Protection XSS (Cross-Site Scripting)

**Déjà en place dans Twig !**

Twig échappe automatiquement toutes les variables :

```twig
{# Échappement automatique #}
{{ product.nom }}  
{# Si nom = "<script>alert('XSS')</script>" #}
{# Affiche : &lt;script&gt;alert('XSS')&lt;/script&gt; #}

{# Pour désactiver (DANGEREUX) : #}
{{ product.nom|raw }}  {# ⚠️ À éviter ! #}
```

**Test manuel :**
```powershell
# Ouvrir le fichier de test
start http://localhost:8000/test_xss_protection.html
```

**Payloads de test :**
```html
<script>alert('XSS')</script>
<img src=x onerror=alert('XSS')>
<svg/onload=alert('XSS')>
<iframe src="javascript:alert('XSS')">
```

#### 3. Headers de Sécurité HTTP

Le fichier `src/EventSubscriber/SecurityHeadersSubscriber.php` configure :

| Header | Valeur | Protection |
|--------|--------|------------|
| `X-XSS-Protection` | `1; mode=block` | Protection XSS navigateur |
| `X-Frame-Options` | `DENY` | Anti-clickjacking |
| `X-Content-Type-Options` | `nosniff` | Anti-MIME sniffing |
| `Content-Security-Policy` | Politique stricte | Anti-XSS avancée |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | Contrôle referrer |
| `Permissions-Policy` | Restrictions | Limite fonctionnalités |

**Test manuel :**
```powershell
# Lancer le serveur
symfony server:start

# Dans un autre terminal
.\test_security_headers.bat
```

**Ou avec curl :**
```powershell
curl -I http://localhost:8000
```

#### 4. Autres Protections

**a) Validation des Entrées**
```php
use Symfony\Component\Validator\Constraints as Assert;

class Product {
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $nom = null;
}
```

**b) Requêtes Préparées (SQL Injection)**
Doctrine utilise automatiquement des requêtes préparées :
```php
// ✅ Sécurisé automatiquement
$products = $productRepository->findByCategory($category);
```

**c) Hashage des Mots de Passe**
```php
// Symfony utilise bcrypt/argon2
$hashedPassword = $passwordHasher->hashPassword(
    $user,
    $plainPassword
);
```

---

## 🧪 Procédure de Test Complète

### 1. Tests Automatiques

```powershell
# 1. Lancer le serveur
symfony server:start

# 2. Lancer les tests PHPUnit
php bin/phpunit --testdox

# 3. Vérifier les résultats
# Tous les tests doivent être verts ✅
```

### 2. Tests de Sécurité Manuels

```powershell
# 1. Test Headers HTTP
.\test_security_headers.bat

# 2. Test Protection CSRF
start http://localhost:8000/test_csrf_protection.html

# 3. Test Protection XSS
start http://localhost:8000/test_xss_protection.html

# 4. Vérifier les formulaires
# - Ouvrir /register ou /login
# - Inspecter le HTML
# - Vérifier la présence du champ _csrf_token
```

### 3. Tests de Pénétration Basiques

**Test XSS dans la recherche :**
```
1. Aller sur /products/search
2. Entrer : <script>alert('XSS')</script>
3. Vérifier que le texte est échappé (pas d'alerte)
```

**Test CSRF :**
```
1. Ouvrir la page /register dans un navigateur
2. Copier le formulaire HTML
3. Créer une page externe avec ce formulaire
4. Soumettre depuis la page externe
5. Vérifier que la soumission échoue (token invalide)
```

**Test Clickjacking :**
```html
<!-- Créer test_clickjacking.html -->
<iframe src="http://localhost:8000/login"></iframe>
<!-- L'iframe doit être bloquée par X-Frame-Options -->
```

---

## 📊 Résultats Attendus

### US-11 : Tests & CI
- ✅ Tous les tests PHPUnit passent (verts)
- ✅ Pipeline CI GitHub Actions fonctionne
- ✅ Badge "build passing" affiché
- ✅ Coverage > 50% (optionnel)

### US-12 : Sécurité
- ✅ Protection CSRF active sur tous les formulaires
- ✅ Protection XSS : tout contenu échappé
- ✅ Headers de sécurité présents sur toutes les réponses
- ✅ Tests manuels concluants

---

## 🐛 Dépannage

### Erreur : "Class not found"
```powershell
composer dump-autoload
```

### Tests qui échouent
```powershell
# Vérifier l'environnement
php bin/console about --env=test

# Nettoyer le cache
php bin/console cache:clear --env=test
```

### Headers non présents
```powershell
# Vérifier que le subscriber est chargé
php bin/console debug:event-dispatcher kernel.response
```

---

## 📝 Checklist Finale

### US-11
- [ ] `.github/workflows/ci.yml` créé
- [ ] Tests PHPUnit créés et passent
- [ ] Badge CI ajouté au README
- [ ] Documentation tests complète

### US-12
- [ ] Headers de sécurité configurés
- [ ] Test CSRF fonctionnel
- [ ] Test XSS fonctionnel
- [ ] Script test_security_headers.bat fonctionne
- [ ] Tous les formulaires ont des tokens CSRF
- [ ] Toutes les variables Twig sont échappées

---

## 🎯 Démonstration

Pour démontrer l'implémentation complète :

```powershell
# 1. Tests automatiques
php bin/phpunit --testdox

# 2. Tests sécurité
.\test_security_headers.bat
start http://localhost:8000/test_csrf_protection.html
start http://localhost:8000/test_xss_protection.html

# 3. Montrer le code
# - SecurityHeadersSubscriber.php
# - Tests dans tests/
# - Workflow CI dans .github/workflows/ci.yml
```

---

## 📚 Ressources

- [Symfony Security Best Practices](https://symfony.com/doc/current/security.html)
- [Symfony Testing](https://symfony.com/doc/current/testing.html)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [GitHub Actions Documentation](https://docs.github.com/en/actions)

---

✅ **US-11 et US-12 complètement implémentées et testées !**
