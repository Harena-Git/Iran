<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des catégories - Iran News</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <h1>Gestion des catégories</h1>

    <a href="/admin/addcategory">Ajouter une catégorie</a>
    <a href="/admin">Retour au tableau de bord</a>

    <?php if (empty($categories)): ?>
        <p>Aucune catégorie pour le moment.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Slug</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?php echo $cat['id']; ?></td>
                        <td><?php echo htmlspecialchars($cat['name']); ?></td>
                        <td><?php echo htmlspecialchars($cat['slug']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($cat['created_at'])); ?></td>
                        <td>
                            <a href="/admin/editcategory/<?php echo $cat['id']; ?>">Modifier</a>
                            <a href="/admin/deletecategory/<?php echo $cat['id']; ?>"
                               onclick="return confirm('Supprimer cette catégorie ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
