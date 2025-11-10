<?php
// GOOGLE OAUTH FEATURE - Script pour ajouter la colonne google_id

declare(strict_types=1);

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=synf_project', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "🔄 Vérification de la colonne google_id...\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM user LIKE 'google_id'");

    if ($stmt->rowCount() === 0) {
        $pdo->exec("ALTER TABLE user ADD COLUMN google_id VARCHAR(255) NULL UNIQUE AFTER created_at");
        echo "✅ Colonne 'google_id' ajoutée avec succès.\n";
    } else {
        echo "ℹ️  La colonne 'google_id' existe déjà, aucune action nécessaire.\n";
    }

    echo "\n🎉 Migration terminée avec succès !\n";
} catch (PDOException $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
