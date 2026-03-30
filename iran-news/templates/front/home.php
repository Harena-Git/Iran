<?php 
    Session::start();
    include TEMPLATES_PATH . '/layout/header.php'; 
?>

<div>
    <section>
        <h1>Bienvenue sur Iran News</h1>
        <p>Les dernières actualités sur l'Iran</p>
    </section>

    <section>
        <h2>Articles récents</h2>
        
        <?php if (empty($articles)): ?>
            <p>Aucun article disponible pour le moment.</p>
        <?php else: ?>
            <div>
                <?php foreach ($articles as $article): ?>
                    <article>
                        <h3><a href="/article/<?php echo htmlspecialchars($article['slug']); ?>">
                            <?php echo htmlspecialchars($article['title']); ?>
                        </a></h3>
                        <p>
                            <span>
                                <a href="/category/<?php echo htmlspecialchars($article['category_slug']); ?>">
                                    <?php echo htmlspecialchars($article['category_name'] ?? 'Sans catégorie'); ?>
                                </a>
                            </span>
                            <span><?php echo date('d/m/Y', strtotime($article['published_at'])); ?></span>
                            <span>Par <?php echo htmlspecialchars($article['author_name'] ?? 'Anonyme'); ?></span>
                        </p>
                        <p><?php echo htmlspecialchars($article['excerpt']); ?></p>
                        <a href="/article/<?php echo htmlspecialchars($article['slug']); ?>">Lire l'article</a>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if ($totalPages > 1): ?>
                <div>
                    <?php if ($currentPage > 1): ?>
                        <a href="/?page=1">« Première</a>
                        <a href="/?page=<?php echo $currentPage - 1; ?>">‹ Précédente</a>
                    <?php endif; ?>

                    <span>Page <?php echo $currentPage; ?> sur <?php echo $totalPages; ?></span>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="/?page=<?php echo $currentPage + 1; ?>">Suivante ›</a>
                        <a href="/?page=<?php echo $totalPages; ?>">Dernière »</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</div>

<?php include TEMPLATES_PATH . '/layout/footer.php'; ?>
