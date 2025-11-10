<?php

// Script pour ajouter la colonne facebook_id à la table user

$host = '127.0.0.1';
$dbname = 'synf_project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connexion à la base de données réussie.\n";
    
    // Vérifier si la colonne existe déjà
    $stmt = $pdo->query("SHOW COLUMNS FROM user LIKE 'facebook_id'");
    if ($stmt->rowCount() > 0) {
        echo "La colonne facebook_id existe déjà.\n";
    } else {
        // Ajouter la colonne facebook_id
        $sql = "ALTER TABLE user ADD COLUMN facebook_id VARCHAR(255) NULL UNIQUE AFTER google_id";
        $pdo->exec($sql);
        echo "✅ Colonne facebook_id ajoutée avec succès!\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    exit(1);
}
