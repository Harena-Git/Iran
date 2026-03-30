-- =========================
-- DONNEES PAR DEFAUT
-- =========================

-- Utilisateurs
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@irannews.com', '$2y$10$4g3J8zNVr4jRbH8Xm6LKne8ypAj8G5J3K5L9M8N2P1Q2R3S4T5U6', 'admin'),
('editeur', 'editeur@irannews.com', '$2y$10$4g3J8zNVr4jRbH8Xm6LKne8ypAj8G5J3K5L9M8N2P1Q2R3S4T5U6', 'editor');

-- Categories
INSERT INTO categories (name, slug) VALUES
('Politique', 'politique'),
('Economie', 'economie'),
('International', 'international'),
('Conflit', 'conflit'),
('Société', 'societe');

-- Tags
INSERT INTO tags (name, slug) VALUES
('election', 'election'),
('diplomatie', 'diplomatie'),
('energie', 'energie'),
('reuters', 'reuters'),
('sanctions', 'sanctions'),
('petrole', 'petrole');

-- Articles d'exemple
INSERT INTO articles (title, slug, content, excerpt, category_id, author_id, status, published_at, created_at) VALUES
(
    'Titre de l''article de test',
    'titre-de-larticle-de-test',
    'Ceci est le contenu complet de l''article de test. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
    'Extrait de l''article de test. Lorem ipsum dolor sit amet.',
    1,
    1,
    'published',
    NOW(),
    NOW()
);

-- =========================
-- NOTES
-- =========================
-- Les mots de passe par défaut sont générés avec password_hash() et valent "password"
-- Username: admin / Email: admin@irannews.com / Password: password
-- Username: editeur / Email: editeur@irannews.com / Password: password