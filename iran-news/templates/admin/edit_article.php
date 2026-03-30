<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - Iran News</title>
    <!-- TinyMCE local -->
    <script src="/tinymce/tinymce/js/tinymce/tinymce.min.js"></script>
</head>
<body>
    <h1><?php echo htmlspecialchars($title); ?></h1>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

        <div>
            <label for="title">Titre *</label>
            <input type="text" id="title" name="title" required 
                   value="<?php echo htmlspecialchars($article['title'] ?? ''); ?>"
                   style="width:100%; padding:8px; font-size:16px;">
        </div>

        <div>
            <label for="excerpt">Extrait (résumé)</label>
            <textarea id="excerpt" name="excerpt" rows="3" style="width:100%; padding:8px;"><?php echo htmlspecialchars($article['excerpt'] ?? ''); ?></textarea>
        </div>

        <div>
            <label for="content">Contenu * (éditeur TinyMCE)</label>
            <textarea id="content" name="content" rows="15" required><?php echo htmlspecialchars($article['content'] ?? ''); ?></textarea>
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

        <!-- Images existantes -->
        <?php if (!empty($articleImages)): ?>
            <div>
                <h3>Images actuelles</h3>
                <?php foreach ($articleImages as $img): ?>
                    <div style="display:inline-block; margin:5px; text-align:center;">
                        <img src="<?php echo htmlspecialchars($img['url']); ?>" 
                             alt="<?php echo htmlspecialchars($img['alt']); ?>" 
                             style="max-width:150px; max-height:150px;">
                        <br>
                        <small><?php echo htmlspecialchars($img['alt']); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Upload nouvelles images -->
        <div>
            <label for="images">Ajouter des images</label>
            <input type="file" id="images" name="images[]" multiple accept="image/*">
            <small>Formats acceptés : JPEG, PNG, GIF, WebP. Max 5 Mo par image.</small>
        </div>

        <div>
            <label for="related_articles">Articles référencés</label>
            <select id="related_articles" name="related_articles[]" multiple style="width:100%; height:120px;">
                <?php foreach ($allArticles as $art): ?>
                    <?php if (!isset($article) || $article['id'] !== $art['id']): ?>
                        <option value="<?php echo $art['id']; ?>" <?php echo in_array($art['id'], $currentRelatedIds ?? []) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($art['title']); ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <small>Maintenez Ctrl/Cmd appuyé pour sélectionner plusieurs articles.</small>
        </div>

        <br>
        <button type="submit">Enregistrer</button>
        <a href="/admin/articles">Annuler</a>
    </form>

    <!-- Initialisation TinyMCE -->
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'lists link image',
            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image',
            height: 400,
            license_key: 'gpl',
            language: 'fr_FR',
            menubar: false,
            branding: false
        });
    </script>
</body>
</html>
