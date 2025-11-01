#!/bin/bash
set -e

echo "🚀 Starting FreshMarket Application..."

# Attendre que la base de données soit prête (si DATABASE_URL est défini)
if [ ! -z "$DATABASE_URL" ]; then
    echo "⏳ Waiting for database..."
    timeout 60 bash -c 'until php bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do sleep 2; done' || echo "⚠️ Database not ready, continuing anyway..."
fi

# Créer les dossiers nécessaires
echo "📁 Creating directories..."
mkdir -p var/cache var/log public/uploads
chmod -R 777 var/cache var/log public/uploads

# Vider et réchauffer le cache
echo "🔥 Warming up cache..."
php bin/console cache:clear --env=prod --no-debug || true
php bin/console cache:warmup --env=prod --no-debug || true

# Exécuter les migrations (optionnel, décommentez si nécessaire)
# echo "🗄️ Running migrations..."
# php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration || true

# Fixer les permissions finales
echo "🔒 Setting permissions..."
chown -R www-data:www-data /var/www/html
chmod -R 777 var/cache var/log

echo "✅ Application ready!"

# Démarrer Apache
exec apache2-foreground
