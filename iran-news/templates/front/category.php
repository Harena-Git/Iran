<?php 
    Session::start();
    include TEMPLATES_PATH . '/layout/header.php'; 
?>

<div class="container">
    <h1>Catégorie: <?php echo htmlspecialchars($category['name']); ?></h1>

    <?php if (empty($articles)): ?>
        <p>Aucun article dans cette catégorie.</p>
    <?php else: ?>
        <div class="articles-grid">
            <?php foreach ($articles as $article): ?>
                <article class="article-card">
                    <h3><a href="/article/<?php echo htmlspecialchars($article['slug']); ?>">
                        <?php echo htmlspecialchars($article['title']); ?>
                    </a></h3>
                    <p class="article-meta">
                        <span class="date"><?php echo date('d/m/Y', strtotime($article['created_at'])); ?></span>
                    </p>
                    <p class="excerpt"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                    <a href="/article/<?php echo htmlspecialchars($article['slug']); ?>" class="read-more">Lire l'article</a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <a href="/" class="btn">Retour à l'accueil</a>
</div>

<?php include TEMPLATES_PATH . '/layout/footer.php'; ?>
