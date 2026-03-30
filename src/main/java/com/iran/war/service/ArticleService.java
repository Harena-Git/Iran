package com.iran.war.service;

import com.iran.war.entity.Article;
import com.iran.war.entity.Author;
import com.iran.war.entity.Category;
import com.iran.war.repository.ArticleRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageRequest;
import org.springframework.data.domain.Pageable;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.text.Normalizer;
import java.util.List;
import java.util.Optional;
import java.util.regex.Pattern;

@Service
@RequiredArgsConstructor
public class ArticleService {

    private final ArticleRepository articleRepository;

    @Transactional(readOnly = true)
    public Page<Article> getPublishedArticles(int page, int size) {
        Pageable pageable = PageRequest.of(page, size);
        return articleRepository.findByStatusOrderByPublishedAtDesc(Article.Status.PUBLISHED, pageable);
    }

    @Transactional(readOnly = true)
    public Page<Article> getPublishedArticlesByCategory(String categorySlug, int page, int size) {
        Pageable pageable = PageRequest.of(page, size);
        return articleRepository.findByCategorySlugAndStatusOrderByPublishedAtDesc(categorySlug, Article.Status.PUBLISHED, pageable);
    }

    @Transactional(readOnly = true)
    public Optional<Article> getPublishedArticleBySlug(String slug) {
        return articleRepository.findBySlugAndStatus(slug, Article.Status.PUBLISHED);
    }

    @Transactional(readOnly = true)
    public Optional<Article> getArticleById(Long id) {
        return articleRepository.findById(id);
    }

    @Transactional(readOnly = true)
    public Optional<Article> getArticleBySlug(String slug) {
        return articleRepository.findBySlug(slug);
    }

    @Transactional(readOnly = true)
    public List<Article> getLatestArticles(int count) {
        return articleRepository.findTop5ByStatusOrderByPublishedAtDesc(Article.Status.PUBLISHED);
    }

    @Transactional(readOnly = true)
    public List<Article> getMostViewedArticles() {
        return articleRepository.findTop5ByStatusOrderByViewCountDesc(Article.Status.PUBLISHED);
    }

    @Transactional(readOnly = true)
    public Page<Article> searchArticles(String query, int page, int size) {
        Pageable pageable = PageRequest.of(page, size);
        return articleRepository.searchByStatusAndQuery(Article.Status.PUBLISHED, query, pageable);
    }

    @Transactional(readOnly = true)
    public Page<Article> getAllArticles(int page, int size) {
        Pageable pageable = PageRequest.of(page, size);
        return articleRepository.findAll(pageable);
    }

    @Transactional
    public Article save(Article article) {
        if (article.getSlug() == null || article.getSlug().isBlank()) {
            article.setSlug(generateSlug(article.getTitle()));
        }
        if (article.getMetaTitle() == null || article.getMetaTitle().isBlank()) {
            article.setMetaTitle(article.getTitle());
        }
        if (article.getMetaDescription() == null || article.getMetaDescription().isBlank()) {
            article.setMetaDescription(article.getSummary());
        }
        return articleRepository.save(article);
    }

    @Transactional
    public Article publish(Long id) {
        Article article = articleRepository.findById(id)
                .orElseThrow(() -> new RuntimeException("Article not found: " + id));
        article.publish();
        return articleRepository.save(article);
    }

    @Transactional
    public void delete(Long id) {
        articleRepository.deleteById(id);
    }

    @Transactional
    public void incrementViewCount(Long id) {
        articleRepository.incrementViewCount(id);
    }

    @Transactional(readOnly = true)
    public long countByStatus(Article.Status status) {
        return articleRepository.countByStatus(status);
    }

    @Transactional(readOnly = true)
    public long countAll() {
        return articleRepository.count();
    }

    public static String generateSlug(String title) {
        if (title == null) return "";
        String normalized = Normalizer.normalize(title, Normalizer.Form.NFD);
        Pattern pattern = Pattern.compile("\\p{InCombiningDiacriticalMarks}+");
        return pattern.matcher(normalized)
                .replaceAll("")
                .toLowerCase()
                .replaceAll("[^a-z0-9\\s-]", "")
                .replaceAll("[\\s]+", "-")
                .replaceAll("-+", "-")
                .replaceAll("^-|-$", "");
    }
}
