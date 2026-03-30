#!/usr/bin/env bash
# Script de démarrage du projet Iran News avec Docker

echo "🚀 Démarrage du projet Iran News..."

# Vérifier si Docker est installé
if ! command -v docker &> /dev/null; then
    echo "❌ Docker n'est pas installé. Veuillez installer Docker."
    exit 1
fi

# Vérifier si Docker Compose est installé
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose n'est pas installé. Veuillez installer Docker Compose."
    exit 1
fi

# Lancer les services
echo "📦 Démarrage des services Docker..."
docker-compose up -d

# Attendre que la base de données soit prête
echo "⏳ Attente de la base de données..."
sleep 5

echo "✅ Le projet Iran News est maintenant en ligne!"
echo ""
echo "📍 URLs d'accès:"
echo "   • Frontend: http://localhost:8000"
echo "   • Admin: http://localhost:8000/admin"
echo ""
echo "🔐 Identifiants de connexion:"
echo "   • Email: admin@irannews.com ou editeur@irannews.com"
echo "   • Password: password"
echo ""
echo "📊 Base de données:"
echo "   • Host: localhost"
echo "   • Port: 5432"
echo "   • Database: iran_news"
echo "   • User: postgres"
echo "   • Password: password"
echo ""
echo "🛑 Pour arrêter les services: docker-compose down"
