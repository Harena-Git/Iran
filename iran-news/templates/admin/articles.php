<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tous les articles - Iran News</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <h1>Tous les articles</h1>

    <a href="/admin/editarticle">Créer un article</a>
    <a href="/admin">Retour au tableau de bord</a>

    <?php if (empty($articles)): ?>
        <p>Aucun article pour le moment.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Auteur</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($article['title']); ?></td>
                        <td><?php echo htmlspecialchars($article['category_name'] ?? 'Aucune'); ?></td>
                        <td><?php echo htmlspecialchars($article['author_name'] ?? 'Inconnu'); ?></td>
                        <td><?php echo $article['status'] === 'published' ? '✅ Publié' : '📝 Brouillon'; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($article['created_at'])); ?></td>
                        <td>
                            <a href="/admin/editarticle/<?php echo $article['id']; ?>">Éditer</a>
                            <a href="/admin/deletearticle/<?php echo $article['id']; ?>"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">Supprimer</a>
                            <?php if ($article['status'] === 'published'): ?>
                                <a href="/article/<?php echo htmlspecialchars($article['slug']); ?>" target="_blank">Voir</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
