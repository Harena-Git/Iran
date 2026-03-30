-- =========================
-- DATABASE INITIALIZATION
-- =========================

-- Créer la base de données
-- CREATE DATABASE iran_news; (déjà créée par docker-compose)

-- =========================
-- TABLE USERS
-- =========================

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'editor', -- admin, editor
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- TABLE CATEGORIE
-- =========================

CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- TABLE ARTICLE
-- =========================

CREATE TABLE articles (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT,
    category_id INT REFERENCES categories(id) ON DELETE SET NULL,
    author_id INT REFERENCES users(id) ON DELETE SET NULL,
    status VARCHAR(20) DEFAULT 'draft', -- draft / published
    published_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- TABLE IMAGES
-- =========================

CREATE TABLE images (
    id SERIAL PRIMARY KEY,
    article_id INT REFERENCES articles(id) ON DELETE CASCADE,
    url TEXT NOT NULL,
    alt TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- TABLE TAGS
-- =========================

CREATE TABLE tags (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    slug VARCHAR(60) UNIQUE NOT NULL
);

-- =========================
-- TABLE RELATION ARTICLE-TAGS
-- =========================

CREATE TABLE article_tags (
    article_id INT REFERENCES articles(id) ON DELETE CASCADE,
    tag_id INT REFERENCES tags(id) ON DELETE CASCADE,
    PRIMARY KEY (article_id, tag_id)
);

-- =========================
-- TABLE ARTICLES REFERENCES
-- =========================

CREATE TABLE related_articles (
    article_id INT REFERENCES articles(id) ON DELETE CASCADE,
    related_id INT REFERENCES articles(id) ON DELETE CASCADE,
    PRIMARY KEY (article_id, related_id)
);


-- =========================
-- TABLE SEO
-- =========================

CREATE TABLE seo (
    id SERIAL PRIMARY KEY,
    article_id INT UNIQUE REFERENCES articles(id) ON DELETE CASCADE,
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT
);

-- =========================
-- INDEX (PERFORMANCE)
-- =========================

CREATE INDEX idx_articles_slug ON articles(slug);
CREATE INDEX idx_articles_category_id ON articles(category_id);
CREATE INDEX idx_articles_author_id ON articles(author_id);
CREATE INDEX idx_articles_status ON articles(status);
CREATE INDEX idx_categories_slug ON categories(slug);
CREATE INDEX idx_tags_slug ON tags(slug);

-- =========================
-- DONNEES INITIALES (SEED)
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

-- Insérer des articles de démonstration
INSERT INTO articles (title, slug, content, excerpt, category_id, author_id, status, published_at, created_at, updated_at) VALUES 
('Nouvelles tensions dans la région du Golfe Persique', 
 'nouvelles-tensions-golfe-persique',
 '<p>Les récentes évolutions géopolitiques dans la région du Golfe Persique soulèvent de nombreuses interrogations quant à la stabilité de la zone. Les experts internationaux suivent la situation avec une attention particulière.</p><h2>Contexte historique</h2><p>La région a toujours été un carrefour stratégique majeur pour le commerce international et l''approvisionnement énergétique mondial.</p><h2>Développements récents</h2><p>Des mouvements de troupes ont été observés aux frontières, suscitant l''inquiétude des observateurs internationaux.</p>',
 'Analyse des dernières tensions dans le Golfe Persique et leurs implications internationales.',
 1, 1, 'published', NOW() - INTERVAL '2 days', NOW(), NOW());

INSERT INTO articles (title, slug, content, excerpt, category_id, author_id, status, published_at, created_at, updated_at) VALUES 
('Modernisation des forces armées: un enjeu stratégique', 
 'modernisation-forces-armees',
 '<p>La modernisation des capacités militaires représente un défi majeur pour les nations soucieuses de leur souveraineté et de leur sécurité.</p><h2>Investissements technologiques</h2><p>Les programmes de modernisation mettent l''accent sur la technologie de pointe et l''autonomie stratégique.</p><h2>Coopération régionale</h2><p>Les exercices conjoints permettent d''améliorer l''interopérabilité entre les forces alliées.</p>',
 'Comment les forces armées s''adaptent aux nouvelles menaces du XXIe siècle.',
 1, 1, 'published', NOW() - INTERVAL '1 day', NOW(), NOW());

INSERT INTO articles (title, slug, content, excerpt, category_id, author_id, status, published_at, created_at, updated_at) VALUES 
('Économie: défis et opportunités du commerce régional', 
 'economie-defis-commerce-regional',
 '<p>L''économie régionale fait face à des défis structurels tout en offrant des opportunités de croissance significatives.</p><h2>Secteurs porteurs</h2><p>L''énergie, le tourisme et les technologies émergent comme les piliers de l''économie moderne.</p><h2>Intégration économique</h2><p>Les accords commerciaux favorisent les échanges et stimulent le développement des infrastructures.</p>',
 'Analyse économique des dynamiques commerciales régionales et des perspectives de développement.',
 2, 2, 'published', NOW() - INTERVAL '3 days', NOW(), NOW());

-- Insérer des images de test pour les articles
INSERT INTO images (article_id, url, alt) VALUES 
(1, '/img-testing/missile.jpg', 'Système de défense antimissile en démonstration'),
(1, '/img-testing/tank.jpg', 'Véhicule blindé lors d''exercices militaires'),
(2, '/img-testing/test1.jpg', 'Infrastructure de défense nationale'),
(2, '/img-testing/missile.jpg', 'Technologie de précision moderne'),
(3, '/img-testing/tank.jpg', 'Logistique militaire et équipements');

-- Associer des tags aux articles
INSERT INTO article_tags (article_id, tag_id) VALUES 
(1, 1), (1, 2), (1, 3),
(2, 1), (2, 3),
(3, 1), (3, 2);

-- Créer des relations entre articles
INSERT INTO related_articles (article_id, related_id) VALUES 
(1, 2), (1, 3),
(2, 1), (2, 3),
(3, 1), (3, 2);


