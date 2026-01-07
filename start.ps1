# Script de démarrage du serveur Symfony
# Exécuter avec: .\start.ps1

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  DÉMARRAGE SERVEUR SYMFONY" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Définir les variables d'environnement
$env:APP_ENV = "prod"
$env:APP_SECRET = "ChangeThisSecretToken123456789abcdefghijklmnop"
$env:DATABASE_URL = "mysql://root:@127.0.0.1:3306/synf_project?serverVersion=8.0.32&charset=utf8mb4"
$env:DEFAULT_URI = "http://localhost"
$env:MESSENGER_TRANSPORT_DSN = "doctrine://default?auto_setup=0"
$env:MAILER_DSN = "null://null"

# Variables OAuth - À configurer dans .env.local (ne pas commiter les secrets !)
# $env:GOOGLE_CLIENT_ID = "VOTRE_GOOGLE_CLIENT_ID"
# $env:GOOGLE_CLIENT_SECRET = "VOTRE_GOOGLE_CLIENT_SECRET"
# $env:FACEBOOK_CLIENT_ID = "VOTRE_FACEBOOK_CLIENT_ID"
# $env:FACEBOOK_CLIENT_SECRET = "VOTRE_FACEBOOK_CLIENT_SECRET"

# Charger les variables depuis .env.local si le fichier existe
if (Test-Path ".env.local") {
    Get-Content .env.local | ForEach-Object {
        if ($_ -match '^([^#].+?)=(.+)$') {
            $name = $matches[1].Trim()
            $value = $matches[2].Trim().Trim('"').Trim("'")
            Set-Item -Path "env:$name" -Value $value
        }
    }
    Write-Host "✅ Variables chargées depuis .env.local" -ForegroundColor Green
}

Write-Host "✅ Variables d'environnement définies" -ForegroundColor Green
Write-Host ""

# Vider le cache
Write-Host "🗑️  Nettoyage du cache..." -ForegroundColor Yellow
Remove-Item -Path var\cache -Recurse -Force -ErrorAction SilentlyContinue | Out-Null
Write-Host "✅ Cache vidé" -ForegroundColor Green
Write-Host ""

# Démarrer le serveur
Write-Host "🚀 Démarrage du serveur sur http://localhost:8000" -ForegroundColor Green
Write-Host "⏹️  Pour arrêter: Ctrl+C" -ForegroundColor Yellow
Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Use PHP 8.2+ from specified installation
$phpPath = "C:\xampp-old\php\windowsXamppPhp\php.exe"

# Check PHP version
$phpVersion = & $phpPath -v 2>&1 | Select-Object -First 1
Write-Host "Using: $phpVersion" -ForegroundColor Magenta

& $phpPath -S localhost:8000 -t public
