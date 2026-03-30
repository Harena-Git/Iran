<?php 
    Session::start();
    include TEMPLATES_PATH . '/layout/header.php'; 
?>

<div class="container">
    <section class="hero">
        <h1>Bienvenue sur Iran News</h1>
        <p>Les dernières actualités sur l'Iran</p>
    </section>

    <section class="articles">
        <h2>Articles récents</h2>
        
        <?php if (empty($articles)): ?>
            <p>Aucun article disponible pour le moment.</p>
        <?php else: ?>
            <div class="articles-grid">
                <?php foreach ($articles as $article): ?>
                    <article class="article-card">
                        <h3><a href="/article/<?php echo htmlspecialchars($article['slug']); ?>">
                            <?php echo htmlspecialchars($article['title']); ?>
                        </a></h3>
                        <p class="article-meta">
                            <span class="category">
                                <a href="/category/<?php echo htmlspecialchars($article['category_slug']); ?>">
                                    <?php echo htmlspecialchars($article['category_name'] ?? 'Sans catégorie'); ?>
                                </a>
                            </span>
                            <span class="date"><?php echo date('d/m/Y', strtotime($article['published_at'])); ?></span>
                            <span class="author">Par <?php echo htmlspecialchars($article['author_name'] ?? 'Anonyme'); ?></span>
                        </p>
                        <p class="excerpt"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                        <a href="/article/<?php echo htmlspecialchars($article['slug']); ?>" class="read-more">Lire l'article</a>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($currentPage > 1): ?>
                        <a href="/?page=1" class="btn">« Première</a>
                        <a href="/?page=<?php echo $currentPage - 1; ?>" class="btn">‹ Précédente</a>
                    <?php endif; ?>

                    <span class="page-info">Page <?php echo $currentPage; ?> sur <?php echo $totalPages; ?></span>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="/?page=<?php echo $currentPage + 1; ?>" class="btn">Suivante ›</a>
                        <a href="/?page=<?php echo $totalPages; ?>" class="btn">Dernière »</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</div>

<?php include TEMPLATES_PATH . '/layout/footer.php'; ?>
