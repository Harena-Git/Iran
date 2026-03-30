<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - Iran News</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($title); ?></h1>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

        <div>
            <label for="title">Titre</label>
            <input type="text" id="title" name="title" required 
                   value="<?php echo htmlspecialchars($article['title'] ?? ''); ?>">
        </div>

        <div>
            <label for="excerpt">Extrait</label>
            <textarea id="excerpt" name="excerpt" rows="3"><?php echo htmlspecialchars($article['excerpt'] ?? ''); ?></textarea>
        </div>

        <div>
            <label for="content">Contenu</label>
            <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($article['content'] ?? ''); ?></textarea>
        </div>

        <div>
            <label for="category_id">Catégorie</label>
            <select id="category_id" name="category_id">
                <option value="">-- Sélectionnez une catégorie --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>"
                            <?php echo (isset($article['category_id']) && $article['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="status">Statut</label>
            <select id="status" name="status">
                <option value="draft" <?php echo (isset($article['status']) && $article['status'] === 'draft') ? 'selected' : ''; ?>>Brouillon</option>
                <option value="published" <?php echo (isset($article['status']) && $article['status'] === 'published') ? 'selected' : ''; ?>>Publié</option>
            </select>
        </div>

        <button type="submit">Enregistrer</button>
        <a href="/admin/articles">Annuler</a>
    </form>
</body>
</html>
