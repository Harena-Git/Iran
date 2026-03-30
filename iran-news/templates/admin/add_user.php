<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - Iran News</title>
</head>
<body>

<h1><?php echo htmlspecialchars($title); ?></h1>

<?php if (!empty($error)): ?>
    <div><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

    <div>
        <label for="username">Nom d'utilisateur *</label>
        <input type="text" id="username" name="username" required
               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
    </div>

    <div>
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" required
               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
    </div>

    <div>
        <label for="password">Mot de passe * (min 6 caractères)</label>
        <input type="password" id="password" name="password" required minlength="6">
    </div>

    <div>
        <label for="role">Rôle</label>
        <select id="role" name="role">
            <option value="editor" <?php echo (isset($_POST['role']) && $_POST['role'] === 'editor') ? 'selected' : ''; ?>>Éditeur</option>
            <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] === 'admin') ? 'selected' : ''; ?>>Administrateur</option>
        </select>
    </div>

    <button type="submit">Créer l'utilisateur</button>
    <a href="/admin">Annuler</a>
</form>

</body>
</html>
