<?php 
    Session::start();
    include TEMPLATES_PATH . '/layout/header.php'; 
?>

<div class="container">
    <div class="article-detail">
        <h1><?php echo htmlspecialchars($article['title']); ?></h1>

        <div class="article-meta">
            <span class="category">
                <a href="/category/<?php echo htmlspecialchars($article['category_slug'] ?? '#'); ?>">
                    <?php echo htmlspecialchars($article['category_name'] ?? 'Sans catégorie'); ?>
                </a>
            </span>
            <span class="date"><?php echo date('d F Y', strtotime($article['published_at'])); ?></span>
            <span class="author">Par <?php echo htmlspecialchars($article['author_name'] ?? 'Anonyme'); ?></span>
        </div>

        <div class="article-content">
            <?php echo nl2br(htmlspecialchars($article['content'])); ?>
        </div>

        <?php if (!empty($tags)): ?>
            <div class="article-tags">
                <h3>Tags</h3>
                <ul class="tags-list">
                    <?php foreach ($tags as $tag): ?>
                        <li><span class="tag"><?php echo htmlspecialchars($tag['name']); ?></span></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <a href="/" class="btn">Retour à l'accueil</a>
    </div>
</div>

<?php include TEMPLATES_PATH . '/layout/footer.php'; ?>
