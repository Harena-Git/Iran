# Guide Docker - Iran News

Ce guide explique le fonctionnement de Docker pour le projet Iran News.

---

## Architecture Docker

```
┌─────────────────────────────────────────────────────────┐
│                    Docker Network                        │
│                  (iran_network)                          │
│                                                          │
│  ┌──────────────┐              ┌─────────────────────┐  │
│  │     web      │              │         db          │  │
│  │  ┌────────┐  │              │  ┌───────────────┐  │  │
│  │  │ Apache │  │              │  │   PostgreSQL  │  │  │
│  │  │  + PHP │  │              │  │     15        │  │  │
│  │  │  Port  │  │              │  │    Port 5432  │  │  │
│  │  │  8000  │  │──────────────│  │               │  │  │
│  │  └────────┘  │              │  └───────────────┘  │  │
│  │              │              │                     │  │
│  │  Dockerfile  │              │  Volume:            │  │
│  │  custom     │              │  postgres_data      │  │
│  └──────────────┘              └─────────────────────┘  │
└─────────────────────────────────────────────────────────┘
```

---

## Services

| Service | Image | Port | Description |
|---------|-------|------|-------------|
| **web** | Dockerfile custom | 8000 | Apache + PHP 8.1 avec mod_rewrite |
| **db** | postgres:15 | 5432 | Base de données PostgreSQL |

---

## Fichiers Docker

| Fichier | Rôle |
|---------|------|
| `docker-compose.yml` | Définit les services, ports, volumes, réseau |
| `Dockerfile` | Image personnalisée PHP-Apache avec mod_rewrite activé |
| `sql/init.sql` | Création automatique des tables au démarrage |
| `sql/seed.sql` | Données initiales (à injecter manuellement) |

---

## Commandes essentielles

### Lancer le projet
```bash
docker-compose up -d
```

### Arrêter le projet
```bash
docker-compose down
```

### Arrêter et supprimer les données
```bash
docker-compose down -v
```

### Reconstruire l'image (après modification du Dockerfile)
```bash
docker-compose build --no-cache web
docker-compose up -d
```

### Voir les logs
```bash
docker-compose logs -f
docker-compose logs -f web    # Logs du service web uniquement
docker-compose logs -f db     # Logs de la base de données
```

---

## Gestion de la base de données

### Injecter les données initiales (seed.sql)
```bash
docker exec -i iran_news_db psql -U postgres -d iran_news < sql/seed.sql
```

### Se connecter à PostgreSQL
```bash
docker exec -it iran_news_db psql -U postgres -d iran_news
```

### Commandes SQL dans le container
```bash
# Lister les tables
\dt

# Voir les utilisateurs
SELECT * FROM users;

# Quitter
\q
```

---

## Résolution de problèmes courants

### Erreur "Cannot redeclare getEnv()"
**Cause** : Conflit avec la fonction native PHP `getEnv()`

**Solution** : 
- Renommer la fonction personnalisée en `getEnvCustom()` dans `config/database.php`

### Erreur "Not Found" sur les URLs
**Cause** : mod_rewrite non activé dans Apache

**Solution** :
- Vérifier que le `Dockerfile` active `a2enmod rewrite`
- Reconstruire l'image : `docker-compose build --no-cache web`

### Port 8000 déjà utilisé
```bash
# Trouver le processus
netstat -ano | findstr :8000

# Ou changer le port dans docker-compose.yml
ports:
  - "8080:80"  # Au lieu de 8000:80
```

---

## Structure des volumes

| Volume Host | Volume Container | Contenu |
|-------------|------------------|---------|
| `./public` | `/var/www/html` | Fichiers web (index.php, .htaccess, assets) |
| `./src` | `/var/www/src` | Code PHP (controllers, models, core) |
| `./config` | `/var/www/config` | Configuration (database.php) |
| `./templates` | `/var/www/templates` | Vues PHP (layout, front, admin) |
| `postgres_data` | `/var/lib/postgresql/data` | Données PostgreSQL persistantes |

---

## Accès aux services

| Service | URL / Commande |
|---------|----------------|
| **Frontend** | http://localhost:8000 |
| **Admin** | http://localhost:8000/admin |
| **PostgreSQL** | localhost:5432 |

### Identifiants base de données
- **Host** : localhost (ou `db` depuis le container web)
- **Port** : 5432
- **Database** : iran_news
- **User** : postgres
- **Password** : password

---

## Cycle de vie d'un container

```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   Créer     │────▶│   Lancer    │────▶│   Arrêter   │
│ docker run  │     │ docker start│     │ docker stop │
└─────────────┘     └─────────────┘     └──────┬──────┘
       ▲                                         │
       │                                         │
       │         ┌─────────────┐                │
       │         │   Supprimer │◄───────────────┘
       └─────────│ docker rm   │
                 └─────────────┘
```

---

## Cheat Sheet

```bash
# Voir les containers actifs
docker ps

# Voir tous les containers (même arrêtés)
docker ps -a

# Redémarrer un service
docker-compose restart web

# Entrer dans un container
docker exec -it iran_news_web bash
docker exec -it iran_news_db bash

# Copier un fichier vers/depuis un container
docker cp localfile.txt iran_news_web:/var/www/html/
docker cp iran_news_web:/var/www/html/file.txt ./

# Supprimer un volume
docker volume rm iran-news_postgres_data
```
