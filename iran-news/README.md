# Iran News - Plateforme de Actualités

Projet de site de news sur l'Iran développé en PHP sans framework avec PostgreSQL.

## Fonctionnalités

- 📰 Affichage des articles par catégorie
- 🏷️ Système de tags
- 👤 Backoffice avec authentification
- ✏️ CRUD complet (Créer, Lire, Modifier, Supprimer)
- 🖼️ Gestion des images
- 🔍 SEO optimisé (slugs, métas, structure HTML)
- 🛡️ Sécurité (PDO, password_hash, filtrage entrées)

## Prérequis

- PHP 8.1+
- PostgreSQL 13+
- Apache avec mod_rewrite activé
- Composer (optionnel)

## Installation

### Avec Docker
```bash
docker-compose up -d
```

### Manuelle
1. Créer la base de données PostgreSQL
2. Importer le fichier `sql/init.sql`
3. Configurer `.env`
4. Placer le dossier `public` à la racine du serveur web

## Structure du Projet

```
iran-news/
├── public/              # Point d'entrée (index.php)
│   ├── index.php       # Routeur principal
│   ├── .htaccess       # Rewriting URLs
│   └── assets/         # CSS, JS, images
├── src/
│   ├── controllers/    # Contrôleurs
│   ├── models/         # Classes modèles (PDO)
│   └── core/           # Router, Database, Session
├── config/             # Base de données
├── templates/          # Vues PHP
│   ├── layout/         # Header, footer
│   ├── front/          # Pages publiques
│   └── admin/          # Pages backoffice
├── sql/                # DDL/DML
├── uploads/            # Images uploadées
├── logs/               # Fichiers logs
└── .env                # Variables d'environnement
```

## Accès

- **Frontend** : `http://localhost:8000/`
- **Backend** : `http://localhost:8000/admin` (login requis)

## Technologies

- **Backend** : PHP 8.1
- **Base de données** : PostgreSQL 15
- **Serveur Web** : Apache
- **Containerisation** : Docker & Docker Compose

## Sécurité

✅ Requêtes SQL avec PDO (prévient les injections SQL)
✅ Mots de passe hashés (password_hash)
✅ Filtrage des entrées utilisateur
✅ Sessions PHP sécurisées
✅ HTACCESS pour les URLs propres

## SEO

✅ URLs avec slugs
✅ Balises meta (title, description)
✅ Structure HTML propre (h1, h2, etc.)
✅ Attributs alt sur les images
✅ Index au niveau de la base de données

## Auteur

Projet d'examen - Web Avancé
