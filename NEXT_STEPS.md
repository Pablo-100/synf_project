# ✅ APRÈS L'INSTALLATION - PROCHAINES ÉTAPES

## 🎉 Ce qui est fait :
- ✅ Base de données créée avec phpMyAdmin
- ✅ Dossier uploads créé
- ✅ Serveur lancé sur http://localhost:8000

## 🌐 Accéder au Site

**Ouvrez votre navigateur et allez sur :**
```
http://localhost:8000
```

## 📱 Pages Disponibles

### Pages Publiques (fonctionnelles)
- **Accueil** : http://localhost:8000/
- **Produits** : http://localhost:8000/products
- **À propos** : http://localhost:8000/about
- **Contact** : http://localhost:8000/contact
- **Recherche** : http://localhost:8000/products/search?q=pizza
- **Catégories** :
  - http://localhost:8000/products/category/plat
  - http://localhost:8000/products/category/boisson
  - http://localhost:8000/products/category/dessert

## 🔐 Identifiants de Test

| Utilisateur | Email | Mot de passe | Rôle |
|-------------|-------|--------------|------|
| Admin | admin@example.com | admin123 | ROLE_ADMIN |
| Client | user@example.com | admin123 | ROLE_USER |

## 📊 Données Disponibles

- **15 produits** répartis en 3 catégories
- **2 utilisateurs** (admin + user)
- **1 réservation** d'exemple
- **1 commande** d'exemple avec 3 articles

## 🛠️ Développement - Prochaines Étapes

### 1. Créer le Système d'Authentification (15 minutes)

```powershell
# Créer le système de login
php bin/console make:auth

# Créer le formulaire d'inscription
php bin/console make:registration-form
```

**Suivez les instructions interactives :**
- Authenticator name: `LoginFormAuthenticator`
- Controller name: `SecurityController`
- Generate logout URL: `Yes`

### 2. Créer les Formulaires (30 minutes)

```powershell
# Formulaire pour les produits
php bin/console make:form ProductType Product

# Formulaire pour les réservations
php bin/console make:form ReservationType Reservation

# Formulaire pour les commandes
php bin/console make:form OrderType Order
```

### 3. Créer les Contrôleurs Admin (1-2 heures)

```powershell
# Dashboard admin
php bin/console make:controller Admin/DashboardController

# CRUD Produits
php bin/console make:crud Product

# Gestion des réservations
php bin/console make:controller Admin/ReservationController

# Gestion des commandes
php bin/console make:controller Admin/OrderController

# Gestion des utilisateurs
php bin/console make:controller Admin/UserController
```

### 4. Implémenter l'Upload d'Images (30 minutes)

**Créer un service d'upload :**

```php
// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger,
    ) {
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->targetDirectory, $fileName);
        } catch (FileException $e) {
            // Gérer l'exception
        }

        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
```

### 5. Ajouter les Statistiques au Dashboard (1 heure)

**Exemple de méthode dans le DashboardController :**

```php
#[Route('/admin/dashboard', name: 'admin_dashboard')]
public function index(
    OrderRepository $orderRepo,
    ReservationRepository $reservationRepo,
    OrderItemRepository $orderItemRepo
): Response {
    // Statistiques
    $stats = [
        'total_revenue' => $orderRepo->getTotalRevenue(),
        'orders_count' => $orderRepo->count([]),
        'pending_reservations' => $reservationRepo->countByStatus('en_attente'),
        'top_products' => $orderItemRepo->getMostOrderedProducts(5),
    ];
    
    return $this->render('admin/dashboard.html.twig', [
        'stats' => $stats,
    ]);
}
```

### 6. Ajouter des Graphiques (Chart.js)

**Dans votre template admin :**

```html
<!-- Ajouter Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<canvas id="salesChart"></canvas>

<script>
const ctx = document.getElementById('salesChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
        datasets: [{
            label: 'Ventes (€)',
            data: [1200, 1900, 3000, 2500, 2200, 3000],
            backgroundColor: 'rgba(54, 162, 235, 0.5)'
        }]
    }
});
</script>
```

## 🎨 Personnalisation de l'Interface

### Modifier le Logo et les Couleurs

**Éditer `templates/base.html.twig` :**

```twig
<style>
    :root {
        --primary-color: #votre-couleur;      /* Changer la couleur principale */
        --secondary-color: #votre-couleur;    /* Couleur secondaire */
    }
    .navbar-brand {
        /* Personnaliser le logo */
    }
</style>
```

### Ajouter Votre Logo

1. Placer votre image dans `public/images/logo.png`
2. Dans `base.html.twig` :

```twig
<a class="navbar-brand" href="{{ path('app_home') }}">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" height="40">
    Mon Site
</a>
```

## 📱 Test de l'Application

### Tester les Pages Actuelles

1. **Page d'accueil** → Doit afficher le hero et les produits
2. **Liste produits** → 15 produits visibles
3. **Détail produit** → Cliquer sur un produit
4. **Recherche** → Taper "pizza" dans la barre de recherche
5. **Filtres** → Cliquer sur "Plats", "Boissons", "Desserts"

### Ce qui Marche Déjà ✅
- Navigation responsive
- Liste des produits
- Détails des produits
- Recherche
- Filtrage par catégorie
- Footer avec liens
- Design Bootstrap 5

### Ce qui Reste à Faire 📝
- [ ] Authentification (make:auth)
- [ ] Inscription utilisateurs
- [ ] Profil utilisateur
- [ ] Panier d'achat
- [ ] Processus de commande
- [ ] Système de réservation (formulaire)
- [ ] Interface admin complète
- [ ] Upload d'images
- [ ] Dashboard avec statistiques

## 🐛 Dépannage

### Le serveur ne démarre pas ?
```powershell
# Vérifier si le port 8000 est occupé
netstat -ano | findstr :8000

# Utiliser un autre port
php -S localhost:8080 -t public
```

### Erreur de base de données ?
```powershell
# Vérifier MySQL dans XAMPP
# Ouvrir XAMPP Control Panel
# S'assurer que MySQL est démarré (vert)
```

### Page blanche ou erreur 500 ?
```powershell
# Vider le cache
Remove-Item -Path var\cache -Recurse -Force

# Régénérer l'autoload
composer dump-autoload
```

### Les produits ne s'affichent pas ?
- Vérifier que la base de données est bien importée dans phpMyAdmin
- Vérifier qu'il y a des données dans la table `product`
- Ouvrir les logs : `var/log/dev.log`

## 📚 Commandes Utiles

```powershell
# Lister toutes les routes
php bin/console debug:router

# Vider le cache
php bin/console cache:clear

# Lister les entités
php bin/console doctrine:mapping:info

# Créer un contrôleur
php bin/console make:controller NomController

# Créer une entité
php bin/console make:entity NomEntite

# Voir les erreurs en temps réel
Get-Content var\log\dev.log -Wait
```

## 🎯 Objectifs par Priorité

### Priorité 1 - Essentiel (2-3 heures)
1. ✅ Base de données fonctionnelle
2. ✅ Pages publiques fonctionnelles
3. 📝 Système d'authentification
4. 📝 CRUD Produits avec upload

### Priorité 2 - Important (2-3 heures)
5. 📝 Formulaire de réservation
6. 📝 Système de commande/panier
7. 📝 Dashboard admin basique

### Priorité 3 - Bonus (1-2 heures)
8. 📝 Statistiques avancées
9. 📝 Graphiques Chart.js
10. 📝 Personnalisation design

## 🎓 Pour la Présentation

### Démonstration suggérée (10 minutes)

1. **Introduction** (1 min)
   - Présenter le projet et objectifs
   
2. **Tour de l'interface** (3 min)
   - Page d'accueil
   - Navigation dans les produits
   - Recherche et filtres
   
3. **Architecture technique** (3 min)
   - Montrer le schéma de la base de données
   - Expliquer les 5 entités
   - Montrer quelques méthodes de repositories
   
4. **Code** (2 min)
   - Controller exemple
   - Entité avec relations
   - Template Twig
   
5. **Perspectives** (1 min)
   - Fonctionnalités futures
   - Possibilités d'extension

### Documents à Préparer
- [ ] Présentation PowerPoint/PDF (optionnel)
- [ ] Schéma de base de données (VISUAL_GUIDE.md)
- [ ] Captures d'écran du site
- [ ] Code source commenté

## 📞 Ressources

### Documentation
- **README.md** - Vue complète du projet
- **QUICKSTART.md** - Installation rapide
- **PROJECT_SUMMARY.md** - Récapitulatif technique
- **VISUAL_GUIDE.md** - Schémas et architecture

### Liens Utiles
- Symfony Docs: https://symfony.com/doc/current/
- Bootstrap 5: https://getbootstrap.com/
- Doctrine: https://www.doctrine-project.org/

---

✨ **Votre projet Symfony est opérationnel !**

Ouvrez **http://localhost:8000** dans votre navigateur pour voir le résultat ! 🚀
