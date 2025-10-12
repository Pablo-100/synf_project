# Instructions d'installation - Upload d'images

## ✅ Fonctionnalités ajoutées

### 1. Upload d'images pour les produits
- Lors de la création d'un produit (Admin → Produits → Ajouter)
- Lors de la modification d'un produit (Admin → Produits → Éditer)
- Formats acceptés : JPEG, PNG, GIF, WebP
- Taille maximum : 2 Mo
- Images stockées dans `public/uploads/`

### 2. Upload de photo de profil pour les utilisateurs
- Dans Profil → Modifier mon profil
- Photo affichée en rond (avatar)
- Formats acceptés : JPEG, PNG, GIF, WebP
- Taille maximum : 2 Mo

## 📋 Étape obligatoire : Ajouter la colonne avatar

**IMPORTANT** : Vous devez exécuter cette requête SQL dans phpMyAdmin pour ajouter la colonne avatar à la table user.

### Via phpMyAdmin :

1. Ouvrez **http://localhost/phpmyadmin**
2. Sélectionnez la base de données **synf_project**
3. Cliquez sur l'onglet **SQL**
4. Copiez et collez cette requête :

```sql
ALTER TABLE `user` ADD COLUMN `avatar` VARCHAR(255) NULL AFTER `adresse`;
```

5. Cliquez sur **Exécuter**
6. Vérifiez que la colonne a été ajoutée (onglet Structure de la table user)

## 🧪 Test des uploads

### Test upload image produit :
1. Connectez-vous en tant qu'admin (admin@example.com / admin123)
2. Allez sur Admin → Produits
3. Cliquez "Ajouter un produit" ou "Éditer" un produit existant
4. Remplissez le formulaire
5. Cliquez sur "Choisir un fichier" sous "Image du produit"
6. Sélectionnez une image (JPEG, PNG, GIF ou WebP, max 2Mo)
7. Enregistrez
8. ✅ L'image apparaît dans la liste des produits et sur les pages produit

### Test upload avatar utilisateur :
1. Connectez-vous (user@example.com / admin123 ou créez un compte)
2. Allez sur Dashboard → Modifier mon profil
3. Sous "Photo de profil", cliquez "Choisir un fichier"
4. Sélectionnez une photo (JPEG, PNG, GIF ou WebP, max 2Mo)
5. Enregistrez
6. ✅ Votre photo de profil apparaît en rond dans la page d'édition
7. ✅ Elle apparaîtra aussi dans les menus (à implémenter dans base.html.twig si souhaité)

## 📂 Fichiers modifiés/créés

### Nouveaux fichiers :
- `src/Service/FileUploader.php` - Service d'upload sécurisé
- `add_avatar_column.sql` - Script SQL pour ajouter colonne avatar

### Fichiers modifiés :
- `config/services.yaml` - Configuration FileUploader
- `src/Entity/User.php` - Ajout propriété avatar + getters/setters
- `src/Form/ProductType.php` - Ajout champ imageFile
- `src/Form/ProfileFormType.php` - Ajout champ avatarFile
- `src/Controller/Admin/AdminController.php` - Gestion upload produits
- `src/Controller/ProfileController.php` - Gestion upload avatar
- `templates/admin/product_edit.html.twig` - Prévisualisation image
- `templates/profile/edit.html.twig` - Prévisualisation avatar

## 🔒 Sécurité

- Validation des types MIME (seulement images)
- Validation de la taille (max 2Mo)
- Noms de fichiers sécurisés (sluggés + uniqid)
- Suppression automatique de l'ancienne image lors du remplacement

## 💡 Améliorations possibles

- Redimensionner automatiquement les images (avec Imagine bundle)
- Ajouter un crop/recadrage d'image
- Afficher l'avatar dans la navigation (dropdown menu)
- Ajouter une galerie d'images pour les produits (multiple images)
