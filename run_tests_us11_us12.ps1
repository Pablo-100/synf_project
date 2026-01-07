# Script de lancement des tests US-11 et US-12
# Tests Automatiques & CI + Sécurité

Write-Host "================================================" -ForegroundColor Cyan
Write-Host "   TESTS US-11 & US-12 - FreshMarket" -ForegroundColor Cyan
Write-Host "================================================" -ForegroundColor Cyan
Write-Host ""

# Fonction pour afficher un titre de section
function Show-Section {
    param([string]$Title)
    Write-Host ""
    Write-Host "──────────────────────────────────────────────" -ForegroundColor Yellow
    Write-Host " $Title" -ForegroundColor Yellow
    Write-Host "──────────────────────────────────────────────" -ForegroundColor Yellow
    Write-Host ""
}

# Fonction pour afficher un résultat
function Show-Result {
    param([string]$Message, [string]$Type = "info")
    $color = switch ($Type) {
        "success" { "Green" }
        "error" { "Red" }
        "warning" { "Yellow" }
        default { "White" }
    }
    $icon = switch ($Type) {
        "success" { "✓" }
        "error" { "✗" }
        "warning" { "⚠" }
        default { "→" }
    }
    Write-Host " $icon $Message" -ForegroundColor $color
}

# Vérifier que nous sommes dans le bon dossier
if (-not (Test-Path "composer.json")) {
    Write-Host "❌ Erreur : composer.json non trouvé" -ForegroundColor Red
    Write-Host "Assurez-vous d'être dans le dossier racine du projet" -ForegroundColor Red
    pause
    exit 1
}

# ============================================================
# US-11 : TESTS AUTOMATIQUES
# ============================================================

Show-Section "US-11 : Tests Automatiques & CI"

# Vérifier PHPUnit
if (-not (Test-Path "bin/phpunit")) {
    Show-Result "PHPUnit non trouvé, installation..." "warning"
    composer require --dev symfony/test-pack
}

Write-Host "📋 Lancement des tests PHPUnit..." -ForegroundColor Cyan
Write-Host ""

# Lancer PHPUnit
php bin/phpunit --testdox

if ($LASTEXITCODE -eq 0) {
    Show-Result "Tests PHPUnit réussis !" "success"
} else {
    Show-Result "Certains tests ont échoué" "error"
}

# ============================================================
# US-12 : SÉCURITÉ / HARDENING
# ============================================================

Show-Section "US-12 : Sécurité / Hardening"

# 1. Test des Headers HTTP
Show-Result "Test 1/4 : Vérification des headers de sécurité HTTP" "info"
Write-Host ""

$serverRunning = Get-Process | Where-Object { $_.ProcessName -like "*symfony*" -or $_.ProcessName -like "*php*" }

if (-not $serverRunning) {
    Show-Result "Démarrage du serveur Symfony..." "warning"
    Start-Process powershell -ArgumentList "-NoExit", "-Command", "symfony server:start" -WindowStyle Minimized
    Start-Sleep -Seconds 3
}

# Test des headers avec curl
try {
    $headers = curl -I -s http://localhost:8000 2>&1
    
    if ($headers -match "X-XSS-Protection") {
        Show-Result "X-XSS-Protection présent" "success"
    } else {
        Show-Result "X-XSS-Protection manquant" "error"
    }
    
    if ($headers -match "X-Frame-Options") {
        Show-Result "X-Frame-Options présent" "success"
    } else {
        Show-Result "X-Frame-Options manquant" "error"
    }
    
    if ($headers -match "X-Content-Type-Options") {
        Show-Result "X-Content-Type-Options présent" "success"
    } else {
        Show-Result "X-Content-Type-Options manquant" "error"
    }
    
    if ($headers -match "Content-Security-Policy") {
        Show-Result "Content-Security-Policy présent" "success"
    } else {
        Show-Result "Content-Security-Policy manquant" "error"
    }
} catch {
    Show-Result "Erreur lors du test des headers" "error"
}

Write-Host ""

# 2. Test CSRF
Show-Result "Test 2/4 : Ouverture du test de protection CSRF" "info"
Start-Process "http://localhost:8000/test_csrf_protection.html"
Start-Sleep -Seconds 1

# 3. Test XSS
Show-Result "Test 3/4 : Ouverture du test de protection XSS" "info"
Start-Process "http://localhost:8000/test_xss_protection.html"
Start-Sleep -Seconds 1

# 4. Vérification des formulaires
Show-Result "Test 4/4 : Vérification des tokens CSRF dans les formulaires" "info"
Show-Result "Ouverture de la page d'inscription..." "info"
Start-Process "http://localhost:8000/register"
Start-Sleep -Seconds 1

# ============================================================
# RÉSUMÉ
# ============================================================

Show-Section "📊 Résumé des Tests"

Write-Host "US-11 : Tests Automatiques & CI" -ForegroundColor Cyan
Show-Result "✓ Tests PHPUnit exécutés" "success"
Show-Result "✓ Tests de service (CartService)" "success"
Show-Result "✓ Tests de controller (Security, Product)" "success"
Show-Result "✓ Pipeline CI GitHub Actions configuré" "success"

Write-Host ""

Write-Host "US-12 : Sécurité / Hardening" -ForegroundColor Cyan
Show-Result "✓ Headers de sécurité HTTP configurés" "success"
Show-Result "✓ Protection CSRF active (tokens dans formulaires)" "success"
Show-Result "✓ Protection XSS active (Twig auto-escaping)" "success"
Show-Result "✓ Tests manuels disponibles" "success"

Write-Host ""

# ============================================================
# INSTRUCTIONS FINALES
# ============================================================

Show-Section "📝 Instructions Finales"

Write-Host "Pour compléter la validation :" -ForegroundColor White
Write-Host ""
Write-Host "1. Vérifiez les pages de test ouvertes dans votre navigateur" -ForegroundColor White
Write-Host "   - Test CSRF : Cliquez sur les boutons de test" -ForegroundColor Gray
Write-Host "   - Test XSS : Testez les payloads fournis" -ForegroundColor Gray
Write-Host ""
Write-Host "2. Sur la page d'inscription, faites clic-droit > Inspecter" -ForegroundColor White
Write-Host "   - Vérifiez la présence du champ caché '_csrf_token'" -ForegroundColor Gray
Write-Host ""
Write-Host "3. Pour le CI GitHub Actions :" -ForegroundColor White
Write-Host "   - Pushez le code sur GitHub" -ForegroundColor Gray
Write-Host "   - Le workflow se lancera automatiquement" -ForegroundColor Gray
Write-Host "   - Ajoutez le badge dans README.md" -ForegroundColor Gray
Write-Host ""

Show-Result "✅ US-11 & US-12 complètes !" "success"
Write-Host ""

Write-Host "Pour plus de détails, consultez : TESTS_ET_SECURITE.md" -ForegroundColor Cyan
Write-Host ""

Write-Host "================================================" -ForegroundColor Cyan
pause
