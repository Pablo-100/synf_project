# Script de préparation pour le mode Production
# FreshMarket - Symfony 7

Write-Host "--- Préparation de FreshMarket pour la PRODUCTION ---" -ForegroundColor Cyan

# 1. Vérification de l'environnement
$envFile = ".env"
if (Test-Path $envFile) {
    Write-Host "[1/4] Optimisation de l'autoloader Composer..."
    composer install --no-dev --optimize-autoloader
}

# 2. Nettoyage et préchauffage du cache
Write-Host "[2/4] Nettoyage du cache pour l'environnement PROD..."
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod

# 3. Compilation des assets (AssetMapper)
Write-Host "[3/4] Compilation et déploiement des assets..."
php bin/console asset-map:compile

# 4. Vérification de la sécurité
Write-Host "[4/4] Audit de sécurité du projet..."
composer audit

Write-Host "`n--- TERMINÉ ---" -ForegroundColor Green
Write-Host "N'oubliez pas de configurer APP_ENV/APP_SECRET dans votre serveur d'hébergement."
