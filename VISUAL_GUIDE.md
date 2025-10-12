# 🎯 GUIDE VISUEL DU PROJET

## 📐 Architecture Globale

```
┌─────────────────────────────────────────────────────────────┐
│                      NAVIGATEUR WEB                          │
│                    (Client HTTP)                             │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        │ HTTP Request
                        ▼
┌─────────────────────────────────────────────────────────────┐
│                   PUBLIC/INDEX.PHP                           │
│              (Point d'entrée Symfony)                        │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────────┐
│                     APP\KERNEL                               │
│            (Initialisation Symfony)                          │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────────┐
│                    ROUTING                                   │
│        (Analyse l'URL et trouve le contrôleur)              │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────────┐
│                 CONTROLLER                                   │
│     HomeController / ProductController                       │
│   (Logique métier, orchestration)                           │
└────────────┬─────────────────────────────┬──────────────────┘
             │                             │
             │ Lecture DB                  │ Rendu Template
             ▼                             ▼
┌─────────────────────┐       ┌──────────────────────────────┐
│   REPOSITORY        │       │         TWIG                 │
│  (Accès données)    │       │    (Moteur de template)      │
│                     │       │                              │
│ - findAll()         │       │  templates/                  │
│ - findById()        │       │    ├── base.html.twig        │
│ - custom queries    │       │    ├── home/                 │
└──────────┬──────────┘       │    └── product/              │
           │                  └──────────────┬───────────────┘
           │                                 │
           ▼                                 │ HTML Response
┌─────────────────────┐                     │
│     ENTITY          │                     │
│   (Modèle ORM)      │                     │
│                     │                     │
│ - User              │                     │
│ - Product           │                     │
│ - Reservation       │                     ▼
│ - Order             │       ┌──────────────────────────────┐
│ - OrderItem         │       │      NAVIGATEUR WEB          │
└──────────┬──────────┘       │    (Affichage HTML/CSS)      │
           │                  └──────────────────────────────┘
           │
           ▼
┌─────────────────────┐
│   BASE DE DONNÉES   │
│      MySQL          │
│                     │
│ Tables:             │
│ - user              │
│ - product           │
│ - reservation       │
│ - order             │
│ - order_item        │
└─────────────────────┘
```

## 🔄 Flux de Données - Exemple Pratique

### Scénario : Affichage de la liste des produits

```
1. Utilisateur accède à: http://localhost:8000/products
   │
   ▼
2. Routing Symfony identifie:
   Route: 'app_product_index'
   Controller: ProductController::index()
   │
   ▼
3. ProductController exécute:
   $products = $productRepository->findAvailable();
   │
   ▼
4. ProductRepository (Doctrine ORM):
   SELECT * FROM product 
   WHERE disponible = 1 AND stock > 0
   │
   ▼
5. Base de données MySQL retourne les résultats
   │
   ▼
6. Doctrine convertit en objets Product[]
   │
   ▼
7. Controller passe à Twig:
   return $this->render('product/index.html.twig', [
       'products' => $products
   ]);
   │
   ▼
8. Twig génère le HTML avec Bootstrap 5
   │
   ▼
9. HTML envoyé au navigateur de l'utilisateur
```

## 🗂️ Structure des Dossiers Détaillée

```
synf_project/
│
├── 📂 bin/                      # Scripts exécutables
│   └── console                  # Console Symfony (CLI)
│
├── 📂 config/                   # Configuration
│   ├── packages/               # Config des bundles
│   │   ├── doctrine.yaml       # ORM config
│   │   ├── routing.yaml        # Routes
│   │   ├── security.yaml       # Sécurité
│   │   └── twig.yaml           # Templates
│   ├── routes.yaml             # Routes principales
│   └── services.yaml           # Services et DI
│
├── 📂 migrations/               # Migrations DB (vide)
│
├── 📂 public/                   # Dossier public (web root)
│   ├── uploads/                # Images uploadées ⬅️ IMPORTANT
│   │   └── .gitkeep
│   └── index.php               # Point d'entrée
│
├── 📂 src/                      # Code source PHP
│   │
│   ├── 📂 Controller/          # Contrôleurs MVC
│   │   ├── HomeController.php
│   │   │   ├── index()  → /
│   │   │   ├── about()  → /about
│   │   │   └── contact() → /contact
│   │   │
│   │   └── ProductController.php
│   │       ├── index()     → /products
│   │       ├── show($id)   → /products/{id}
│   │       ├── category()  → /products/category/{cat}
│   │       └── search()    → /products/search?q=...
│   │
│   ├── 📂 Entity/              # Modèles (ORM)
│   │   ├── User.php
│   │   │   └── Relations: 1→N Reservation, 1→N Order
│   │   │
│   │   ├── Product.php
│   │   │   └── Relations: 1→N OrderItem
│   │   │
│   │   ├── Reservation.php
│   │   │   └── Relations: N→1 User
│   │   │
│   │   ├── Order.php
│   │   │   └── Relations: N→1 User, 1→N OrderItem
│   │   │
│   │   └── OrderItem.php
│   │       └── Relations: N→1 Order, N→1 Product
│   │
│   ├── 📂 Repository/          # Accès données
│   │   ├── UserRepository.php
│   │   │   ├── findByRole()
│   │   │   └── upgradePassword()
│   │   │
│   │   ├── ProductRepository.php
│   │   │   ├── findAvailable()
│   │   │   ├── findByCategory()
│   │   │   └── searchProducts()
│   │   │
│   │   ├── ReservationRepository.php
│   │   │   ├── findUpcomingReservations()
│   │   │   ├── findByUser()
│   │   │   └── countByStatus()
│   │   │
│   │   ├── OrderRepository.php
│   │   │   ├── findByUser()
│   │   │   ├── getTotalRevenue() ⭐
│   │   │   ├── findRecentOrders()
│   │   │   └── countByStatus()
│   │   │
│   │   └── OrderItemRepository.php
│   │       └── getMostOrderedProducts() ⭐
│   │
│   └── Kernel.php              # Noyau Symfony
│
├── 📂 templates/               # Vues Twig
│   ├── base.html.twig         # Layout principal
│   │   ├── <head> Bootstrap 5 + Icons
│   │   ├── <nav> Navigation responsive
│   │   ├── <main> Contenu dynamique
│   │   └── <footer> Pied de page
│   │
│   ├── 📂 home/
│   │   ├── index.html.twig    # Accueil
│   │   ├── about.html.twig    # À propos
│   │   └── contact.html.twig  # Contact
│   │
│   └── 📂 product/
│       ├── index.html.twig    # Liste produits
│       ├── show.html.twig     # Détail produit
│       ├── category.html.twig # Par catégorie
│       └── search.html.twig   # Résultats recherche
│
├── 📂 tests/                   # Tests unitaires
├── 📂 translations/            # Traductions
├── 📂 var/                     # Cache et logs
│   ├── cache/                 # Cache Symfony
│   └── log/                   # Fichiers logs
│
├── 📂 vendor/                  # Dépendances Composer
│
├── 📄 .env                     # Configuration env
├── 📄 .gitignore              # Fichiers ignorés Git
├── 📄 composer.json           # Dépendances PHP
├── 📄 composer.lock           # Versions exactes
├── 📄 symfony.lock            # Recettes Symfony
│
├── 📄 README.md               # Doc complète
├── 📄 QUICKSTART.md           # Guide rapide
├── 📄 PROJECT_SUMMARY.md      # Récapitulatif
├── 📄 INDEX.md                # Index doc
├── 📄 VISUAL_GUIDE.md         # Ce fichier
├── 📄 database.sql            # Script SQL
└── 📄 install.ps1             # Script installation
```

## 📊 Modèle de Données Visuel

```
┌────────────────────────────────────────────────────────────────┐
│                           USER                                  │
├──────────────┬──────────────┬──────────────┬──────────────────┤
│ PK: id       │ email        │ nom          │ created_at       │
│              │ UNIQUE       │ prenom       │                  │
│              │ password     │ telephone    │                  │
│              │ roles []     │ adresse      │                  │
└──────────────┴──────────────┴──────────────┴────────┬─────────┘
                                                       │
                          ┌────────────────────────────┼────────────┐
                          │                            │            │
                          │ 1                          │ 1          │
                          │                            │            │
                          │ N                          │ N          │
    ┌─────────────────────▼──────┐      ┌─────────────▼────────────┐
    │     RESERVATION            │      │        ORDER             │
    ├────────────────────────────┤      ├──────────────────────────┤
    │ PK: id                     │      │ PK: id                   │
    │ FK: user_id                │      │ FK: user_id              │
    │ date_reservation           │      │ numero_commande UNIQUE   │
    │ heure_reservation          │      │ montant_total            │
    │ nombre_personnes           │      │ statut                   │
    │ statut                     │      │ adresse_livraison        │
    │ - en_attente               │      │ - en_cours               │
    │ - confirmee                │      │ - validee                │
    │ - annulee                  │      │ - livree                 │
    │ - terminee                 │      │ - annulee                │
    │ commentaire                │      │ commentaire              │
    │ created_at                 │      │ created_at               │
    └────────────────────────────┘      │ validated_at             │
                                        └─────────┬────────────────┘
                                                  │ 1
                                                  │
                                                  │ N
                                     ┌────────────▼───────────────┐
                                     │      ORDER_ITEM            │
                                     ├────────────────────────────┤
                                     │ PK: id                     │
                                     │ FK: commande_id (order)    │
                                     │ FK: product_id             │◄───┐
                                     │ quantite                   │    │
                                     │ prix_unitaire              │    │
                                     └────────────────────────────┘    │
                                                                       │
                                                                       │ N
                                                                       │
                                                                       │ 1
                                     ┌─────────────────────────────────┘
                                     │
                                     │
                         ┌───────────▼────────────┐
                         │      PRODUCT           │
                         ├────────────────────────┤
                         │ PK: id                 │
                         │ nom                    │
                         │ description            │
                         │ prix                   │
                         │ image (filename)       │
                         │ categorie              │
                         │ - plat                 │
                         │ - boisson              │
                         │ - dessert              │
                         │ stock                  │
                         │ disponible (boolean)   │
                         │ created_at             │
                         │ updated_at             │
                         └────────────────────────┘
```

## 🎨 Interface Utilisateur - Wireframes

### Page d'Accueil
```
┌─────────────────────────────────────────────────────┐
│  [LOGO] Mon Site          [Accueil] [Produits] [+] │ ← Navigation
├─────────────────────────────────────────────────────┤
│                                                     │
│  ╔═══════════════════════════════════════════════╗ │
│  ║     Bienvenue sur Mon Site                    ║ │ ← Hero Section
│  ║  Découvrez notre sélection de produits...     ║ │
│  ║  [Voir les Produits]  [Créer un compte]       ║ │
│  ╚═══════════════════════════════════════════════╝ │
│                                                     │
│  ┌────────────┐  ┌────────────┐  ┌────────────┐   │
│  │   📅       │  │    🛒      │  │    🔒      │   │ ← Features
│  │ Réservation│  │  Commande  │  │  Sécurisé  │   │
│  │   Simple   │  │  en Ligne  │  │  Paiement  │   │
│  └────────────┘  └────────────┘  └────────────┘   │
│                                                     │
│  ⭐ Produits Disponibles                           │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐           │
│  │  Image   │ │  Image   │ │  Image   │           │ ← Produits
│  │  Nom     │ │  Nom     │ │  Nom     │           │
│  │  12.50 € │ │  14.00 € │ │  11.00 € │           │
│  │ [Détails]│ │ [Détails]│ │ [Détails]│           │
│  └──────────┘ └──────────┘ └──────────┘           │
│                                                     │
├─────────────────────────────────────────────────────┤
│  Footer: Liens | Contact | Réseaux sociaux         │ ← Footer
└─────────────────────────────────────────────────────┘
```

### Page Liste Produits
```
┌─────────────────────────────────────────────────────┐
│  Navigation                                         │
├─────────────────────────────────────────────────────┤
│  📂 Nos Produits                                    │
│                                                     │
│  Filtrer: [Tous] [Plats] [Boissons] [Desserts]    │
│  15 produit(s) trouvé(s)                           │
│                                                     │
│  ┌──────────────────┐ ┌──────────────────┐         │
│  │ Image            │ │ Image            │         │
│  │ Pizza Margherita │ │ Spaghetti        │         │
│  │ [plat] En stock  │ │ [plat] En stock  │         │
│  │ Pizza tradition..│ │ Pâtes italien... │         │
│  │ 12.50 €  [Détail]│ │ 11.00 €  [Détail]│         │
│  └──────────────────┘ └──────────────────┘         │
│                                                     │
│  ┌──────────────────┐ ┌──────────────────┐         │
│  │ Image            │ │ Image            │         │
│  │ Coca-Cola        │ │ Tiramisu         │         │
│  │ [boisson]        │ │ [dessert]        │         │
│  │ ...              │ │ ...              │         │
│  └──────────────────┘ └──────────────────┘         │
│                                                     │
└─────────────────────────────────────────────────────┘
```

### Page Détail Produit
```
┌─────────────────────────────────────────────────────┐
│  Accueil > Produits > Pizza Margherita              │ ← Breadcrumb
├──────────────────┬──────────────────────────────────┤
│                  │  Pizza Margherita                │
│   ┌──────────┐   │  [plat] ✓ Disponible             │
│   │          │   │                                  │
│   │  Image   │   │  12.50 €                         │
│   │ Produit  │   │  📦 Stock: 50 unités             │
│   │          │   │                                  │
│   └──────────┘   │  Description                     │
│                  │  Pizza traditionnelle avec...    │
│                  │                                  │
│                  │  📅 Ajouté le: 11/10/2025        │
│                  │                                  │
│                  │  ┌────────────────────────────┐  │
│                  │  │ Commander ce produit       │  │
│                  │  │ Quantité: [1] ▼            │  │
│                  │  │ [Ajouter au panier]        │  │
│                  │  └────────────────────────────┘  │
│                  │                                  │
│                  │  [← Retour à la liste]           │
└──────────────────┴──────────────────────────────────┘
```

## 🔐 Sécurité - Flow d'Authentification

```
                   ┌──────────────┐
                   │  Utilisateur │
                   └──────┬───────┘
                          │
                          ▼
                   ┌──────────────┐
                   │ Page Login   │
                   └──────┬───────┘
                          │ POST email/password
                          ▼
            ┌────────────────────────────┐
            │  Security Bundle           │
            │  (Symfony Authentication)  │
            └──────┬──────────────┬──────┘
                   │              │
         ✓ Valid   │              │ ✗ Invalid
                   ▼              ▼
        ┌──────────────┐   ┌────────────┐
        │ Load User    │   │  Error     │
        │ from DB      │   │  Message   │
        └──────┬───────┘   └─────┬──────┘
               │                  │
               ▼                  │
        ┌──────────────┐          │
        │ Check        │          │
        │ Password     │          │
        └──────┬───────┘          │
               │                  │
         ✓ OK  │                  │
               ▼                  │
        ┌──────────────┐          │
        │ Create       │          │
        │ Session      │          │
        └──────┬───────┘          │
               │                  │
               ▼                  │
        ┌──────────────┐          │
        │ Set Roles    │          │
        │ ROLE_USER    │          │
        │ ROLE_ADMIN   │          │
        └──────┬───────┘          │
               │                  │
               ▼                  ▼
        ┌────────────────────────────┐
        │  Redirect to Dashboard     │
        └────────────────────────────┘
```

## 📈 Exemple de Statistiques (Admin Dashboard)

```
┌─────────────────────────────────────────────────────┐
│  📊 Dashboard Administration                        │
├─────────────────────────────────────────────────────┤
│                                                     │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐         │
│  │ 1,245 €  │  │   156    │  │    23    │         │
│  │ Revenue  │  │ Commandes│  │ Réservat.│         │
│  │ +12%     │  │ Ce mois  │  │ En cours │         │
│  └──────────┘  └──────────┘  └──────────┘         │
│                                                     │
│  📈 Ventes par Mois                                │
│  ┌─────────────────────────────────────────┐       │
│  │ ████████████████░░░░░░░░░░░░░░░░░░░░░ │       │
│  │ Jan Feb Mar Apr May Jun Jul Aug Sep... │       │
│  └─────────────────────────────────────────┘       │
│                                                     │
│  🏆 Top 5 Produits                                 │
│  1. Pizza Margherita     - 89 ventes              │
│  2. Spaghetti Carbonara  - 67 ventes              │
│  3. Tiramisu             - 54 ventes              │
│  4. Coca-Cola            - 123 ventes             │
│  5. Lasagnes             - 45 ventes              │
│                                                     │
│  📅 Prochaines Réservations                        │
│  ┌────────────────────────────────────────┐        │
│  │ 12/10 - 19:30 - Table 4 - Dupont      │        │
│  │ 12/10 - 20:00 - Table 2 - Martin      │        │
│  │ 13/10 - 19:00 - Table 6 - Bernard     │        │
│  └────────────────────────────────────────┘        │
│                                                     │
└─────────────────────────────────────────────────────┘
```

## 🚀 Cycle de Vie d'une Requête

```
Temps  │ Action
───────┼────────────────────────────────────────
0ms    │ Navigateur envoie requête HTTP
       │ GET http://localhost:8000/products
       ▼
5ms    │ Apache/PHP reçoit la requête
       │ Execute public/index.php
       ▼
10ms   │ Symfony Kernel démarre
       │ Charge la configuration
       ▼
15ms   │ Routing trouve la route
       │ app_product_index → ProductController
       ▼
20ms   │ Controller instancié
       │ Dependency Injection (Repository)
       ▼
25ms   │ Repository query vers MySQL
       │ SELECT * FROM product WHERE...
       ▼
35ms   │ MySQL retourne résultats
       │ 15 produits trouvés
       ▼
40ms   │ Doctrine hydrate les objets
       │ Array de Product entities
       ▼
45ms   │ Controller passe data à Twig
       │ render('product/index.html.twig')
       ▼
60ms   │ Twig compile et génère HTML
       │ Boucle sur products, applique Bootstrap
       ▼
75ms   │ HTML retourné au navigateur
       │ HTTP 200 OK
       ▼
80ms   │ Navigateur affiche la page
       │ + charge CSS/JS externes (CDN)
       ▼
300ms  │ Page complètement chargée
       │ Prête pour interaction utilisateur
```

## 💡 Bonnes Pratiques Implémentées

### ✅ Code
- Namespaces PSR-4
- Type hints stricts
- Retour types déclarés
- Documentation PHPDoc
- Constantes pour statuts
- Méthodes utilitaires

### ✅ Base de Données
- Indexes sur foreign keys
- Unique constraints
- Cascade operations
- Timestamps automatiques
- Soft deletes prêts

### ✅ Sécurité
- Password hashing (bcrypt)
- CSRF protection
- SQL injection prevention (Doctrine)
- XSS protection (Twig escape)
- Role-based access control

### ✅ Performance
- Requêtes optimisées
- Eager loading relations
- Cache Symfony
- Asset CDN (Bootstrap)
- Pagination prête

---

✨ **Ce guide visuel vous aide à comprendre l'architecture complète du projet**
