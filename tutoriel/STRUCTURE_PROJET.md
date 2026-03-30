# Structure du Projet Iran News

## 📁 Organisation des Dossiers

```
iran-news/
│
├── public/                           # Point d'entrée accessible au navigateur
│   ├── index.php                     # Routeur principal
│   ├── .htaccess                     # Rewriting des URLs
│   └── assets/
│       ├── css/
│       │   └── style.css             # Styles CSS
│       ├── js/
│       │   └── main.js               # JavaScript principal
│       └── images/                   # Images statiques
│
├── src/                              # Logique applicative
│   ├── controllers/                  # Contrôleurs MVC
│   │   ├── auth.php                  # Authentification
│   │   ├── home.php                  # Page d'accueil
│   │   ├── article.php               # Détail des articles
│   │   ├── category.php              # Catégories
│   │   └── admin.php                 # Backoffice
│   │
│   ├── models/                       # Modèles de données
│   │   ├── user.php                  # Gestion des utilisateurs
│   │   ├── article.php               # Gestion des articles
│   │   ├── category.php              # Gestion des catégories
│   │   └── tag.php                   # Gestion des tags
│   │
│   └── core/                         # Classes centrales
│       ├── router.php                # Routeur d'URL
│       ├── database.php              # Connexion PDO
│       └── session.php               # Gestion des sessions
│
├── config/                           # Configuration
│   └── database.php                  # Paramètres de connexion DB
│
├── templates/                        # Vues (affichage)
│   ├── layout/
│   │   ├── header.php                # En-tête
│   │   └── footer.php                # Pied de page
│   │
│   ├── front/                        # Pages publiques
│   │   ├── home.php                  # Accueil
│   │   ├── article.php               # Détail d'un article
│   │   ├── category.php              # Articles d'une catégorie
│   │   └── 404.php                   # Page d'erreur 404
│   │
│   └── admin/                        # Pages backoffice
│       ├── login.php                 # Formulaire de connexion
│       ├── dashboard.php             # Tableau de bord
│       ├── articles.php              # Liste des articles
│       └── edit_article.php          # Créer/Éditer un article
│
├── sql/                              # Base de données
│   └── init.sql                      # Script d'initialisation
│
├── uploads/                          # Fichiers uploadés
│   └── images/                       # Images d'articles
│
├── logs/                             # Fichiers logs
│
├── .env                              # Variables d'environnement
├── .gitignore                        # Fichiers à ignorer
├── docker-compose.yml                # Configuration Docker
├── README.md                         # Documentation principales
├── start.sh                          # Script démarrage (Linux/Mac)
└── start.bat                         # Script démarrage (Windows)
```

## 🔄 Flux de Requête

1. **Navigateur** → Envoie une requête HTTP
2. **Apache + .htaccess** → Redirige vers `public/index.php`
3. **index.php** → Charge les dépendances et instancie le Router
4. **Router.php** → Analyse l'URL et détermine le contrôleur/action
5. **Contrôleur** → Traite la logique et récupère les données via les modèles
6. **Modèles** → Effectuent les requêtes PDO à la base de données
7. **Template** → Affiche les données via les vues PHP
8. **Navigateur** → Reçoit le HTML rendu

## 🔐 Architecture Sécurité

### PDO (Prepared Statements)
- Protection contre les injections SQL
- Requêtes paramétrées obligatoires

### Passwords
- Hash avec `password_hash()` (BCRYPT)
- Vérification avec `password_verify()`

### Sessions
- CSRF Token pour les formulaires
- Restriction d'accès au backoffice

### Filtrage
- `htmlspecialchars()` pour l'affichage
- `trim()` et validation des entrées

## 🛣️ Routes Disponibles

### Frontend
- `/` → Page d'accueil
- `/article/[slug]` → Détail d'un article
- `/category/[slug]` → Articles d'une catégorie

### Backoffice
- `/admin/login` → Connexion
- `/admin/logout` → Déconnexion
- `/admin` → Tableau de bord (auth requise)
- `/admin/articles` → Liste des articles (auth requise)
- `/admin/editarticle` → Créer un article (auth requise)
- `/admin/editarticle/[id]` → Éditer un article (auth requise)

## 📊 Base de Données

### Tables
- **users** → Utilisateurs (admin, editeur)
- **categories** → Catégories d'articles
- **articles** → Articles (avec statut draft/published)
- **tags** → Tags/Étiquettes
- **article_tags** → Relation N:N articles/tags
- **images** → Images des articles
- **seo** → Métadonnées SEO

### Index
- Articles par slug (recherche rapide)
- Categories par slug
- Tags par slug

## 🎨 SEO

✅ **URLs propres** → Slugs au lieu d'ID
✅ **Meta tags** → Title, description dans les templates
✅ **Balises HTML** → H1, H2 bien structurées
✅ **Alt sur images** → Attributs alt remplis
✅ **Structure** → Index et hiérarchie logique

## 🚀 Démarrage

### Avec Docker (recommandé)
```bash
# Windows
./start.bat

# Linux/Mac
chmod +x start.sh
./start.sh
```

### Sans Docker
1. Créer la base PostgreSQL
2. Importer `sql/init.sql`
3. Configurer `.env`
4. Accéder via `http://localhost/iran-news/public/`

## 🔑 Identifiants de Test

| Type | Email | Mot de passe |
|------|-------|--------------|
| Admin | admin@irannews.com | password |
| Editeur | editeur@irannews.com | password |

## 📝 Bonnes Pratiques Appliquées

✅ Séparation des responsabilités (MVC)
✅ Pas de logique en affichage
✅ Requêtes SQL avec PDO
✅ Mots de passe hashés (BCRYPT)
✅ CSRF Token sur les formulaires
✅ Slugs pour URLs SEO-ready
✅ Structure modulaire et extensible
✅ Commentaires du code
✅ Gestion des erreurs 404
✅ Docker pour déploiement

## 📚 Pour Aller Plus Loin

- Ajouter un upload d'images
- Ajouter les métadonnées SEO par article
- Implémenter la pagination complète
- Ajouter des permissions (RBAC)
- Configurer email/notifications
- Ajouter une recherche d'articles
- Implémenter les commentaires
- Ajouter des analytics
