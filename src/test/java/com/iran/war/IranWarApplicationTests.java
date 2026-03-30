package com.iran.war;

import com.iran.war.service.ArticleService;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.web.servlet.AutoConfigureMockMvc;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.security.test.context.support.WithMockUser;
import org.springframework.test.web.servlet.MockMvc;

import static org.assertj.core.api.Assertions.assertThat;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.get;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.*;

@SpringBootTest
@AutoConfigureMockMvc
class IranWarApplicationTests {

    @Autowired
    private MockMvc mockMvc;

    @Autowired
    private ArticleService articleService;

    @Test
    void contextLoads() {
        assertThat(articleService).isNotNull();
    }

    @Test
    void homePageLoads() throws Exception {
        mockMvc.perform(get("/"))
                .andExpect(status().isOk())
                .andExpect(view().name("frontoffice/home"));
    }

    @Test
    void searchPageLoads() throws Exception {
        mockMvc.perform(get("/recherche"))
                .andExpect(status().isOk())
                .andExpect(view().name("frontoffice/search"));
    }

    @Test
    void searchWithQueryReturnsResults() throws Exception {
        mockMvc.perform(get("/recherche").param("q", "Iran"))
                .andExpect(status().isOk())
                .andExpect(view().name("frontoffice/search"))
                .andExpect(model().attributeExists("results"));
    }

    @Test
    void robotsTxtIsAccessible() throws Exception {
        mockMvc.perform(get("/robots.txt"))
                .andExpect(status().isOk())
                .andExpect(content().contentTypeCompatibleWith("text/plain"));
    }

    @Test
    void sitemapXmlIsAccessible() throws Exception {
        mockMvc.perform(get("/sitemap.xml"))
                .andExpect(status().isOk());
    }

    @Test
    void adminLoginPageLoads() throws Exception {
        mockMvc.perform(get("/admin/login"))
                .andExpect(status().isOk())
                .andExpect(view().name("backoffice/login"));
    }

    @Test
    void adminDashboardRequiresAuthentication() throws Exception {
        mockMvc.perform(get("/admin/dashboard"))
                .andExpect(status().is3xxRedirection());
    }

    @Test
    @WithMockUser(username = "admin", roles = {"ADMIN"})
    void adminDashboardLoadsWhenAuthenticated() throws Exception {
        mockMvc.perform(get("/admin/dashboard"))
                .andExpect(status().isOk())
                .andExpect(view().name("backoffice/dashboard"));
    }

    @Test
    void slugGenerationWorks() {
        assertThat(ArticleService.generateSlug("Escalade des tensions en Iran"))
                .isEqualTo("escalade-des-tensions-en-iran");
        assertThat(ArticleService.generateSlug("Négociations diplomatiques"))
                .isEqualTo("negociations-diplomatiques");
        assertThat(ArticleService.generateSlug("Économie & Sanctions"))
                .isEqualTo("economie-sanctions");
    }

    @Test
    void publishedArticlesArePresent() {
        long count = articleService.countByStatus(
                com.iran.war.entity.Article.Status.PUBLISHED);
        assertThat(count).isGreaterThan(0);
    }
}
