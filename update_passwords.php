<?php
// Script pour mettre à jour les mots de passe dans la base de données

$host = 'localhost';
$dbname = 'synf_project';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Hash bcrypt généré pour "admin123"
    $hashedPassword = '$2y$13$4v.VUUsBCQPx0Db1HreATOx0SfOptPW6Vth6Y1N6HpBQcbXGOlrgS';
    
    // Mise à jour du mot de passe pour admin
    $stmt = $pdo->prepare("UPDATE user SET password = :password WHERE email = :email");
    $stmt->execute([
        'password' => $hashedPassword,
        'email' => 'admin@example.com'
    ]);
    echo "✅ Mot de passe mis à jour pour admin@example.com\n";
    
    // Mise à jour du mot de passe pour user
    $stmt->execute([
        'password' => $hashedPassword,
        'email' => 'user@example.com'
    ]);
    echo "✅ Mot de passe mis à jour pour user@example.com\n";
    
    // Vérification
    echo "\n📋 Utilisateurs dans la base :\n";
    $result = $pdo->query("SELECT id, email, nom, prenom, roles FROM user");
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo sprintf("  - ID: %d | Email: %s | Nom: %s %s | Roles: %s\n", 
            $row['id'], 
            $row['email'], 
            $row['prenom'], 
            $row['nom'],
            $row['roles']
        );
    }
    
    echo "\n🎉 Mots de passe mis à jour avec succès !\n";
    echo "   Vous pouvez maintenant vous connecter avec :\n";
    echo "   - Email : admin@example.com ou user@example.com\n";
    echo "   - Mot de passe : admin123\n";
    
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    exit(1);
}
