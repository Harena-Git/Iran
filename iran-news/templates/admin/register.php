<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <h1>Iran News</h1>
    <h2>Inscription</h2>

    <?php if (!empty($error)): ?>
        <div><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div><?php echo htmlspecialchars($success); ?></div>
        <p><a href="/admin/login">Se connecter</a></p>
    <?php else: ?>
        <form method="POST">
            <div>
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required minlength="3">
            </div>

            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div>
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>

            <div>
                <label for="password_confirm">Confirmer le mot de passe</label>
                <input type="password" id="password_confirm" name="password_confirm" required minlength="6">
            </div>

            <button type="submit">S'inscrire</button>
        </form>

        <p>Déjà inscrit ? <a href="/admin/login">Se connecter</a></p>
    <?php endif; ?>
</body>
</html>
