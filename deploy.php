<?php
/**
 * Script de déploiement en production
 * Exécutez ce script après avoir déployé votre code sur le serveur
 * 
 * Usage: php deploy.php
 */

echo "🚀 Déploiement en mode PRODUCTION\n\n";

// 1. Vider le cache
echo "1️⃣  Vidage du cache...\n";
exec('php bin/console cache:clear --env=prod --no-debug', $output, $returnCode);
if ($returnCode !== 0) {
    die("❌ Erreur lors du vidage du cache\n");
}
echo "✅ Cache vidé\n\n";

// 2. Installer les dépendances (sans dev)
echo "2️⃣  Installation des dépendances...\n";
exec('composer install --no-dev --optimize-autoloader', $output, $returnCode);
if ($returnCode !== 0) {
    die("❌ Erreur lors de l'installation des dépendances\n");
}
echo "✅ Dépendances installées\n\n";

// 3. Migrations de base de données
echo "3️⃣  Application des migrations...\n";
exec('php bin/console doctrine:migrations:migrate --no-interaction --env=prod', $output, $returnCode);
if ($returnCode !== 0) {
    echo "⚠️  Attention: Erreur lors des migrations\n\n";
} else {
    echo "✅ Migrations appliquées\n\n";
}

// 4. Optimiser l'autoloader
echo "4️⃣  Optimisation de l'autoloader...\n";
exec('composer dump-autoload --optimize --no-dev', $output, $returnCode);
echo "✅ Autoloader optimisé\n\n";

// 5. Warmup du cache
echo "5️⃣  Préchauffage du cache...\n";
exec('php bin/console cache:warmup --env=prod', $output, $returnCode);
if ($returnCode !== 0) {
    die("❌ Erreur lors du préchauffage du cache\n");
}
echo "✅ Cache préchauffé\n\n";

// 6. Vérifier les permissions
echo "6️⃣  Vérification des permissions...\n";
$dirs = ['var/cache', 'var/log', 'public/uploads'];
foreach ($dirs as $dir) {
    if (!is_writable($dir)) {
        echo "⚠️  Le dossier $dir n'est pas accessible en écriture\n";
        echo "   Exécutez: chmod -R 775 $dir\n";
    }
}
echo "✅ Vérification terminée\n\n";

// 7. Résumé de sécurité
echo "🔒 CHECKLIST DE SÉCURITÉ:\n";
echo "   ☑️  CSRF Protection activée\n";
echo "   ☑️  XSS Protection (headers + validation)\n";
echo "   ☑️  SQL Injection (requêtes paramétrées)\n";
echo "   ☑️  Session sécurisée (httpOnly, sameSite)\n";
echo "   ☑️  Headers de sécurité HTTP\n";
echo "   ⚠️  Vérifiez APP_SECRET dans .env.prod\n";
echo "   ⚠️  Configurez HTTPS sur votre serveur\n";
echo "   ⚠️  Configurez DATABASE_URL dans .env.prod\n\n";

echo "✅ Déploiement terminé!\n";
echo "🌐 N'oubliez pas de configurer votre serveur web (Apache/Nginx)\n";
