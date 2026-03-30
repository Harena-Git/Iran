# Iran War Info

Site d'informations sur la guerre en Iran

## Description

Application web Spring Boot complète permettant de publier et gérer des articles d'actualité sur le conflit en Iran.

### Fonctionnalités

**FrontOffice (site public)**
- Page d'accueil avec les dernières actualités
- Affichage des articles par catégorie
- Page de détail article avec partage réseaux sociaux
- Recherche full-text dans les articles
- Articles les plus vus
- SEO optimisé : balises meta, Open Graph, Twitter Cards, données structurées JSON-LD
- `sitemap.xml` et `robots.txt` automatiques

**BackOffice (administration)**
- Tableau de bord avec statistiques
- CRUD complet pour les articles (titre, résumé, contenu HTML, tags, image)
- CRUD complet pour les catégories
- CRUD complet pour les auteurs
- Gestion du statut des articles (brouillon, publié, archivé)
- Champs SEO par article (meta title, description, mots-clés)
- Authentification sécurisée (Spring Security + BCrypt)

## Stack technique

- **Backend** : Java 17 + Spring Boot 3.2
- **Base de données** : H2 (développement) / MySQL (production)
- **ORM** : Spring Data JPA / Hibernate
- **Sécurité** : Spring Security
- **Templates** : Thymeleaf
- **UI** : Bootstrap 5 + Font Awesome

## Démarrage rapide

### Prérequis
- Java 17+
- Maven 3.6+

### Lancement

```bash
mvn spring-boot:run
```

L'application démarre sur http://localhost:8080

### Accès

| Accès | URL | Identifiants |
|-------|-----|-------------|
| Site public | http://localhost:8080 | — |
| Backoffice | http://localhost:8080/admin/dashboard | admin / admin123 |
| Console H2 | http://localhost:8080/h2-console | sa / (vide) |

## Configuration production (MySQL)

Dans `src/main/resources/application.properties` :

```properties
spring.datasource.url=jdbc:mysql://localhost:3306/iranwardb
spring.datasource.username=root
spring.datasource.password=motdepasse
spring.jpa.database-platform=org.hibernate.dialect.MySQL8Dialect
spring.jpa.hibernate.ddl-auto=update
spring.h2.console.enabled=false
```

## Structure du projet

```
src/main/java/com/iran/war/
├── IranWarApplication.java          # Point d'entrée
├── config/
│   ├── DataInitializer.java         # Données de démonstration
│   ├── JpaConfig.java               # Configuration JPA
│   └── SecurityConfig.java          # Configuration Spring Security
├── controller/
│   ├── backoffice/AdminController.java
│   └── frontoffice/FrontController.java, SeoController.java
├── entity/
│   ├── Article.java, Author.java, Category.java, User.java
├── repository/
│   ├── ArticleRepository.java, AuthorRepository.java, CategoryRepository.java, UserRepository.java
└── service/
    ├── ArticleService.java, AuthorService.java, CategoryService.java, UserDetailsServiceImpl.java
```
