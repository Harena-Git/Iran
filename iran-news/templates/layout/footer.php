    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <div class="footer__section">
                    <h3>À propos</h3>
                    <p>Iran News est une plateforme d'actualités dédiée aux informations sur l'Iran.</p>
                </div>
                <div class="footer__section">
                    <h3>Catégories</h3>
                    <ul class="footer__list">
                        <?php foreach ($categories ?? [] as $cat): ?>
                            <li><a href="/category/<?php echo htmlspecialchars($cat['slug']); ?>">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="footer__section">
                    <h3>Liens</h3>
                    <ul class="footer__list">
                        <li><a href="/">Accueil</a></li>
                        <li><a href="/admin">Backoffice</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer__bottom">
                <p>&copy; 2024 Iran News. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="/assets/js/main.js"></script>
</body>
</html>
