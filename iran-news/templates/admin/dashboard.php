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
        <h2>Gestion du contenu</h2>
        <ul>
            <li><a href="/admin/articles">Tous les articles</a></li>
            <li><a href="/admin/editarticle">Créer un article</a></li>
            <li><a href="/admin/categories">Gérer les catégories</a></li>
            <li><a href="/admin/adduser">Ajouter un utilisateur</a></li>
        </ul>
    </div>

    <div>
        <h2>Informations du compte</h2>
        <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <p>Rôle: <?php echo htmlspecialchars($user['role'] ?? 'Editeur'); ?></p>
    </div>

    <p><a href="/admin/logout">Déconnexion</a></p>
    <p><a href="/">Voir le site</a></p>
</body>
</html>
