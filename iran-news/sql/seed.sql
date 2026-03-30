-- =========================
-- DONNEES INITIALES
-- =========================

-- Insérer un administrateur (password: password)
INSERT INTO users (username, email, password, role) 
VALUES ('admin', 'admin@irannews.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insérer un éditeur (password: password)
INSERT INTO users (username, email, password, role) 
VALUES ('editeur', 'editeur@irannews.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'editor');

-- Insérer des catégories
INSERT INTO categories (name, slug) VALUES 
('Politique', 'politique'),
('Economie', 'economie'),
('Culture', 'culture'),
('Sport', 'sport');

-- Insérer des tags
INSERT INTO tags (name, slug) VALUES 
('Actualité', 'actualite'),
('International', 'international'),
('Iran', 'iran');
