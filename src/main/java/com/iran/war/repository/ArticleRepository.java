package com.iran.war.repository;

import com.iran.war.entity.Article;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Modifying;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import org.springframework.stereotype.Repository;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.Optional;

@Repository
public interface ArticleRepository extends JpaRepository<Article, Long> {

    Optional<Article> findBySlugAndStatus(String slug, Article.Status status);

    Page<Article> findByStatusOrderByPublishedAtDesc(Article.Status status, Pageable pageable);

    Page<Article> findByCategorySlugAndStatusOrderByPublishedAtDesc(String categorySlug, Article.Status status, Pageable pageable);

    @Query("SELECT a FROM Article a WHERE a.status = :status AND " +
           "(LOWER(a.title) LIKE LOWER(CONCAT('%', :query, '%')) OR " +
           " LOWER(a.summary) LIKE LOWER(CONCAT('%', :query, '%')) OR " +
           " LOWER(a.content) LIKE LOWER(CONCAT('%', :query, '%')))")
    Page<Article> searchByStatusAndQuery(@Param("status") Article.Status status,
                                         @Param("query") String query,
                                         Pageable pageable);

    List<Article> findTop5ByStatusOrderByPublishedAtDesc(Article.Status status);

    List<Article> findTop5ByStatusOrderByViewCountDesc(Article.Status status);

    @Modifying
    @Transactional
    @Query("UPDATE Article a SET a.viewCount = a.viewCount + 1 WHERE a.id = :id")
    void incrementViewCount(@Param("id") Long id);

    long countByStatus(Article.Status status);

    Optional<Article> findBySlug(String slug);
}
