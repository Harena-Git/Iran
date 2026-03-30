# Créer un Formulaire d'Insertion (Backoffice)

Ce guide explique comment créer un formulaire d'insertion dans le backoffice en respectant l'architecture du projet Iran News.

## Architecture (Flux de données)

```
Navigateur → .htaccess (rewriting) → index.php → Router → Controller → Model → PostgreSQL (Docker)
     ↑                                                                                          ↓
     └────────────────────── HTML / Redirect ← Template ←───┘
```

---

## URL Rewriting (public/.htaccess)

Toutes les requêtes passent par `index.php` grâce au `.htaccess` :

```apache
RewriteEngine On
RewriteBase /

# Si ce n'est pas un fichier ou dossier existant
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# Redirige vers index.php
RewriteRule ^(.*)$ index.php [QSA,L]
```

**Résultat :** L'URL `/admin/adduser` est redirigée vers `index.php` qui analyse l'URL et appelle le bon contrôleur/action.

---

## Étapes à suivre

### 1. Modèle (`src/models/[entite].php`)

Créer une méthode `create()` avec prepared statements PDO :

```php
public function create($name, $description, $userId) {
    $stmt = $this->db->prepare('
        INSERT INTO entities (name, description, user_id, created_at)
        VALUES (:name, :description, :user_id, NOW())
        RETURNING id, name
    ');

    return $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':user_id' => $userId
    ]) ? $stmt->fetch() : false;
}
```

**Règles :**
- Toujours utiliser `prepare()` avec des paramètres nommés (`:param`)
- Hash des mots de passe avec `password_hash()` si applicable
- Retourner le résultat avec `RETURNING` pour PostgreSQL

---

### 2. Contrôleur (`src/controllers/[entite].php` ou `admin.php`)

Créer une action pour afficher le formulaire et traiter le POST :

```php
public function addAction($params) {
    // Vérifie l'authentification
    if (!Session::isLoggedIn()) {
        header('Location: /admin/login');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 1. Vérifie le token CSRF
        if (!Session::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            die('Token CSRF invalide');
        }

        // 2. Filtre et valide les entrées
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (!$name) {
            $error = 'Le nom est obligatoire';
        } else {
            // 3. Appelle le modèle
            $this->entityModel->create($name, $description, Session::getUser()['id']);
            
            // 4. Redirection après succès
            header('Location: /admin/entities');
            exit;
        }
    }

    // 5. Génère le token CSRF pour le formulaire
    $csrfToken = Session::generateCSRFToken();

    // 6. Affiche le template
    $this->render('admin/add_entity', [
        'title' => 'Ajouter une entité',
        'csrf_token' => $csrfToken,
        'error' => $error ?? null
    ]);
}
```

**Règles :**
- Vérifier `Session::isLoggedIn()` pour le backoffice
- TOUJOURS vérifier le token CSRF sur les POST
- Filtrer avec `trim()` et `htmlspecialchars()`
- Rediriger après un POST réussi (pattern PRG)

---

### 3. Template (`templates/admin/[form].php`)

Structure du formulaire HTML :

```html
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($title); ?></h1>

        <!-- Messages d'erreur -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" class="entity-form">
            <!-- Token CSRF obligatoire -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

            <div class="form-group">
                <label for="name">Nom *</label>
                <input type="text" id="name" name="name" required
                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4">
                    <?php echo htmlspecialchars($_POST['description'] ?? ''); ?>
                </textarea>
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="/admin/entities" class="btn">Annuler</a>
        </form>
    </div>
</body>
</html>
```

**Règles :**
- Inclure le `csrf_token` en champ caché
- Utiliser `htmlspecialchars()` sur toutes les sorties
- Ajouter `required` sur les champs obligatoires
- Pré-remplir les valeurs en cas d'erreur

---

### 4. Route (`public/index.php` ou router)

Ajouter la route dans le routeur :

```php
// Affichage formulaire
$router->add('/admin/entity/add', 'AdminController', 'addentityAction');

// Ou si formulaire + traitement dans même action (recommandé)
$router->add('/admin/entity/new', 'EntityController', 'newAction');
```

---

### 5. SQL (`sql/init.sql`)

Créer la table si elle n'existe pas (Docker l'exécute automatiquement) :

```sql
CREATE TABLE entities (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    user_id INT REFERENCES users(id) ON DELETE SET NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Docker :** Le fichier `init.sql` est monté dans le conteneur PostgreSQL et exécuté au premier démarrage.

---

## Checklist Sécurité

| Élément | Où | Comment |
|---------|-----|---------|
| Authentification | Contrôleur | `Session::isLoggedIn()` |
| CSRF Token | Template + Controller | `Session::generate/verifyCSRFToken()` |
| Injection SQL | Modèle | `prepare()` + paramètres nommés |
| XSS | Template | `htmlspecialchars()` sur sorties |
| Validation | Contrôleur | `trim()`, `filter_var()`, `intval()` |
| Mots de passe | Modèle | `password_hash()` + `password_verify()` |

---

## Exemple Complet : Ajout d'Utilisateur

**Modèle** (`src/models/user.php:16-29`):
```php
public function create($username, $email, $password, $role = 'editor') {
    $stmt = $this->db->prepare('
        INSERT INTO users (username, email, password, role, created_at)
        VALUES (:username, :email, :password, :role, NOW())
        RETURNING id, username, email, role
    ');

    return $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => password_hash($password, PASSWORD_BCRYPT),
        ':role' => $role
    ]) ? $stmt->fetch() : false;
}
```

**Contrôleur** (`src/controllers/admin.php` - action pour users):
- Vérifier CSRF token
- Valider email avec `filter_var($email, FILTER_VALIDATE_EMAIL)`
- Hasher le mot de passe
- Rediriger vers liste

**Template** (`templates/admin/edit_user.php`):
- Formulaire avec `method="POST"`
- Champ caché `csrf_token`
- Champs : `username`, `email`, `password`, `role`

---

## Démarrage avec Docker

```bash
# Windows
./start.bat

# Linux/Mac
./start.sh
```

L'application est accessible sur `http://localhost:8000`

La base PostgreSQL est sur `localhost:5432`
