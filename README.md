
# synf_project — Application Symfony de Réservation & Commande

## 🧩 Description

**synf_project** est une application web développée avec **Symfony 7 (PHP)**.  
Elle permet aux utilisateurs d’effectuer des **réservations** et de **passer des commandes** en ligne, tout en offrant une **interface d’administration complète** pour gérer les utilisateurs, les produits, les réservations et les commandes.

### 🎯 Objectifs du projet
- Offrir une plateforme simple et moderne pour la gestion de réservations et commandes.
- Implémenter les bonnes pratiques de développement web avec Symfony.
- Servir de base académique pour un projet d’ingénierie en développement web.

---

## ⚙️ Installation

### Prérequis
- **PHP ≥ 8.1**
- **MySQL ≥ 8.0**
- **Composer**
- Extensions PHP requises : `pdo_mysql`, `gd`, `intl`

### Étapes d’installation

1. **Cloner le dépôt**
   ```bash
   git clone https://github.com/Pablo-100/synf_project.git
   cd synf_project


2. **Installer les dépendances**

   ```bash
   composer install
   ```

3. **Configurer la base de données**
   Modifier la variable `DATABASE_URL` dans le fichier `.env` :

   ```
   DATABASE_URL="mysql://root:@127.0.0.1:3306/synf_project?serverVersion=8.0&charset=utf8mb4"
   ```

4. **Créer la base de données et exécuter les migrations**

   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   

5. **(Optionnel)** Charger des données de test (fixtures)

   ```bash
   php bin/console doctrine:fixtures:load
   ```

6. **Créer un utilisateur administrateur**

   ```bash
   php bin/console make:user
   ```

7. **Démarrer le serveur local**

   ```bash
   symfony server:start
   # ou
   php -S localhost:8000 -t public
   ```

➡️ L’application sera accessible sur : [http://localhost:8000](http://localhost:8000)

---

## 🧱 Structure du projet

```
synf_project/
├── config/              # Configuration de Symfony
├── public/              # Point d’entrée web (index.php)
├── src/
│   ├── Controller/      # Contrôleurs
│   ├── Entity/          # Entités Doctrine
│   ├── Form/            # Formulaires Symfony
│   ├── Repository/      # Classes de gestion des entités
│   └── Kernel.php
├── templates/           # Vues Twig
├── migrations/          # Fichiers de migration de la base
├── .env                 # Configuration d’environnement
└── composer.json        # Dépendances PHP
```

---

## 🔐 Sécurité

* Mots de passe hashés avec **bcrypt / argon2**
* Protection **CSRF** sur les formulaires
* Gestion des rôles et permissions (**ROLE_USER**, **ROLE_ADMIN**)
* Validation des données côté serveur

---

## 🧑‍💻 Développement

### Générer un contrôleur

```bash
php bin/console make:controller NomController
```

### Générer une entité

```bash
php bin/console make:entity NomEntity
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

### Lancer les tests

```bash
php bin/phpunit
```


## 📊 Fonctionnalités principales

### Utilisateurs

* Inscription / connexion
* Profil personnel
* Historique des réservations et commandes

### Réservations

* Création, modification, annulation
* Statuts : en attente, confirmée, annulée, terminée

### Commandes

* Ajout d’articles au panier
* Validation de commande
* Suivi du statut

### Administration

* CRUD complet : produits, utilisateurs, réservations, commandes
* Tableau de bord avec statistiques

---

## 🤝 Contribution

Les contributions sont les bienvenues !
Pour contribuer :

1. Forker le dépôt
2. Créer une nouvelle branche :

   ```bash
   git checkout -b feature/ma-fonctionnalite
   ```
3. Effectuer vos modifications
4. Soumettre une Pull Request pour révision

---

## 🪪 Licence

Ce projet est distribué sous la **Licence MIT** :

```
MIT License

Copyright (c) 2025 

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the “Software”), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
```

---

## 👤 Auteur

**Mustapha Amine TBINI**
📍 Tunis, Tunisie
📧 [mustaphaamintbini@gmail.com](mailto:mustaphaamintbini@gmail.com)
🔗 [LinkedIn](https://www.linkedin.com/in/mustapha-amin-tbini)


