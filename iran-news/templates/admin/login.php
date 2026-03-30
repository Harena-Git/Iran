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
    <h2>Connexion</h2>

    <?php if (!empty($error)): ?>
        <div><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required autofocus>
        </div>

        <div>
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">Connexion</button>
    </form>

    <p>Pas encore de compte ? <a href="/admin/register">S'inscrire</a></p>
</body>
</html>
