#!/bin/bash
# Quick Deploy Script for FreshMarket Symfony Project
# This script helps you deploy to various free hosting platforms

echo "╔════════════════════════════════════════════════════╗"
echo "║   🚀 FreshMarket Quick Deploy Script              ║"
echo "║   Déploiement rapide sur plateformes gratuites    ║"
echo "╚════════════════════════════════════════════════════╝"
echo ""
echo "Choisissez votre plateforme de déploiement :"
echo ""
echo "1) Railway.app (Recommandé - Le plus simple)"
echo "2) Render.com"
echo "3) Afficher le guide complet"
echo "4) Quitter"
echo ""
read -p "Votre choix (1-4) : " choice

case $choice in
    1)
        echo ""
        echo "═══════════════════════════════════════════"
        echo "📦 Déploiement sur Railway.app"
        echo "═══════════════════════════════════════════"
        echo ""
        
        # Check if Railway CLI is installed
        if ! command -v railway &> /dev/null; then
            echo "⚠️  Railway CLI n'est pas installé."
            echo "📥 Installation en cours..."
            echo ""
            npm install -g @railway/cli
        else
            echo "✅ Railway CLI est déjà installé"
        fi
        
        echo ""
        echo "🔐 Connexion à Railway..."
        railway login
        
        echo ""
        echo "🎯 Initialisation du projet..."
        railway init
        
        echo ""
        read -p "Voulez-vous ajouter une base de données MySQL gratuite ? (O/n) : " add_db
        if [[ $add_db != "n" && $add_db != "N" ]]; then
            echo "📊 Ajout de MySQL..."
            railway add --database mysql
        fi
        
        echo ""
        echo "🚀 Déploiement de l'application..."
        railway up
        
        echo ""
        echo "✅ Déploiement terminé !"
        echo "🌐 Pour ouvrir votre application dans le navigateur :"
        echo "   railway open"
        echo ""
        echo "📊 Pour voir les logs :"
        echo "   railway logs"
        echo ""
        echo "⚙️  Pour gérer les variables d'environnement :"
        echo "   railway variables"
        echo ""
        ;;
        
    2)
        echo ""
        echo "═══════════════════════════════════════════"
        echo "📦 Déploiement sur Render.com"
        echo "═══════════════════════════════════════════"
        echo ""
        echo "Pour déployer sur Render.com :"
        echo ""
        echo "1. Allez sur https://render.com"
        echo "2. Connectez-vous avec GitHub"
        echo "3. Cliquez sur 'New +' → 'Web Service'"
        echo "4. Sélectionnez ce repository"
        echo "5. Configuration :"
        echo "   - Name: freshmarket"
        echo "   - Environment: Docker"
        echo "   - Dockerfile Path: ./Dockerfile"
        echo ""
        echo "📖 Guide détaillé : RENDER_DEPLOY.md"
        echo ""
        read -p "Appuyez sur Entrée pour ouvrir Render.com dans le navigateur..."
        
        if command -v xdg-open &> /dev/null; then
            xdg-open "https://render.com"
        elif command -v open &> /dev/null; then
            open "https://render.com"
        elif command -v start &> /dev/null; then
            start "https://render.com"
        else
            echo "🌐 Ouvrez manuellement : https://render.com"
        fi
        ;;
        
    3)
        echo ""
        echo "═══════════════════════════════════════════"
        echo "📖 Guide Complet de Déploiement"
        echo "═══════════════════════════════════════════"
        echo ""
        echo "Consultez ces guides pour plus d'informations :"
        echo ""
        echo "🌟 FREE_DOMAIN_GUIDE.md - Domaines et hébergement gratuits"
        echo "📖 DEPLOYMENT.md - Guide complet de déploiement"
        echo "🎯 RENDER_DEPLOY.md - Déploiement spécifique Render"
        echo ""
        echo "Ces fichiers contiennent des instructions détaillées pour :"
        echo "- Obtenir un domaine gratuit (.tk, .ml, .ga, etc.)"
        echo "- Déployer sur différentes plateformes"
        echo "- Configurer SSL/HTTPS gratuit"
        echo "- Gérer la base de données"
        echo ""
        
        if command -v cat &> /dev/null; then
            read -p "Voulez-vous afficher FREE_DOMAIN_GUIDE.md maintenant ? (O/n) : " show_guide
            if [[ $show_guide != "n" && $show_guide != "N" ]]; then
                if command -v less &> /dev/null; then
                    less FREE_DOMAIN_GUIDE.md
                else
                    cat FREE_DOMAIN_GUIDE.md
                fi
            fi
        fi
        ;;
        
    4)
        echo ""
        echo "👋 Au revoir !"
        echo ""
        exit 0
        ;;
        
    *)
        echo ""
        echo "❌ Choix invalide. Veuillez choisir entre 1 et 4."
        echo ""
        exit 1
        ;;
esac

echo ""
echo "╔════════════════════════════════════════════════════╗"
echo "║   ✅ Script terminé !                              ║"
echo "║   📧 Support : mustaphaamintbini@gmail.com         ║"
echo "╚════════════════════════════════════════════════════╝"
echo ""
