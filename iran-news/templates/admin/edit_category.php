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
        <div style="color:red;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

        <div>
            <label for="name">Nom de la catégorie *</label>
            <input type="text" id="name" name="name" required
                   value="<?php echo htmlspecialchars($category['name'] ?? ''); ?>">
        </div>

        <p>Slug actuel : <strong><?php echo htmlspecialchars($category['slug'] ?? ''); ?></strong> (sera régénéré automatiquement)</p>

        <button type="submit">Modifier</button>
        <a href="/admin/categories">Annuler</a>
    </form>
</body>
</html>
