# Guide de Démarrage - Iran News

## 1. Lancer le projet avec Docker

**Windows :**
```bash
cd iran-news
./start.bat
```

**Linux/Mac :**
```bash
cd iran-news
chmod +x start.sh
./start.sh
```

**Ou manuellement :**
```bash
cd iran-news
docker-compose up -d
```

---

## 2. Accéder au projet

| Service | URL |
|---------|-----|
| **Frontend** | http://localhost:8000 |
| **Backoffice** | http://localhost:8000/admin |

---

## 3. Identifiants de connexion (Admin)

| Email | Mot de passe | Rôle |
|-------|--------------|------|
| admin@irannews.com | password | Administrateur |
| editeur@irannews.com | password | Éditeur |

---

## 4. Commandes utiles

```bash
# Voir les conteneurs en cours
docker ps

# Voir les logs
docker-compose logs -f

# Arrêter les services
docker-compose down

# Arrêter et supprimer les données
docker-compose down -v

# Redémarrer
docker-compose restart
```

---

## 5. Configuration base de données

| Paramètre | Valeur |
|-----------|--------|
| Host | localhost |
| Port | 5432 |
| Database | iran_news |
| User | postgres |
| Password | password |

---

## 6. Structure des services Docker

- **web** : PHP 8.1 + Apache (port 8000)
- **db** : PostgreSQL 15 (port 5432)
