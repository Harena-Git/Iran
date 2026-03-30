<?php 
    Session::start();
    include TEMPLATES_PATH . '/layout/header.php'; 
?>

<article>
    <h2><?php echo htmlspecialchars($article['title']); ?></h2>

    <div>
        <span>
            <a href="/category/<?php echo htmlspecialchars($article['category_slug'] ?? '#'); ?>">
                <?php echo htmlspecialchars($article['category_name'] ?? 'Sans catégorie'); ?>
            </a>
        </span>
        | <time datetime="<?php echo !empty($article['published_at']) ? date('Y-m-d', strtotime($article['published_at'])) : ''; ?>">
            <?php echo !empty($article['published_at']) ? date('d F Y', strtotime($article['published_at'])) : 'Non publié'; ?>
        </time>
        | Par <?php echo htmlspecialchars($article['author_name'] ?? 'Anonyme'); ?>
    </div>

    <!-- Images de l'article avec attribut alt pour le SEO -->
    <?php if (!empty($images)): ?>
        <section>
            <h3>Illustrations</h3>
            <?php foreach ($images as $img): ?>
                <figure>
                    <img src="<?php echo htmlspecialchars($img['url']); ?>" 
                         alt="<?php echo htmlspecialchars($img['alt'] ?? 'Illustration: ' . $article['title'] . ' - Iran News'); ?>"
                         width="800" height="auto"
                         style="max-width:100%; height:auto;"
                         onerror="this.style.display='none'; this.insertAdjacentHTML('afterend', '<p class=\'image-error\'>Image non disponible: <?php echo htmlspecialchars($img['alt'] ?? $article['title']); ?></p>');">
                    <?php if (!empty($img['alt'])): ?>
                        <figcaption><?php echo htmlspecialchars($img['alt']); ?></figcaption>
                    <?php endif; ?>
                </figure>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <!-- Contenu HTML issu de TinyMCE (affiché en brut car déjà en HTML) -->
    <section>
        <h3>Contenu de l'article</h3>
        <?php echo $article['content']; ?>
    </section>

    <?php if (!empty($relatedArticles)): ?>
        <section>
            <h3>Articles liés</h3>
            <ul>
                <?php foreach ($relatedArticles as $related): ?>
                    <li>
                        <a href="/article/<?php echo htmlspecialchars($related['slug']); ?>">
                            <?php echo htmlspecialchars($related['title']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <?php if (!empty($tags)): ?>
        <section>
            <h3>Tags</h3>
            <ul>
                <?php foreach ($tags as $tag): ?>
                    <li><span><?php echo htmlspecialchars($tag['name']); ?></span></li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <nav aria-label="Retour">
        <a href="/">← Retour à l'accueil</a>
    </nav>
</article>

<?php include TEMPLATES_PATH . '/layout/footer.php'; ?>
