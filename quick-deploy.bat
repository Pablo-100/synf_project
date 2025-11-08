@echo off
REM Quick Deploy Script for FreshMarket Symfony Project (Windows)
REM This script helps you deploy to various free hosting platforms

echo ╔════════════════════════════════════════════════════╗
echo ║   🚀 FreshMarket Quick Deploy Script              ║
echo ║   Déploiement rapide sur plateformes gratuites    ║
echo ╚════════════════════════════════════════════════════╝
echo.
echo Choisissez votre plateforme de déploiement :
echo.
echo 1) Railway.app (Recommandé - Le plus simple)
echo 2) Render.com
echo 3) Afficher le guide complet
echo 4) Quitter
echo.
set /p choice="Votre choix (1-4) : "

if "%choice%"=="1" goto railway
if "%choice%"=="2" goto render
if "%choice%"=="3" goto guide
if "%choice%"=="4" goto quit
goto invalid

:railway
echo.
echo ═══════════════════════════════════════════
echo 📦 Déploiement sur Railway.app
echo ═══════════════════════════════════════════
echo.

REM Check if Railway CLI is installed
where railway >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo ⚠️  Railway CLI n'est pas installé.
    echo 📥 Installation en cours...
    echo.
    npm install -g @railway/cli
) else (
    echo ✅ Railway CLI est déjà installé
)

echo.
echo 🔐 Connexion à Railway...
call railway login

echo.
echo 🎯 Initialisation du projet...
call railway init

echo.
set /p add_db="Voulez-vous ajouter une base de données MySQL gratuite ? (O/n) : "
if /i not "%add_db%"=="n" (
    echo 📊 Ajout de MySQL...
    call railway add --database mysql
)

echo.
echo 🚀 Déploiement de l'application...
call railway up

echo.
echo ✅ Déploiement terminé !
echo 🌐 Pour ouvrir votre application dans le navigateur :
echo    railway open
echo.
echo 📊 Pour voir les logs :
echo    railway logs
echo.
echo ⚙️  Pour gérer les variables d'environnement :
echo    railway variables
echo.
goto end

:render
echo.
echo ═══════════════════════════════════════════
echo 📦 Déploiement sur Render.com
echo ═══════════════════════════════════════════
echo.
echo Pour déployer sur Render.com :
echo.
echo 1. Allez sur https://render.com
echo 2. Connectez-vous avec GitHub
echo 3. Cliquez sur 'New +' -^> 'Web Service'
echo 4. Sélectionnez ce repository
echo 5. Configuration :
echo    - Name: freshmarket
echo    - Environment: Docker
echo    - Dockerfile Path: ./Dockerfile
echo.
echo 📖 Guide détaillé : RENDER_DEPLOY.md
echo.
pause
start https://render.com
goto end

:guide
echo.
echo ═══════════════════════════════════════════
echo 📖 Guide Complet de Déploiement
echo ═══════════════════════════════════════════
echo.
echo Consultez ces guides pour plus d'informations :
echo.
echo 🌟 FREE_DOMAIN_GUIDE.md - Domaines et hébergement gratuits
echo 📖 DEPLOYMENT.md - Guide complet de déploiement
echo 🎯 RENDER_DEPLOY.md - Déploiement spécifique Render
echo.
echo Ces fichiers contiennent des instructions détaillées pour :
echo - Obtenir un domaine gratuit (.tk, .ml, .ga, etc.)
echo - Déployer sur différentes plateformes
echo - Configurer SSL/HTTPS gratuit
echo - Gérer la base de données
echo.
set /p show_guide="Voulez-vous ouvrir FREE_DOMAIN_GUIDE.md maintenant ? (O/n) : "
if /i not "%show_guide%"=="n" (
    start notepad FREE_DOMAIN_GUIDE.md
)
goto end

:quit
echo.
echo 👋 Au revoir !
echo.
exit /b 0

:invalid
echo.
echo ❌ Choix invalide. Veuillez choisir entre 1 et 4.
echo.
exit /b 1

:end
echo.
echo ╔════════════════════════════════════════════════════╗
echo ║   ✅ Script terminé !                              ║
echo ║   📧 Support : mustaphaamintbini@gmail.com         ║
echo ╚════════════════════════════════════════════════════╝
echo.
pause
