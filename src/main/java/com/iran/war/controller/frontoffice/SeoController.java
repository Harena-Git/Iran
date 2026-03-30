package com.iran.war.controller.frontoffice;

import com.iran.war.entity.Article;
import com.iran.war.service.ArticleService;
import com.iran.war.service.CategoryService;
import lombok.RequiredArgsConstructor;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.http.MediaType;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ResponseBody;

import java.util.List;

@Controller
@RequiredArgsConstructor
public class SeoController {

    private final ArticleService articleService;
    private final CategoryService categoryService;

    @Value("${site.url}")
    private String siteUrl;

    @GetMapping(value = "/sitemap.xml", produces = MediaType.APPLICATION_XML_VALUE)
    @ResponseBody
    public String sitemap() {
        StringBuilder sb = new StringBuilder();
        sb.append("<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
        sb.append("<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n");

        // Static pages
        sb.append(url(siteUrl + "/", "daily", "1.0"));
        sb.append(url(siteUrl + "/recherche", "weekly", "0.5"));

        // Categories
        categoryService.getAllCategories().forEach(cat ->
                sb.append(url(siteUrl + "/categorie/" + cat.getSlug(), "daily", "0.8")));

        // Articles
        List<Article> articles = articleService.getPublishedArticles(0, 1000).getContent();
        articles.forEach(article -> {
            String lastMod = article.getUpdatedAt() != null
                    ? article.getUpdatedAt().toLocalDate().toString()
                    : (article.getPublishedAt() != null ? article.getPublishedAt().toLocalDate().toString() : "");
            sb.append("<url>\n");
            sb.append("  <loc>").append(siteUrl).append("/article/").append(article.getSlug()).append("</loc>\n");
            if (!lastMod.isEmpty()) {
                sb.append("  <lastmod>").append(lastMod).append("</lastmod>\n");
            }
            sb.append("  <changefreq>weekly</changefreq>\n");
            sb.append("  <priority>0.9</priority>\n");
            sb.append("</url>\n");
        });

        sb.append("</urlset>");
        return sb.toString();
    }

    @GetMapping(value = "/robots.txt", produces = MediaType.TEXT_PLAIN_VALUE)
    @ResponseBody
    public String robots() {
        return "User-agent: *\n" +
               "Allow: /\n" +
               "Disallow: /admin/\n" +
               "Disallow: /h2-console/\n" +
               "\n" +
               "Sitemap: " + siteUrl + "/sitemap.xml\n";
    }

    private String url(String loc, String changefreq, String priority) {
        return "<url>\n" +
               "  <loc>" + loc + "</loc>\n" +
               "  <changefreq>" + changefreq + "</changefreq>\n" +
               "  <priority>" + priority + "</priority>\n" +
               "</url>\n";
    }
}
