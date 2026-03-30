<?php 
    Session::start();
    include TEMPLATES_PATH . '/layout/header.php'; 
?>

<section>
    <h2>Catégorie : <?php echo htmlspecialchars($category['name']); ?></h2>

    <?php if (empty($articles)): ?>
        <p>Aucun article dans cette catégorie.</p>
    <?php else: ?>
        <div>
            <?php foreach ($articles as $article): ?>
                <article>
                    <h3>
                        <a href="/article/<?php echo htmlspecialchars($article['slug']); ?>">
                            <?php echo htmlspecialchars($article['title']); ?>
                        </a>
                    </h3>
                    <p>
                        <time datetime="<?php echo date('Y-m-d', strtotime($article['created_at'])); ?>">
                            <?php echo date('d/m/Y', strtotime($article['created_at'])); ?>
                        </time>
                    </p>
                    <p><?php echo htmlspecialchars($article['excerpt']); ?></p>
                    <a href="/article/<?php echo htmlspecialchars($article['slug']); ?>">Lire l'article →</a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <nav aria-label="Retour">
        <a href="/">← Retour à l'accueil</a>
    </nav>
</section>

<?php include TEMPLATES_PATH . '/layout/footer.php'; ?>
