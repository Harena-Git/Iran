<?php 
    Session::start();
    include TEMPLATES_PATH . '/layout/header.php'; 
?>

<div>
    <div>
        <h1><?php echo htmlspecialchars($article['title']); ?></h1>

        <div>
            <span>
                <a href="/category/<?php echo htmlspecialchars($article['category_slug'] ?? '#'); ?>">
                    <?php echo htmlspecialchars($article['category_name'] ?? 'Sans catégorie'); ?>
                </a>
            </span>
            <span><?php echo date('d F Y', strtotime($article['published_at'])); ?></span>
            <span>Par <?php echo htmlspecialchars($article['author_name'] ?? 'Anonyme'); ?></span>
        </div>

        <div>
            <?php echo nl2br(htmlspecialchars($article['content'])); ?>
        </div>

        <?php if (!empty($tags)): ?>
            <div>
                <h3>Tags</h3>
                <ul>
                    <?php foreach ($tags as $tag): ?>
                        <li><span><?php echo htmlspecialchars($tag['name']); ?></span></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <a href="/">Retour à l'accueil</a>
    </div>
</div>

<?php include TEMPLATES_PATH . '/layout/footer.php'; ?>
