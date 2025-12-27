# Rapport Sécurité FreshMarket

Ce document détaille les mesures de sécurité mises en place pour protéger l'application contre les menaces courantes (OWASP Top 10).

## 1. Protection contre les menaces (Recommandation v2)

### ○ XSS (Cross-Site Scripting)
*   **Moteur de Template Twig** : Toutes les variables affichées dans les templates sont automatiquement échappées par défaut (`auto-escaping`).
*   **Content Security Policy (CSP)** : Un `SecurityHeadersSubscriber` injecte un header CSP strict qui restreint les sources d'exécution des scripts, empêchant l'injection de scripts tiers malveillants.
*   **Headers de sécurité** : Utilisation de `X-XSS-Protection: 1; mode=block` pour activer les filtres natifs des navigateurs.

### ○ SQL Injection
*   **Doctrine ORM** : L'utilisation de Doctrine garantit que toutes les requêtes utilisent des **requêtes préparées**. Les données utilisateurs ne sont jamais concaténées directement dans le SQL.
*   **Validation des Entités** : Les types de données sont strictement contrôlés au niveau des entités Symfony.

### ○ CSRF (Cross-Site Request Forgery)
*   **Activation Globale** : La protection CSRF est activée dans `config/packages/framework.yaml`.
*   **Protection des Formulaires** : Tous les formulaires Symfony incluent automatiquement un token CSRF masqué.
*   **Checkout & Panier** : Les actions critiques (comme la validation d'une commande) vérifient explicitement la validité du token.

## 2. Couche Sécurité Additionnelle (Back-end)

L'application utilise un `SecurityHeadersSubscriber` qui ajoute les en-têtes suivants à chaque réponse :
*   `X-Frame-Options: DENY` (anti-clickjacking)
*   `X-Content-Type-Options: nosniff` (anti-MIME sniffing)
*   `Referrer-Policy: strict-origin-when-cross-origin`
*   `HSTS` : Activé en production pour forcer le HTTPS.

## 3. Mode Production & Hébergement

### Configuration Production
1.  **Environnement** : Basculer `.env` sur `APP_ENV=prod`.
2.  **Performance** : Cache moteur Twig, cache Doctrine et AssetMapper compilé.
3.  **Scripts** : Utiliser `php bin/console asset-map:compile` pour optimiser les assets.

### Hébergement Recommandé
L'application est compatible avec tout hébergeur supportant PHP 8.2+ et MySQL :
*   **Hébergement Gratuit** :
    *   **AlwaysData** (Support natif Symfony/PHP).
    *   **Vercel** (Via le runtime PHP community).
*   **Hébergement Premium** :
    *   **Infomaniak / Hostinger** : Excellentes performances pour Symfony.
    *   **Platform.sh** : Conçu spécifiquement pour le workflow Symfony.

---
*Document généré le 27/12/2025*
