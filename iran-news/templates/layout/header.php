<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Iran News - Actualités sur la guerre en Iran'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($description ?? 'Iran News - Site d\'information sur la guerre et l\'actualité en Iran'); ?>">
    <?php if (!empty($keywords)): ?>
        <meta name="keywords" content="<?php echo htmlspecialchars($keywords); ?>">
    <?php endif; ?>
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="<?php echo htmlspecialchars($title ?? 'Iran News'); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($description ?? 'Site d\'information sur la guerre en Iran'); ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <!-- h1 unique du site uniquement dans le header, les pages utilisent h2 -->
        <h1><a href="/">Iran News</a></h1>
        <p>Site d'information sur la guerre en Iran</p>
        <nav aria-label="Navigation principale">
            <ul>
                <li><a href="/">Accueil</a></li>
                <?php foreach ($categories ?? [] as $cat): ?>
                    <li><a href="/category/<?php echo htmlspecialchars($cat['slug']); ?>">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </a></li>
                <?php endforeach; ?>
                <?php if (Session::isLoggedIn()): ?>
                    <li><a href="/admin">Backoffice</a></li>
                    <li><a href="/admin/logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="/admin/login">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
