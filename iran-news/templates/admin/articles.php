<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes articles - Iran News</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="admin-panel">
            <h1>Mes articles</h1>

            <a href="/admin/editarticle" class="btn btn-primary">Créer un article</a>

            <?php if (empty($articles)): ?>
                <p>Vous n'avez pas d'articles pour le moment.</p>
            <?php else: ?>
                <table class="articles-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Slug</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $article): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($article['title']); ?></td>
                                <td><?php echo htmlspecialchars($article['slug']); ?></td>
                                <td><?php echo htmlspecialchars($article['status']); ?></td>
                                <td>
                                    <a href="/admin/editarticle/<?php echo $article['id']; ?>" class="btn btn-small">Éditer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <a href="/admin" class="btn">Retour au tableau de bord</a>
        </div>
    </div>
</body>
</html>
