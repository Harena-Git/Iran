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

-- =========================
-- ARTICLES DE DÉMONSTRATION
-- =========================

-- Article 1: Tensions géopolitiques
INSERT INTO articles (title, slug, content, excerpt, category_id, author_id, status, published_at, created_at, updated_at) VALUES 
('Nouvelles tensions dans la région du Golfe Persique', 
 'nouvelles-tensions-golfe-persique',
 '<p>Les récentes évolutions géopolitiques dans la région du Golfe Persique soulèvent de nombreuses interrogations quant à la stabilité de la zone. Les experts internationaux suivent la situation avec une attention particulière.</p>
  <h2>Contexte historique</h2>
  <p>La région a toujours été un carrefour stratégique majeur pour le commerce international et l''approvisionnement énergétique mondial.</p>
  <h2>Développements récents</h2>
  <p>Des mouvements de troupes ont été observés aux frontières, suscitant l''inquiétude des observateurs internationaux.</p>
  <h2>Réactions internationales</h2>
  <p>Les principales puissances mondiales appellent au calme et au dialogue pour désamorcer les tensions.</p>',
 'Analyse des dernières tensions dans le Golfe Persique et leurs implications internationales.',
 1, 1, 'published', NOW() - INTERVAL '2 days', NOW(), NOW());

-- Article 2: Défense et sécurité
INSERT INTO articles (title, slug, content, excerpt, category_id, author_id, status, published_at, created_at, updated_at) VALUES 
('Modernisation des forces armées: un enjeu stratégique', 
 'modernisation-forces-armees',
 '<p>La modernisation des capacités militaires représente un défi majeur pour les nations soucieuses de leur souveraineté et de leur sécurité.</p>
  <h2>Investissements technologiques</h2>
  <p>Les programmes de modernisation mettent l''accent sur la technologie de pointe et l''autonomie stratégique.</p>
  <h2>Coopération régionale</h2>
  <p>Les exercices conjoints permettent d''améliorer l''interopérabilité entre les forces alliées.</p>
  <h2>Perspectives d''avenir</h2>
  <p>Les experts prévoient une accélération des programmes de modernisation dans les années à venir.</p>',
 'Comment les forces armées s''adaptent aux nouvelles menaces du XXIe siècle.',
 1, 1, 'published', NOW() - INTERVAL '1 day', NOW(), NOW());

-- Article 3: Économie régionale
INSERT INTO articles (title, slug, content, excerpt, category_id, author_id, status, published_at, created_at, updated_at) VALUES 
('Économie: défis et opportunités du commerce régional', 
 'economie-defis-commerce-regional',
 '<p>L''économie régionale fait face à des défis structurels tout en offrant des opportunités de croissance significatives.</p>
  <h2>Secteurs porteurs</h2>
  <p>L''énergie, le tourisme et les technologies émergent comme les piliers de l''économie moderne.</p>
  <h2>Intégration économique</h2>
  <p>Les accords commerciaux favorisent les échanges et stimulent le développement des infrastructures.</p>
  <h2>Investissements étrangers</h2>
  <p>Les capitaux internationaux montrent un intérêt croissant pour les marchés émergents de la région.</p>',
 'Analyse économique des dynamiques commerciales régionales et des perspectives de développement.',
 2, 2, 'published', NOW() - INTERVAL '3 days', NOW(), NOW());

-- Associer les images aux articles (copier manuellement les images dans /uploads après le seed)
-- Les images seront visibles via /img-testing/ en développement

-- Insérer des images de test pour les articles
-- Note: Les images réelles doivent être copiées de img-testing/ vers uploads/
INSERT INTO images (article_id, url, alt) VALUES 
(1, '/img-testing/missile.jpg', 'Système de défense antimissile en démonstration'),
(1, '/img-testing/tank.jpg', 'Véhicule blindé lors d''exercices militaires'),
(2, '/img-testing/test1.jpg', 'Infrastructure de défense nationale'),
(2, '/img-testing/missile.jpg', 'Technologie de précision moderne'),
(3, '/img-testing/tank.jpg', 'Logistique militaire et équipements');

-- Associer des tags aux articles
INSERT INTO article_tags (article_id, tag_id) VALUES 
(1, 1), (1, 2), (1, 3),  -- Article 1: Actualité, International, Iran
(2, 1), (2, 3),           -- Article 2: Actualité, Iran
(3, 1), (3, 2);           -- Article 3: Actualité, International

-- Créer des relations entre articles
INSERT INTO related_articles (article_id, related_id) VALUES 
(1, 2), (1, 3),
(2, 1), (2, 3),
(3, 1), (3, 2);
