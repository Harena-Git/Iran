<?php 
    Session::start();
    include TEMPLATES_PATH . '/layout/header.php'; 
?>

<!-- Section héro -->
<section>
    <h2>Bienvenue sur Iran News</h2>
    <p>Les dernières actualités sur la guerre en Iran</p>
</section>

<!-- Section articles récents -->
<section>
    <h3>Articles récents</h3>
    
    <?php if (empty($articles)): ?>
        <p>Aucun article disponible pour le moment.</p>
    <?php else: ?>
        <div>
            <?php foreach ($articles as $article): ?>
                <article>
                    <h4>
                        <a href="/article/<?php echo htmlspecialchars($article['slug']); ?>">
                            <?php echo htmlspecialchars($article['title']); ?>
                        </a>
                    </h4>
                    <p>
                        <span>
                            <a href="/category/<?php echo htmlspecialchars($article['category_slug']); ?>">
                                <?php echo htmlspecialchars($article['category_name'] ?? 'Sans catégorie'); ?>
                            </a>
                        </span>
                        | <time datetime="<?php echo date('Y-m-d', strtotime($article['published_at'])); ?>">
                            <?php echo date('d/m/Y', strtotime($article['published_at'])); ?>
                        </time>
                        | Par <?php echo htmlspecialchars($article['author_name'] ?? 'Anonyme'); ?>
                    </p>
                    <p><?php echo htmlspecialchars($article['excerpt']); ?></p>
                    <a href="/article/<?php echo htmlspecialchars($article['slug']); ?>">Lire l'article →</a>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <nav aria-label="Pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="/?page=1">« Première</a>
                    <a href="/?page=<?php echo $currentPage - 1; ?>">‹ Précédente</a>
                <?php endif; ?>

                <span>Page <?php echo $currentPage; ?> sur <?php echo $totalPages; ?></span>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="/?page=<?php echo $currentPage + 1; ?>">Suivante ›</a>
                    <a href="/?page=<?php echo $totalPages; ?>">Dernière »</a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</section>

<?php include TEMPLATES_PATH . '/layout/footer.php'; ?>
