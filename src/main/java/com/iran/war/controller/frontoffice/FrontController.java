package com.iran.war.controller.frontoffice;

import com.iran.war.entity.Article;
import com.iran.war.entity.Category;
import com.iran.war.service.ArticleService;
import com.iran.war.service.CategoryService;
import lombok.RequiredArgsConstructor;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.data.domain.Page;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestParam;

import java.util.List;

@Controller
@RequiredArgsConstructor
public class FrontController {

    private final ArticleService articleService;
    private final CategoryService categoryService;

    @Value("${site.name}")
    private String siteName;

    @Value("${site.description}")
    private String siteDescription;

    @Value("${site.url}")
    private String siteUrl;

    @Value("${site.keywords}")
    private String siteKeywords;

    @GetMapping("/")
    public String home(Model model,
                       @RequestParam(defaultValue = "0") int page) {
        Page<Article> articles = articleService.getPublishedArticles(page, 6);
        List<Article> latestArticles = articleService.getLatestArticles(5);
        List<Article> mostViewedArticles = articleService.getMostViewedArticles();
        List<Category> categories = categoryService.getAllCategories();

        model.addAttribute("articles", articles);
        model.addAttribute("latestArticles", latestArticles);
        model.addAttribute("mostViewedArticles", mostViewedArticles);
        model.addAttribute("categories", categories);
        model.addAttribute("currentPage", page);
        model.addAttribute("totalPages", articles.getTotalPages());

        addSeoAttributes(model, siteName, siteDescription, siteUrl, siteKeywords, null);
        return "frontoffice/home";
    }

    @GetMapping("/article/{slug}")
    public String article(@PathVariable String slug, Model model) {
        Article article = articleService.getPublishedArticleBySlug(slug)
                .orElseThrow(() -> new RuntimeException("Article not found: " + slug));

        articleService.incrementViewCount(article.getId());

        List<Article> latestArticles = articleService.getLatestArticles(5);
        List<Category> categories = categoryService.getAllCategories();

        String canonicalUrl = siteUrl + "/article/" + slug;
        String metaDesc = article.getMetaDescription() != null ? article.getMetaDescription() : article.getSummary();
        String metaKeywords = article.getMetaKeywords() != null ? article.getMetaKeywords() : siteKeywords;

        model.addAttribute("article", article);
        model.addAttribute("latestArticles", latestArticles);
        model.addAttribute("mostViewedArticles", latestArticles);
        model.addAttribute("categories", categories);

        addSeoAttributes(model, article.getMetaTitle() + " | " + siteName,
                metaDesc, canonicalUrl, metaKeywords, article.getImageUrl());
        model.addAttribute("structuredData", buildArticleStructuredData(article, siteUrl));
        return "frontoffice/article";
    }

    @GetMapping("/categorie/{slug}")
    public String category(@PathVariable String slug,
                            Model model,
                            @RequestParam(defaultValue = "0") int page) {
        Category category = categoryService.findBySlug(slug)
                .orElseThrow(() -> new RuntimeException("Category not found: " + slug));

        Page<Article> articles = articleService.getPublishedArticlesByCategory(slug, page, 6);
        List<Category> categories = categoryService.getAllCategories();

        String canonicalUrl = siteUrl + "/categorie/" + slug;

        model.addAttribute("category", category);
        model.addAttribute("articles", articles);
        model.addAttribute("categories", categories);
        model.addAttribute("currentPage", page);
        model.addAttribute("totalPages", articles.getTotalPages());
        model.addAttribute("mostViewedArticles", articleService.getMostViewedArticles());

        addSeoAttributes(model,
                category.getName() + " | " + siteName,
                category.getDescription(),
                canonicalUrl, siteKeywords, null);
        return "frontoffice/category";
    }

    @GetMapping("/recherche")
    public String search(@RequestParam(required = false, defaultValue = "") String q,
                          Model model,
                          @RequestParam(defaultValue = "0") int page) {
        Page<Article> results = articleService.searchArticles(q, page, 6);
        List<Category> categories = categoryService.getAllCategories();

        model.addAttribute("query", q);
        model.addAttribute("results", results);
        model.addAttribute("categories", categories);
        model.addAttribute("currentPage", page);
        model.addAttribute("mostViewedArticles", articleService.getMostViewedArticles());
        model.addAttribute("totalPages", results.getTotalPages());

        addSeoAttributes(model, "Recherche : " + q + " | " + siteName,
                "Résultats de recherche pour : " + q,
                siteUrl + "/recherche?q=" + q, siteKeywords, null);
        return "frontoffice/search";
    }

    private void addSeoAttributes(Model model, String title, String description,
                                   String canonicalUrl, String keywords, String ogImage) {
        model.addAttribute("siteTitle", title);
        model.addAttribute("metaDescription", description);
        model.addAttribute("canonicalUrl", canonicalUrl);
        model.addAttribute("metaKeywords", keywords);
        model.addAttribute("siteName", siteName);
        model.addAttribute("siteUrl", siteUrl);
        if (ogImage != null) {
            model.addAttribute("ogImage", ogImage);
        }
    }

    private String buildArticleStructuredData(Article article, String baseUrl) {
        String publishedAt = article.getPublishedAt() != null ? article.getPublishedAt().toString() : "";
        String updatedAt = article.getUpdatedAt() != null ? article.getUpdatedAt().toString() : publishedAt;
        String authorName = article.getAuthor() != null ? article.getAuthor().getName() : "Rédaction";
        return String.format(
            "{\"@context\":\"https://schema.org\",\"@type\":\"NewsArticle\"," +
            "\"headline\":\"%s\",\"description\":\"%s\"," +
            "\"datePublished\":\"%s\",\"dateModified\":\"%s\"," +
            "\"author\":{\"@type\":\"Person\",\"name\":\"%s\"}," +
            "\"publisher\":{\"@type\":\"Organization\",\"name\":\"Iran War Info\"}," +
            "\"url\":\"%s/article/%s\"}",
            escape(article.getTitle()),
            escape(article.getSummary() != null ? article.getSummary() : ""),
            publishedAt, updatedAt,
            escape(authorName),
            baseUrl, article.getSlug()
        );
    }

    private String escape(String s) {
        return s == null ? "" : s.replace("\"", "\\\"").replace("\n", " ");
    }
}
