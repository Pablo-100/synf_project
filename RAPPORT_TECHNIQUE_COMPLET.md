# Rapport Technique Détaillé du Projet Symfony

## Table des Matières
1. [Introduction](#introduction)
2. [Stack Technologique](#stack-technologique)
3. [Architecture Logicielle](#architecture-logicielle)
4. [Conception de la Base de Données](#conception-de-la-base-de-données)
    - [Diagramme de Classes (UML via TikZ)](#diagramme-de-classes)
5. [Fonctionnalités Clés](#fonctionnalités-clés)
    - [Authentification et Sécurité](#authentification-et-sécurité)
    - [Gestion du Panier et Commandes](#gestion-du-panier-et-commandes)
    - [Système de Réservation](#système-de-réservation)
6. [Diagrammes de Séquence](#diagrammes-de-séquence)
    - [Processus de Commande (TikZ)](#processus-de-commande)
7. [Déploiement et Environnement](#déploiement-et-environnement)
8. [Sécurité](#sécurité)
9. [Conclusion](#conclusion)

---

## 1. Introduction

Ce document présente un rapport technique détaillé du projet « **Synf Project** », une application web développée avec le framework **Symfony 7.3**. L'application est une plateforme e-commerce et de réservation permettant aux utilisateurs de s'inscrire, de consulter des produits, de passer des commandes et d'effectuer des réservations de services.

L'objectif de ce rapport est de fournir une vue exhaustive de l'architecture, du code source, des choix technologiques et des mécanismes de sécurité mis en œuvre.

## 2. Stack Technologique

Le projet repose sur des technologies modernes assurant performance, robustesse et maintenabilité :

*   **Langage Backend** : PHP 8.x
*   **Framework** : Symfony 7.3 (Dernière version stable)
*   **Base de Données** : MySQL / MariaDB (via Doctrine ORM)
*   **Frontend** :
    *   **Twig** : Moteur de templates.
    *   **Stimulus** : Framework JavaScript léger pour l'interactivité.
    *   **Symfony UX Turbo** : Pour une expérience Single-Page Application (SPA) sans rechargement complet.
    *   **AssetMapper** : Gestion moderne des assets sans Webpack Encore (Node.js optionnel).
*   **Authentification** :
    *   Symfony Security Bundle.
    *   OAuth 2.0 (Google & Facebook) via `knpuniversity/oauth2-client-bundle`.
*   **Environnement** : Docker (conteneurisation) et scripts PowerShell pour Windows.

## 3. Architecture Logicielle

Le projet suit strictement le modèle **MVC (Modèle-Vue-Contrôleur)** imposé par Symfony :

### Composition des Répertoires
-   **`src/Entity`** : Les classes PHP représentant les tables de la base de données (Le Modèle).
-   **`src/Repository`** : Les classes gérant les requêtes SQL complexes via Doctrine.
-   **`src/Controller`** : Les points d'entrée de l'application, gérant la logique HTTP (La partie Contrôleur).
-   **`src/Service`** : Logique métier déportée (ex: `CartService`) pour alléger les contrôleurs.
-   **`templates/`** : Les fichiers `.html.twig` (La Vue).
-   **`config/`** : Configuration YAML des services, routes, et packages.

## 4. Conception de la Base de Données

Le modèle de données est centré sur l'utilisateur (`User`), qui interagit avec deux modules principaux : le E-commerce (`Order`, `Product`) et les Réservations (`Reservation`).

### Entités Principales
1.  **User** : Utilisateur inscrit (Email, Password, Roles, Social IDs).
2.  **Product** : Article disponible à la vente (Nom, Prix, Stock).
3.  **Order** : Une commande passée par un utilisateur.
4.  **OrderItem** : Ligne de commande liant une `Order` et un `Product` avec une quantité.
5.  **Reservation** : Demande de réservation (indépendante du catalogue produit).

### Diagramme de Classes

Voici le code **LaTeX / TikZ** pour générer le diagramme de classes UML du projet :

```latex
\documentclass{standalone}
\usepackage{tikz}
\usepackage{pgf-umlcd} % Paquet pour les diagrammes de classes UML

\begin{document}
\begin{tikzpicture}
    % Classe User
    \begin{class}[text width=6cm]{User}{0,0}
        \attribute{id : int}
        \attribute{email : string}
        \attribute{password : string}
        \attribute{roles : array}
        \attribute{nom : string}
        \attribute{prenom : string}
        \attribute{googleId : string}
        \attribute{facebookId : string}
        \operation{getReservations() : Collection}
        \operation{getOrders() : Collection}
    \end{class}

    % Classe Reservation
    \begin{class}[text width=5cm]{Reservation}{8,0}
        \attribute{id : int}
        \attribute{dateReservation : DateTime}
        \attribute{nombrePersonnes : int}
        \attribute{statut : string}
        \operation{getUser() : User}
    \end{class}

    % Classe Order
    \begin{class}[text width=5cm]{Order}{0,-7}
        \attribute{id : int}
        \attribute{numeroCommande : string}
        \attribute{montantTotal : float}
        \attribute{statut : string}
        \attribute{createdAt : DateTime}
        \operation{getOrderItems() : Collection}
        \operation{calculateTotal() : void}
    \end{class}

    % Classe OrderItem
    \begin{class}[text width=5cm]{OrderItem}{8,-7}
        \attribute{id : int}
        \attribute{quantite : int}
        \attribute{prixUnitaire : float}
        \operation{getTotal() : float}
    \end{class}

    % Classe Product
    \begin{class}[text width=5cm]{Product}{8,-13}
        \attribute{id : int}
        \attribute{nom : string}
        \attribute{prix : float}
        \attribute{description : text}
        \attribute{stock : int}
    \end{class}

    % Relations
    \association{User}{1}{passe}{*}{Order}
    \association{User}{1}{effectue}{*}{Reservation}
    \composition{Order}{1}{contient}{*}{OrderItem}
    \association{OrderItem}{*}{réfère}{1}{Product}
\end{tikzpicture}
\end{document}
```

## 5. Fonctionnalités Clés

### Authentification et Sécurité
Le système gère l'authentification via **`SecurityController`**.
-   **Login Classique** : Email/Mot de passe haché.
-   **OAuth** : Connexion via Google et Facebook implémentée dans `GoogleOAuthController` et `FacebookOAuthController` utilisant le `knpuniversity/oauth2-client-bundle`.
-   **Contrôle d'accès** : Les annotations `#[IsGranted('ROLE_USER')]` protègent les routes sensibles (panier, profil).

### Gestion du Panier et Commandes
Le panier n'est pas stocké en base de données avant la validation, il est géré en **Session** via le `CartService`.
1.  **Ajout** : L'ID du produit est stocké en session.
2.  **Affichage** : Le service reconstruit la liste des objets `Product` à partir de la BDD.
3.  **Validation (`place-order`)** :
    -   Une entité `Order` est créée.
    -   Pour chaque ligne du panier, une entité `OrderItem` est instanciée et liée.
    -   Le `EntityManager` persiste le tout en une transaction.
    -   Le stock n'est pas décrémenté dans le code visible (point d'amélioration possible).

### Système de Réservation
Un module distinct permet de réserver une table ou un service. Contrairement aux commandes, cela ne passe pas par le panier mais par un formulaire dédié validé par l'entité `Reservation`.

## 6. Diagrammes de Séquence

Processus simplifié de validation d'une commande (« Checkout »).

### Code TikZ (Diagramme de Séquence)

```latex
\documentclass{standalone}
\usepackage{tikz}
\usepackage{pgf-umlsd} % Paquet pour les diagrammes de séquence

\begin{document}
\begin{sequences}
    \newthread{User}{Utilisateur}
    \newinst[1]{View}{Vue (Twig)}
    \newinst[1]{Ctrl}{CartController}
    \newinst[1]{Srv}{CartService}
    \newinst[1]{DB}{Base de Données}

    \begin{call}{User}{Cliquer "Commander"}{View}{Formulaire affiché}
    \end{call}

    \begin{call}{User}{Soumettre Formulaire}{Ctrl}{placeOrder()}
        \begin{call}{Ctrl}{isEmpty()}{Srv}{false}
        \end{call}
        \begin{call}{Ctrl}{getCart()}{Srv}{Items[]}
        \end{call}
        
        \begin{call}{Ctrl}{Persist Order & Items}{DB}{Success}
        \end{call}
        
        \begin{call}{Ctrl}{clear()}{Srv}{Panier vidé}
        \end{call}
    \end{call}
    
    \mess{Ctrl}{Redirection Profil}{User}
\end{sequences}
\end{document}
```

## 7. Déploiement et Environnement

Le projet inclut des outils facilitant le déploiement sur Windows et serveurs de production :
-   **`setup_prod.ps1`** : Script PowerShell automatisant l'installation des dépendances (`composer install`), la configuration des assets et le cache.
-   **`Dockerfile`** : Configuration pour créer une image conteneurisée de l'application.
-   **`compose.yaml`** : Orchestration des services (Web, Database) pour Docker Compose.

## 8. Sécurité

L'audit des fichiers révèle plusieurs couches de protection :
1.  **CSRF (Cross-Site Request Forgery)** : Activé globalement sur tous les formulaires Symfony.
2.  **Validation des Données** : Utilisation du composant Validator sur les entités (ex: `#[Assert\Email]`, `#[Assert\NotBlank]`).
3.  **Échappement Automatique** : Twig échappe automatiquement les variables pour prévenir les failles XSS.
4.  **Pare-feu (Firewall)** : Configuré dans `security.yaml` pour isoler les sections admin et utilisateur.

## 9. Conclusion

Le projet Synf est une application web robuste et structurée, tirant parti de la puissance de Symfony 7. Elle sépare proprement la logique métier (Services) de la logique de présentation (Contrôleurs/Vues). L'intégration de l'authentification sociale et d'une interface réactive (Stimulus/Turbo) en fait une solution moderne adaptée aux besoins actuels du marché e-commerce.
