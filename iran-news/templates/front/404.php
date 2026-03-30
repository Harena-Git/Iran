<?php 
    Session::start();
    $description = 'Cette page n\'existe pas sur Iran News';
    include TEMPLATES_PATH . '/layout/header.php'; 
?>

<section>
    <h2>404 - Page non trouvée</h2>
    <p>Désolé, la page que vous recherchez n'existe pas.</p>
    <nav aria-label="Retour">
        <a href="/">← Retour à l'accueil</a>
    </nav>
</section>

<?php include TEMPLATES_PATH . '/layout/footer.php'; ?>
