@echo off
REM Script de démarrage du projet Iran News avec Docker (Windows)

echo 🚀 Démarrage du projet Iran News...

REM Vérifier si Docker est installé
docker --version >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo ❌ Docker n'est pas installé. Veuillez installer Docker Desktop.
    exit /b 1
)

REM Vérifier si Docker Compose est installé
docker-compose --version >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo ❌ Docker Compose n'est pas installé. Veuillez installer Docker Desktop.
    exit /b 1
)

REM Lancer les services
echo 📦 Démarrage des services Docker...
docker-compose up -d

REM Attendre que la base de données soit prête
echo ⏳ Attente de la base de données...
timeout /t 5 /nobreak

echo ✅ Le projet Iran News est maintenant en ligne!
echo.
echo 📍 URLs d'accès:
echo    • Frontend: http://localhost:8000
echo    • Admin: http://localhost:8000/admin
echo.
echo 🔐 Identifiants de connexion:
echo    • Email: admin@irannews.com ou editeur@irannews.com
echo    • Password: password
echo.
echo 📊 Base de données:
echo    • Host: localhost
echo    • Port: 5432
echo    • Database: iran_news
echo    • User: postgres
echo    • Password: password
echo.
echo 🛑 Pour arrêter les services: docker-compose down
