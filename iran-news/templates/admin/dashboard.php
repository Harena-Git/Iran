<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Iran News</title>
</head>
<body>
    <h1>Tableau de bord</h1>
    <p>Bienvenue, <?php echo htmlspecialchars($user['username']); ?> !</p>

    <div>
        <a href="/admin/articles">Mes articles</a>
        <a href="/admin/editarticle">Créer un article</a>
        <a href="/admin/logout">Déconnexion</a>
    </div>

    <div>
        <h2>Informations du compte</h2>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <p>Rôle: Editeur</p>
    </div>
</body>
</html>
