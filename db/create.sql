-- =========================
-- DATABASE
-- =========================
CREATE DATABASE iran_news;

\c iran_news;

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
    article_id INT REFERENCES articles(id) ,
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
    article_id INT REFERENCES articles(id) ,
    tag_id INT REFERENCES tags(id) ,
    PRIMARY KEY (article_id, tag_id)
);

-- =========================
-- TABLE SEO (optionnel mais PRO)
-- =========================

CREATE TABLE seo (
    id SERIAL PRIMARY KEY,
    article_id INT UNIQUE REFERENCES articles(id) ,
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT
);

-- =========================
-- INDEX (PERFORMANCE SEO)
-- =========================
CREATE INDEX idx_articles_slug ON articles(slug);
CREATE INDEX idx_categories_slug ON categories(slug);
CREATE INDEX idx_tags_slug ON tags(slug);

-- =========================
-- DONNEES PAR DEFAUT
-- =========================

-- Categories
INSERT INTO categories (name, slug) VALUES
('Politique', 'politique'),
('Economie', 'economie'),
('International', 'international'),
('Conflit', 'conflit');

-- Tags
INSERT INTO tags (name, slug) VALUES
('Iran', 'iran'),
('Guerre', 'guerre'),
('Moyen-Orient', 'moyen-orient');