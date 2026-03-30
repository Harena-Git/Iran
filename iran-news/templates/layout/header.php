<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Iran News'); ?></title>
</head>
<body>
    <header>
        <h1><a href="/">Iran News</a></h1>
        <nav>
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
