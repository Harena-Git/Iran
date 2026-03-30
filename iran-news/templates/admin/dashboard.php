<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Iran News</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="admin-panel">
            <h1>Tableau de bord</h1>
            <p>Bienvenue, <?php echo htmlspecialchars($user['username']); ?> !</p>

            <div class="admin-menu">
                <a href="/admin/articles" class="btn">Mes articles</a>
                <a href="/admin/editarticle" class="btn btn-primary">Créer un article</a>
                <a href="/admin/logout" class="btn">Déconnexion</a>
            </div>

            <div class="admin-info">
                <h2>Informations du compte</h2>
                <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                <p>Rôle: Editeur</p>
            </div>
        </div>
    </div>
</body>
</html>
