<?php 
    Session::start();
    include TEMPLATES_PATH . '/layout/header.php'; 
?>

<div>
    <h1>404</h1>
    <h2>Page non trouvée</h2>
    <p>Désolé, la page que vous recherchez n'existe pas.</p>
    <a href="/">Retour à l'accueil</a>
</div>

<?php include TEMPLATES_PATH . '/layout/footer.php'; ?>
