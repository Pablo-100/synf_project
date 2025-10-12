# Script d'installation automatique du projet Symfony
# À exécuter dans PowerShell en tant qu'administrateur

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Installation Projet Symfony" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Vérifier si XAMPP est installé
$xamppPath = "C:\xampp"
if (!(Test-Path $xamppPath)) {
    Write-Host "ERREUR: XAMPP n'est pas installé dans $xamppPath" -ForegroundColor Red
    Write-Host "Veuillez installer XAMPP d'abord." -ForegroundColor Yellow
    exit 1
}

# Vérifier si PHP est accessible
try {
    $phpVersion = php -v
    Write-Host "PHP détecté:" -ForegroundColor Green
    Write-Host $phpVersion[0]
} catch {
    Write-Host "ERREUR: PHP n'est pas accessible" -ForegroundColor Red
    Write-Host "Ajoutez C:\xampp\php à votre PATH" -ForegroundColor Yellow
    exit 1
}

# Vérifier si Composer est installé
try {
    composer --version | Out-Null
    Write-Host "Composer détecté" -ForegroundColor Green
} catch {
    Write-Host "ERREUR: Composer n'est pas installé" -ForegroundColor Red
    Write-Host "Téléchargez Composer sur https://getcomposer.org/" -ForegroundColor Yellow
    exit 1
}

Write-Host ""
Write-Host "Vérifications terminées avec succès!" -ForegroundColor Green
Write-Host ""

# Se déplacer dans le dossier du projet
$projectPath = "C:\xampp\htdocs\synf_project"
Set-Location $projectPath

Write-Host "Installation des dépendances..." -ForegroundColor Cyan
composer install --no-interaction

Write-Host ""
Write-Host "Création du dossier uploads..." -ForegroundColor Cyan
New-Item -ItemType Directory -Path "public\uploads" -Force | Out-Null

Write-Host ""
Write-Host "Configuration des variables d'environnement..." -ForegroundColor Cyan
$env:DATABASE_URL = "mysql://root:@127.0.0.1:3306/synf_project?serverVersion=8.0.32&charset=utf8mb4"
$env:DEFAULT_URI = "http://localhost"

Write-Host ""
Write-Host "========================================" -ForegroundColor Yellow
Write-Host "  IMPORTANT: Étapes Manuelles" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Yellow
Write-Host ""
Write-Host "1. Démarrez XAMPP Control Panel" -ForegroundColor Yellow
Write-Host "2. Démarrez Apache et MySQL" -ForegroundColor Yellow
Write-Host "3. Ouvrez phpMyAdmin: http://localhost/phpmyadmin" -ForegroundColor Yellow
Write-Host "4. Créez la base 'synf_project'" -ForegroundColor Yellow
Write-Host "5. Importez le fichier 'database.sql'" -ForegroundColor Yellow
Write-Host ""
Write-Host "Ou exécutez ces commandes:" -ForegroundColor Cyan
Write-Host ""
Write-Host '$env:DATABASE_URL="mysql://root:@127.0.0.1:3306/synf_project?serverVersion=8.0.32&charset=utf8mb4"' -ForegroundColor White
Write-Host '$env:DEFAULT_URI="http://localhost"' -ForegroundColor White
Write-Host 'php bin/console doctrine:database:create --if-not-exists' -ForegroundColor White
Write-Host ""
Write-Host "Puis importez database.sql via phpMyAdmin" -ForegroundColor Cyan
Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "  Pour lancer le serveur:" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "php -S localhost:8000 -t public" -ForegroundColor White
Write-Host ""
Write-Host "Puis ouvrez: http://localhost:8000" -ForegroundColor Cyan
Write-Host ""
Write-Host "Installation terminée!" -ForegroundColor Green
