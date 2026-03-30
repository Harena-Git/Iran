package com.iran.war.controller.backoffice;

import com.iran.war.entity.Article;
import com.iran.war.entity.Author;
import com.iran.war.entity.Category;
import com.iran.war.service.ArticleService;
import com.iran.war.service.AuthorService;
import com.iran.war.service.CategoryService;
import lombok.RequiredArgsConstructor;
import org.springframework.data.domain.Page;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import java.util.Arrays;
import java.util.List;
import java.util.stream.Collectors;

@Controller
@RequestMapping("/admin")
@RequiredArgsConstructor
public class AdminController {

    private final ArticleService articleService;
    private final CategoryService categoryService;
    private final AuthorService authorService;

    @GetMapping("/login")
    public String login() {
        return "backoffice/login";
    }

    @GetMapping("/dashboard")
    public String dashboard(Model model) {
        model.addAttribute("pageTitle", "Tableau de bord");
        model.addAttribute("totalArticles", articleService.countAll());
        model.addAttribute("publishedArticles", articleService.countByStatus(Article.Status.PUBLISHED));
        model.addAttribute("draftArticles", articleService.countByStatus(Article.Status.DRAFT));
        model.addAttribute("totalCategories", categoryService.countAll());
        model.addAttribute("totalAuthors", authorService.countAll());
        model.addAttribute("latestArticles", articleService.getLatestArticles(5));
        return "backoffice/dashboard";
    }

    // ===== ARTICLES =====

    @GetMapping("/articles")
    public String listArticles(Model model, @RequestParam(defaultValue = "0") int page) {
        Page<Article> articles = articleService.getAllArticles(page, 10);
        model.addAttribute("pageTitle", "Articles");
        model.addAttribute("articles", articles);
        model.addAttribute("currentPage", page);
        model.addAttribute("totalPages", articles.getTotalPages());
        return "backoffice/articles/list";
    }

    @GetMapping("/articles/nouveau")
    public String newArticleForm(Model model) {
        model.addAttribute("pageTitle", "Nouvel article");
        model.addAttribute("article", new Article());
        model.addAttribute("categories", categoryService.getAllCategories());
        model.addAttribute("authors", authorService.getAllAuthors());
        model.addAttribute("statuses", Article.Status.values());
        model.addAttribute("tagsAsString", "");
        return "backoffice/articles/form";
    }

    @GetMapping("/articles/modifier/{id}")
    public String editArticleForm(@PathVariable Long id, Model model) {
        Article article = articleService.getArticleById(id)
                .orElseThrow(() -> new RuntimeException("Article not found: " + id));
        model.addAttribute("pageTitle", "Modifier l'article");
        model.addAttribute("article", article);
        model.addAttribute("categories", categoryService.getAllCategories());
        model.addAttribute("authors", authorService.getAllAuthors());
        model.addAttribute("statuses", Article.Status.values());
        model.addAttribute("tagsAsString", String.join(", ", article.getTags()));
        return "backoffice/articles/form";
    }

    @PostMapping("/articles/sauvegarder")
    public String saveArticle(@ModelAttribute Article article,
                               @RequestParam(required = false) Long categoryId,
                               @RequestParam(required = false) Long authorId,
                               @RequestParam(required = false, defaultValue = "") String tagsInput,
                               RedirectAttributes redirectAttributes) {
        if (categoryId != null) {
            categoryService.findById(categoryId).ifPresent(article::setCategory);
        }
        if (authorId != null) {
            authorService.findById(authorId).ifPresent(article::setAuthor);
        }
        if (!tagsInput.isBlank()) {
            List<String> tags = Arrays.stream(tagsInput.split(","))
                    .map(String::trim)
                    .filter(t -> !t.isEmpty())
                    .collect(Collectors.toList());
            article.setTags(tags);
        }
        if (article.getStatus() == Article.Status.PUBLISHED && article.getPublishedAt() == null) {
            article.publish();
        }
        articleService.save(article);
        redirectAttributes.addFlashAttribute("success", "Article sauvegardé avec succès.");
        return "redirect:/admin/articles";
    }

    @PostMapping("/articles/publier/{id}")
    public String publishArticle(@PathVariable Long id, RedirectAttributes redirectAttributes) {
        articleService.publish(id);
        redirectAttributes.addFlashAttribute("success", "Article publié avec succès.");
        return "redirect:/admin/articles";
    }

    @PostMapping("/articles/supprimer/{id}")
    public String deleteArticle(@PathVariable Long id, RedirectAttributes redirectAttributes) {
        articleService.delete(id);
        redirectAttributes.addFlashAttribute("success", "Article supprimé avec succès.");
        return "redirect:/admin/articles";
    }

    // ===== CATEGORIES =====

    @GetMapping("/categories")
    public String listCategories(Model model) {
        model.addAttribute("pageTitle", "Catégories");
        model.addAttribute("categories", categoryService.getAllCategories());
        return "backoffice/categories/list";
    }

    @GetMapping("/categories/nouvelle")
    public String newCategoryForm(Model model) {
        model.addAttribute("pageTitle", "Nouvelle catégorie");
        model.addAttribute("category", new Category());
        return "backoffice/categories/form";
    }

    @GetMapping("/categories/modifier/{id}")
    public String editCategoryForm(@PathVariable Long id, Model model) {
        Category category = categoryService.findById(id)
                .orElseThrow(() -> new RuntimeException("Category not found: " + id));
        model.addAttribute("pageTitle", "Modifier la catégorie");
        model.addAttribute("category", category);
        return "backoffice/categories/form";
    }

    @PostMapping("/categories/sauvegarder")
    public String saveCategory(@ModelAttribute Category category,
                                RedirectAttributes redirectAttributes) {
        categoryService.save(category);
        redirectAttributes.addFlashAttribute("success", "Catégorie sauvegardée avec succès.");
        return "redirect:/admin/categories";
    }

    @PostMapping("/categories/supprimer/{id}")
    public String deleteCategory(@PathVariable Long id, RedirectAttributes redirectAttributes) {
        categoryService.delete(id);
        redirectAttributes.addFlashAttribute("success", "Catégorie supprimée avec succès.");
        return "redirect:/admin/categories";
    }

    // ===== AUTHORS =====

    @GetMapping("/auteurs")
    public String listAuthors(Model model) {
        model.addAttribute("pageTitle", "Auteurs");
        model.addAttribute("authors", authorService.getAllAuthors());
        return "backoffice/authors/list";
    }

    @GetMapping("/auteurs/nouveau")
    public String newAuthorForm(Model model) {
        model.addAttribute("pageTitle", "Nouvel auteur");
        model.addAttribute("author", new Author());
        return "backoffice/authors/form";
    }

    @GetMapping("/auteurs/modifier/{id}")
    public String editAuthorForm(@PathVariable Long id, Model model) {
        Author author = authorService.findById(id)
                .orElseThrow(() -> new RuntimeException("Author not found: " + id));
        model.addAttribute("pageTitle", "Modifier l'auteur");
        model.addAttribute("author", author);
        return "backoffice/authors/form";
    }

    @PostMapping("/auteurs/sauvegarder")
    public String saveAuthor(@ModelAttribute Author author,
                              RedirectAttributes redirectAttributes) {
        authorService.save(author);
        redirectAttributes.addFlashAttribute("success", "Auteur sauvegardé avec succès.");
        return "redirect:/admin/auteurs";
    }

    @PostMapping("/auteurs/supprimer/{id}")
    public String deleteAuthor(@PathVariable Long id, RedirectAttributes redirectAttributes) {
        authorService.delete(id);
        redirectAttributes.addFlashAttribute("success", "Auteur supprimé avec succès.");
        return "redirect:/admin/auteurs";
    }
}
