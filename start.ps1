# Script de démarrage du serveur Symfony
# Exécuter avec: .\start.ps1

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  DÉMARRAGE SERVEUR SYMFONY" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Définir les variables d'environnement
$env:APP_ENV = "dev"
$env:APP_SECRET = "ChangeThisSecretToken123456789abcdefghijklmnop"
$env:DATABASE_URL = "mysql://root:@127.0.0.1:3306/synf_project?serverVersion=8.0.32&charset=utf8mb4"
$env:DEFAULT_URI = "http://localhost"
$env:MESSENGER_TRANSPORT_DSN = "doctrine://default?auto_setup=0"
$env:MAILER_DSN = "null://null"

Write-Host "✅ Variables d'environnement définies" -ForegroundColor Green
Write-Host ""

# Vider le cache
Write-Host "🗑️  Nettoyage du cache..." -ForegroundColor Yellow
Remove-Item -Path var\cache -Recurse -Force -ErrorAction SilentlyContinue
Write-Host "✅ Cache vidé" -ForegroundColor Green
Write-Host ""

# Démarrer le serveur
Write-Host "🚀 Démarrage du serveur sur http://localhost:8000" -ForegroundColor Green
Write-Host "⏹️  Pour arrêter: Ctrl+C" -ForegroundColor Yellow
Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

php -S localhost:8000 -t public
